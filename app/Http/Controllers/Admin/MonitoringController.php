<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class MonitoringController extends Controller
{
    /**
     * Tampilkan halaman monitoring.
     */
    public function index()
    {
        return view('admin.monitoring.index');
    }

    /**
     * Endpoint AJAX: kembalikan semua metrik sekaligus.
     * Didesain seringan mungkin — satu request, satu response.
     */
    public function metrics(): JsonResponse
    {
        return response()->json([
            'cpu'     => $this->getCpu(),
            'memory'  => $this->getMemory(),
            'disk'    => $this->getDisk(),
            'network' => $this->getNetwork(),
            'gpu'     => $this->getGpu(),
            'uptime'  => $this->getUptime(),
        ]);
    }

    // ─────────────────────────────────────────────
    // Private helpers — setiap helper semaksimal
    // mungkin menghindari proses berat.
    // ─────────────────────────────────────────────

    private function getCpu(): array
    {
        $usage = 0;
        $cores = 1;

        if (PHP_OS_FAMILY === 'Linux') {
            // Baca /proc/stat dua kali dengan jeda sangat singkat (50ms)
            $s1 = $this->readProcStat();
            usleep(50000); // 50 ms
            $s2 = $this->readProcStat();

            $idle1 = $s1[3] + $s1[4];
            $idle2 = $s2[3] + $s2[4];
            $total1 = array_sum($s1);
            $total2 = array_sum($s2);

            $dTotal = $total2 - $total1;
            $dIdle  = $idle2  - $idle1;

            $usage = $dTotal > 0 ? round((1 - $dIdle / $dTotal) * 100, 1) : 0;
            $cores = (int) shell_exec('nproc 2>/dev/null') ?: 1;
        } elseif (PHP_OS_FAMILY === 'Darwin') {
            $out   = shell_exec("top -l 1 -n 0 2>/dev/null | grep 'CPU usage'");
            preg_match('/(\d+\.\d+)% user/', $out ?? '', $m);
            $usage = isset($m[1]) ? (float) $m[1] : 0;
            $cores = (int) shell_exec('sysctl -n hw.ncpu 2>/dev/null') ?: 1;
        } elseif (PHP_OS_FAMILY === 'Windows') {
            $out   = shell_exec('wmic cpu get loadpercentage /value 2>NUL');
            preg_match('/LoadPercentage=(\d+)/', $out ?? '', $m);
            $usage = isset($m[1]) ? (float) $m[1] : 0;
            $cores = (int) shell_exec('echo %NUMBER_OF_PROCESSORS%') ?: 1;
        }

        // Load average (Linux/Mac only)
        $load = [0, 0, 0];
        if (function_exists('sys_getloadavg')) {
            $la   = sys_getloadavg();
            $load = [
                round($la[0], 2),
                round($la[1], 2),
                round($la[2], 2),
            ];
        }

        return [
            'usage' => $usage,
            'cores' => $cores,
            'load'  => $load,
        ];
    }

    private function readProcStat(): array
    {
        $line = explode(' ', trim(shell_exec("head -1 /proc/stat 2>/dev/null") ?? ''));
        array_shift($line); // buang 'cpu'
        return array_map('intval', $line);
    }

    private function getMemory(): array
    {
        $total = $used = $free = $cached = 0;

        if (PHP_OS_FAMILY === 'Linux') {
            $raw = shell_exec('cat /proc/meminfo 2>/dev/null') ?? '';
            preg_match('/MemTotal:\s+(\d+)/',     $raw, $mt);
            preg_match('/MemAvailable:\s+(\d+)/', $raw, $ma);
            preg_match('/Cached:\s+(\d+)/',       $raw, $mc);

            $total  = isset($mt[1]) ? (int)$mt[1] * 1024 : 0;
            $avail  = isset($ma[1]) ? (int)$ma[1] * 1024 : 0;
            $cached = isset($mc[1]) ? (int)$mc[1] * 1024 : 0;
            $used   = $total - $avail;
            $free   = $avail;
        } elseif (PHP_OS_FAMILY === 'Darwin') {
            $total  = (int) shell_exec('sysctl -n hw.memsize 2>/dev/null');
            $vmstat = shell_exec('vm_stat 2>/dev/null') ?? '';
            preg_match('/Pages free:\s+(\d+)/', $vmstat, $pf);
            $pageSize = 4096;
            $free  = isset($pf[1]) ? (int)$pf[1] * $pageSize : 0;
            $used  = $total - $free;
        } elseif (PHP_OS_FAMILY === 'Windows') {
            $out   = shell_exec('wmic OS get TotalVisibleMemorySize,FreePhysicalMemory /value 2>NUL') ?? '';
            preg_match('/TotalVisibleMemorySize=(\d+)/', $out, $mt);
            preg_match('/FreePhysicalMemory=(\d+)/',     $out, $mf);
            $total = isset($mt[1]) ? (int)$mt[1] * 1024 : 0;
            $free  = isset($mf[1]) ? (int)$mf[1] * 1024 : 0;
            $used  = $total - $free;
        }

        $pct = $total > 0 ? round($used / $total * 100, 1) : 0;

        return [
            'total'      => $total,
            'used'       => $used,
            'free'       => $free,
            'cached'     => $cached,
            'percent'    => $pct,
            'total_hr'   => $this->formatBytes($total),
            'used_hr'    => $this->formatBytes($used),
            'free_hr'    => $this->formatBytes($free),
        ];
    }

    private function getDisk(): array
    {
        $path  = base_path();
        $total = disk_total_space($path) ?: 0;
        $free  = disk_free_space($path)  ?: 0;
        $used  = $total - $free;
        $pct   = $total > 0 ? round($used / $total * 100, 1) : 0;

        return [
            'total'    => $total,
            'used'     => $used,
            'free'     => $free,
            'percent'  => $pct,
            'total_hr' => $this->formatBytes($total),
            'used_hr'  => $this->formatBytes($used),
            'free_hr'  => $this->formatBytes($free),
            'path'     => $path,
        ];
    }

    private function getNetwork(): array
    {
        $rx = $tx = 0;
        $iface = 'N/A';

        if (PHP_OS_FAMILY === 'Linux') {
            $raw = shell_exec('cat /proc/net/dev 2>/dev/null') ?? '';
            $lines = explode("\n", $raw);
            foreach ($lines as $line) {
                $line = trim($line);
                // Skip loopback & header
                if (!$line || str_starts_with($line, 'lo:') || str_starts_with($line, 'Inter')) continue;
                preg_match('/^(\S+):\s+(\d+)\s+\d+\s+\d+\s+\d+\s+\d+\s+\d+\s+\d+\s+\d+\s+(\d+)/', $line, $m);
                if (isset($m[1])) {
                    $iface = $m[1];
                    $rx   += (int)$m[2];
                    $tx   += (int)$m[3];
                    break; // ambil interface pertama saja
                }
            }
        }

        return [
            'interface' => $iface,
            'rx'        => $rx,
            'tx'        => $tx,
            'rx_hr'     => $this->formatBytes($rx),
            'tx_hr'     => $this->formatBytes($tx),
        ];
    }

    private function getGpu(): array
    {
        // Coba nvidia-smi terlebih dahulu (sangat ringan)
        $out = shell_exec('nvidia-smi --query-gpu=name,utilization.gpu,memory.used,memory.total,temperature.gpu --format=csv,noheader,nounits 2>/dev/null');

        if ($out && trim($out) !== '') {
            $parts = array_map('trim', explode(',', trim($out)));
            return [
                'available'  => true,
                'name'       => $parts[0] ?? 'GPU',
                'usage'      => isset($parts[1]) ? (float)$parts[1] : 0,
                'mem_used'   => isset($parts[2]) ? (int)$parts[2] * 1024 * 1024 : 0,
                'mem_total'  => isset($parts[3]) ? (int)$parts[3] * 1024 * 1024 : 0,
                'temp'       => isset($parts[4]) ? (int)$parts[4] : 0,
                'mem_used_hr'  => isset($parts[2]) ? $parts[2] . ' MiB' : '-',
                'mem_total_hr' => isset($parts[3]) ? $parts[3] . ' MiB' : '-',
            ];
        }

        return [
            'available' => false,
            'name'      => 'Tidak terdeteksi',
            'usage'     => 0,
            'temp'      => 0,
        ];
    }

    private function getUptime(): array
    {
        $seconds = 0;

        if (PHP_OS_FAMILY === 'Linux') {
            $raw     = shell_exec('cat /proc/uptime 2>/dev/null') ?? '';
            $seconds = (int) explode(' ', $raw)[0];
        } elseif (PHP_OS_FAMILY === 'Darwin') {
            $raw = shell_exec("sysctl -n kern.boottime 2>/dev/null") ?? '';
            preg_match('/sec = (\d+)/', $raw, $m);
            $seconds = isset($m[1]) ? time() - (int)$m[1] : 0;
        }

        $d = intdiv($seconds, 86400);
        $h = intdiv($seconds % 86400, 3600);
        $i = intdiv($seconds % 3600, 60);

        return [
            'seconds'  => $seconds,
            'days'     => $d,
            'hours'    => $h,
            'minutes'  => $i,
            'readable' => "{$d}h {$h}j {$i}m",
        ];
    }

    private function formatBytes(int $bytes, int $precision = 1): string
    {
        if ($bytes <= 0) return '0 B';
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $i     = (int) floor(log($bytes, 1024));
        return round($bytes / pow(1024, $i), $precision) . ' ' . $units[$i];
    }
}