{{-- resources/views/admin/monitoring/index.blade.php --}}

@extends('layouts.app') {{-- Sesuaikan dengan layout utama Anda --}}

@section('title', 'Server Monitoring')

@section('content')

<div class="p-6">

    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-xl font-bold text-gray-800">Server Monitoring</h2>
            <p class="text-sm text-gray-500 mt-0.5">Real-time resource usage</p>
        </div>
        <div class="flex items-center gap-3">
            <span id="last-updated" class="text-xs text-gray-400">Menunggu data...</span>
            <span class="inline-flex items-center gap-1.5 text-xs text-gray-500 bg-gray-100 border border-gray-200 rounded px-2.5 py-1">
                <span class="w-2 h-2 rounded-full bg-green-400 animate-pulse"></span>
                Refresh <span id="countdown" class="font-semibold text-gray-700">5</span>s
            </span>
        </div>
    </div>

    {{-- Grid Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-4">

        {{-- CPU --}}
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5">
            <div class="flex items-center justify-between mb-3">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center">
                        <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <rect x="4" y="4" width="16" height="16" rx="2" stroke-width="2"/>
                            <path d="M9 9h6v6H9z" stroke-width="2"/>
                            <path d="M9 1v3M15 1v3M9 20v3M15 20v3M1 9h3M1 15h3M20 9h3M20 15h3" stroke-width="2"/>
                        </svg>
                    </div>
                    <span class="text-sm font-semibold text-gray-600">CPU</span>
                </div>
                <span id="cpu-badge" class="text-xs font-semibold px-2 py-0.5 rounded-full bg-gray-100 text-gray-500">—</span>
            </div>

            <div class="flex items-end gap-1 mb-2">
                <span id="cpu-val" class="text-3xl font-bold text-gray-800">—</span>
                <span class="text-base text-gray-400 mb-1">%</span>
            </div>

            <div class="w-full bg-gray-100 rounded-full h-2 mb-4">
                <div id="cpu-bar" class="h-2 rounded-full bg-blue-500 transition-all duration-500" style="width:0%"></div>
            </div>

            <div class="grid grid-cols-2 gap-y-2 gap-x-4 text-xs">
                <div>
                    <p class="text-gray-400">Cores</p>
                    <p id="cpu-cores" class="font-semibold text-gray-700">—</p>
                </div>
                <div>
                    <p class="text-gray-400">Load 1m</p>
                    <p id="cpu-load1" class="font-semibold text-gray-700">—</p>
                </div>
                <div>
                    <p class="text-gray-400">Load 5m</p>
                    <p id="cpu-load5" class="font-semibold text-gray-700">—</p>
                </div>
                <div>
                    <p class="text-gray-400">Load 15m</p>
                    <p id="cpu-load15" class="font-semibold text-gray-700">—</p>
                </div>
            </div>
        </div>

        {{-- Memory --}}
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5">
            <div class="flex items-center justify-between mb-3">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg bg-indigo-50 flex items-center justify-center">
                        <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <rect x="2" y="7" width="20" height="10" rx="2" stroke-width="2"/>
                            <path d="M6 7V5M10 7V5M14 7V5M18 7V5M6 17v2M10 17v2M14 17v2M18 17v2" stroke-width="2"/>
                        </svg>
                    </div>
                    <span class="text-sm font-semibold text-gray-600">Memory</span>
                </div>
                <span id="mem-badge" class="text-xs font-semibold px-2 py-0.5 rounded-full bg-gray-100 text-gray-500">—</span>
            </div>

            <div class="mb-2">
                <span id="mem-val" class="text-2xl font-bold text-gray-800">—</span>
            </div>

            <div class="w-full bg-gray-100 rounded-full h-2 mb-4">
                <div id="mem-bar" class="h-2 rounded-full bg-indigo-500 transition-all duration-500" style="width:0%"></div>
            </div>

            <div class="grid grid-cols-2 gap-y-2 gap-x-4 text-xs">
                <div>
                    <p class="text-gray-400">Total</p>
                    <p id="mem-total" class="font-semibold text-gray-700">—</p>
                </div>
                <div>
                    <p class="text-gray-400">Terpakai</p>
                    <p id="mem-used" class="font-semibold text-gray-700">—</p>
                </div>
                <div>
                    <p class="text-gray-400">Bebas</p>
                    <p id="mem-free" class="font-semibold text-gray-700">—</p>
                </div>
                <div>
                    <p class="text-gray-400">Cache</p>
                    <p id="mem-cached" class="font-semibold text-gray-700">—</p>
                </div>
            </div>
        </div>

        {{-- Disk --}}
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5">
            <div class="flex items-center justify-between mb-3">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg bg-green-50 flex items-center justify-center">
                        <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <ellipse cx="12" cy="5" rx="9" ry="3" stroke-width="2"/>
                            <path d="M21 12c0 1.66-4 3-9 3s-9-1.34-9-3" stroke-width="2"/>
                            <path d="M3 5v14c0 1.66 4 3 9 3s9-1.34 9-3V5" stroke-width="2"/>
                        </svg>
                    </div>
                    <span class="text-sm font-semibold text-gray-600">Disk</span>
                </div>
                <span id="disk-badge" class="text-xs font-semibold px-2 py-0.5 rounded-full bg-gray-100 text-gray-500">—</span>
            </div>

            <div class="mb-2">
                <span id="disk-val" class="text-2xl font-bold text-gray-800">—</span>
            </div>

            <div class="w-full bg-gray-100 rounded-full h-2 mb-4">
                <div id="disk-bar" class="h-2 rounded-full bg-green-500 transition-all duration-500" style="width:0%"></div>
            </div>

            <div class="grid grid-cols-2 gap-y-2 gap-x-4 text-xs">
                <div>
                    <p class="text-gray-400">Total</p>
                    <p id="disk-total" class="font-semibold text-gray-700">—</p>
                </div>
                <div>
                    <p class="text-gray-400">Terpakai</p>
                    <p id="disk-used" class="font-semibold text-gray-700">—</p>
                </div>
                <div>
                    <p class="text-gray-400">Bebas</p>
                    <p id="disk-free" class="font-semibold text-gray-700">—</p>
                </div>
                <div class="col-span-2">
                    <p class="text-gray-400">Path</p>
                    <p id="disk-path" class="font-semibold text-gray-700 truncate">—</p>
                </div>
            </div>
        </div>

        {{-- Network --}}
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5">
            <div class="flex items-center gap-2 mb-4">
                <div class="w-8 h-8 rounded-lg bg-orange-50 flex items-center justify-center">
                    <svg class="w-4 h-4 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path d="M5 12.55a11 11 0 0 1 14.08 0" stroke-width="2" stroke-linecap="round"/>
                        <path d="M1.42 9a16 16 0 0 1 21.16 0" stroke-width="2" stroke-linecap="round"/>
                        <path d="M8.53 16.11a6 6 0 0 1 6.95 0" stroke-width="2" stroke-linecap="round"/>
                        <circle cx="12" cy="20" r="1" fill="currentColor"/>
                    </svg>
                </div>
                <span class="text-sm font-semibold text-gray-600">Network</span>
            </div>

            <p class="text-xs text-gray-400 mb-3">Interface: <span id="net-iface" class="font-semibold text-gray-700">—</span></p>

            <div class="space-y-2">
                <div class="flex items-center justify-between bg-gray-50 border border-gray-100 rounded-lg px-3 py-2.5">
                    <div class="flex items-center gap-2 text-xs text-gray-500">
                        <svg class="w-3.5 h-3.5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
                        </svg>
                        RX (Total Diterima)
                    </div>
                    <span id="net-rx" class="text-sm font-bold text-gray-800">—</span>
                </div>
                <div class="flex items-center justify-between bg-gray-50 border border-gray-100 rounded-lg px-3 py-2.5">
                    <div class="flex items-center gap-2 text-xs text-gray-500">
                        <svg class="w-3.5 h-3.5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 10l7-7m0 0l7 7m-7-7v18"/>
                        </svg>
                        TX (Total Terkirim)
                    </div>
                    <span id="net-tx" class="text-sm font-bold text-gray-800">—</span>
                </div>
            </div>
        </div>

        {{-- GPU --}}
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5">
            <div class="flex items-center gap-2 mb-4">
                <div class="w-8 h-8 rounded-lg bg-yellow-50 flex items-center justify-center">
                    <svg class="w-4 h-4 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <rect x="2" y="6" width="20" height="12" rx="2" stroke-width="2"/>
                        <path d="M6 10h.01M10 10h.01M14 10h.01M18 10h.01" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                </div>
                <span class="text-sm font-semibold text-gray-600">GPU</span>
            </div>
            <div id="gpu-content">
                <div class="flex flex-col gap-2 animate-pulse">
                    <div class="h-8 bg-gray-100 rounded w-1/2"></div>
                    <div class="h-2 bg-gray-100 rounded w-full"></div>
                    <div class="h-4 bg-gray-100 rounded w-2/3"></div>
                </div>
            </div>
        </div>

        {{-- Uptime --}}
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5">
            <div class="flex items-center gap-2 mb-4">
                <div class="w-8 h-8 rounded-lg bg-purple-50 flex items-center justify-center">
                    <svg class="w-4 h-4 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="10" stroke-width="2"/>
                        <polyline points="12 6 12 12 16 14" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
                <span class="text-sm font-semibold text-gray-600">Uptime Server</span>
            </div>

            <div class="grid grid-cols-3 gap-3">
                <div class="text-center bg-gray-50 border border-gray-100 rounded-lg py-3">
                    <p id="up-days" class="text-2xl font-bold text-gray-800">—</p>
                    <p class="text-xs text-gray-400 mt-0.5">Hari</p>
                </div>
                <div class="text-center bg-gray-50 border border-gray-100 rounded-lg py-3">
                    <p id="up-hours" class="text-2xl font-bold text-gray-800">—</p>
                    <p class="text-xs text-gray-400 mt-0.5">Jam</p>
                </div>
                <div class="text-center bg-gray-50 border border-gray-100 rounded-lg py-3">
                    <p id="up-mins" class="text-2xl font-bold text-gray-800">—</p>
                    <p class="text-xs text-gray-400 mt-0.5">Menit</p>
                </div>
            </div>
        </div>

    </div>
</div>

@endsection

@push('scripts')
<script>
(function () {
    'use strict';

    const INTERVAL = 60000;
    const ENDPOINT = '{{ route("admin.monitoring.metrics") }}';

    let countdown = 5;
    let timer     = null;
    let cdTimer   = null;

    function setText(id, val) {
        const el = document.getElementById(id);
        if (el) el.textContent = val;
    }

    function setBar(barId, pct) {
        const el = document.getElementById(barId);
        if (!el) return;
        el.style.width = Math.min(pct, 100) + '%';
        el.classList.remove('bg-green-500','bg-yellow-500','bg-red-500','bg-blue-500','bg-indigo-500');
        if (pct >= 80)      el.classList.add('bg-red-500');
        else if (pct >= 60) el.classList.add('bg-yellow-500');
        else                el.classList.add('bg-green-500');
    }

    function setBadge(id, pct) {
        const el = document.getElementById(id);
        if (!el) return;
        el.textContent = pct + '%';
        el.classList.remove('bg-gray-100','text-gray-500','bg-green-100','text-green-600','bg-yellow-100','text-yellow-600','bg-red-100','text-red-600');
        if (pct >= 80)      el.classList.add('bg-red-100','text-red-600');
        else if (pct >= 60) el.classList.add('bg-yellow-100','text-yellow-600');
        else                el.classList.add('bg-green-100','text-green-600');
    }

    function renderCpu(d) {
        setText('cpu-val',    d.usage);
        setBadge('cpu-badge', d.usage);
        setBar('cpu-bar',     d.usage);
        setText('cpu-cores',  d.cores);
        setText('cpu-load1',  d.load[0] ?? '—');
        setText('cpu-load5',  d.load[1] ?? '—');
        setText('cpu-load15', d.load[2] ?? '—');
    }

    function renderMemory(d) {
        setText('mem-val',    d.used_hr + ' / ' + d.total_hr);
        setBadge('mem-badge', d.percent);
        setBar('mem-bar',     d.percent);
        setText('mem-total',  d.total_hr);
        setText('mem-used',   d.used_hr + ' (' + d.percent + '%)');
        setText('mem-free',   d.free_hr);
        setText('mem-cached', d.cached > 0 ? (d.cached_hr || '—') : '—');
    }

    function renderDisk(d) {
        setText('disk-val',    d.used_hr + ' / ' + d.total_hr);
        setBadge('disk-badge', d.percent);
        setBar('disk-bar',     d.percent);
        setText('disk-total',  d.total_hr);
        setText('disk-used',   d.used_hr + ' (' + d.percent + '%)');
        setText('disk-free',   d.free_hr);
        setText('disk-path',   d.path);
    }

    function renderNetwork(d) {
        setText('net-iface', d.interface);
        setText('net-rx',    d.rx_hr);
        setText('net-tx',    d.tx_hr);
    }

    function renderGpu(d) {
        const cont = document.getElementById('gpu-content');
        if (!d.available) {
            cont.innerHTML = `
                <div class="flex flex-col items-center justify-center py-4 text-center">
                    <svg class="w-8 h-8 text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p class="text-sm text-gray-400">GPU tidak terdeteksi</p>
                    <p class="text-xs text-gray-300 mt-1">nvidia-smi tidak ditemukan</p>
                </div>`;
            return;
        }

        const pct      = d.usage;
        const badgeCls = pct >= 80 ? 'bg-red-100 text-red-600' : pct >= 60 ? 'bg-yellow-100 text-yellow-600' : 'bg-green-100 text-green-600';
        const barCls   = pct >= 80 ? 'bg-red-500' : pct >= 60 ? 'bg-yellow-500' : 'bg-green-500';
        const tempCls  = d.temp > 80 ? 'text-red-600' : d.temp > 60 ? 'text-yellow-600' : 'text-green-600';

        cont.innerHTML = `
            <div class="flex items-center justify-between mb-2">
                <span class="text-3xl font-bold text-gray-800">${pct}<span class="text-base font-normal text-gray-400">%</span></span>
                <span class="text-xs font-semibold px-2 py-0.5 rounded-full ${badgeCls}">${pct}%</span>
            </div>
            <div class="w-full bg-gray-100 rounded-full h-2 mb-4">
                <div class="h-2 rounded-full ${barCls} transition-all duration-500" style="width:${pct}%"></div>
            </div>
            <div class="grid grid-cols-2 gap-y-2 gap-x-4 text-xs">
                <div class="col-span-2">
                    <p class="text-gray-400">GPU</p>
                    <p class="font-semibold text-gray-700 truncate">${d.name}</p>
                </div>
                <div>
                    <p class="text-gray-400">Suhu</p>
                    <p class="font-semibold ${tempCls}">${d.temp}°C</p>
                </div>
                <div>
                    <p class="text-gray-400">VRAM</p>
                    <p class="font-semibold text-gray-700">${d.mem_used_hr} / ${d.mem_total_hr}</p>
                </div>
            </div>`;
    }

    function renderUptime(d) {
        setText('up-days',  String(d.days).padStart(2, '0'));
        setText('up-hours', String(d.hours).padStart(2, '0'));
        setText('up-mins',  String(d.minutes).padStart(2, '0'));
    }

    function fetchMetrics() {
        fetch(ENDPOINT, {
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
        })
        .then(r => r.json())
        .then(data => {
            renderCpu(data.cpu);
            renderMemory(data.memory);
            renderDisk(data.disk);
            renderNetwork(data.network);
            renderGpu(data.gpu);
            renderUptime(data.uptime);

            const now = new Date();
            document.getElementById('last-updated').textContent =
                'Update: ' + String(now.getHours()).padStart(2,'0') + ':' +
                String(now.getMinutes()).padStart(2,'0') + ':' +
                String(now.getSeconds()).padStart(2,'0');
        })
        .catch(() => {
            document.getElementById('last-updated').textContent = 'Gagal memuat data';
        });
    }

    function startCountdown() {
        clearInterval(cdTimer);
        countdown = INTERVAL / 1000;
        setText('countdown', countdown);
        cdTimer = setInterval(() => {
            countdown = countdown <= 1 ? INTERVAL / 1000 : countdown - 1;
            setText('countdown', countdown);
        }, 1000);
    }

    fetchMetrics();
    startCountdown();

    timer = setInterval(() => {
        fetchMetrics();
        startCountdown();
    }, INTERVAL);

    // Hentikan polling saat tab disembunyikan — hemat server
    document.addEventListener('visibilitychange', () => {
        if (document.hidden) {
            clearInterval(timer);
            clearInterval(cdTimer);
        } else {
            fetchMetrics();
            startCountdown();
            timer = setInterval(() => {
                fetchMetrics();
                startCountdown();
            }, INTERVAL);
        }
    });

})();
</script>
@endpush