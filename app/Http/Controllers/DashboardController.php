<?php
// app/Http/Controllers/DashboardController.php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->role === 'admin') {
            $stats = [
                'total_pendaftar'    => Mahasiswa::count(),
                'pendaftar_hari_ini' => Mahasiswa::whereDate('created_at', today())->count(),
                'total_user'         => User::count(),
                'belum_mengisi'      => User::where('role', 'mahasiswa')
                                            ->doesntHave('mahasiswa')
                                            ->count(),
                'laki_laki'          => Mahasiswa::where('jenis_kelamin', 'Laki-laki')->count(),
                'perempuan'          => Mahasiswa::where('jenis_kelamin', 'Perempuan')->count(),
                'kewarganegaraan'    => Mahasiswa::selectRaw('kewarganegaraan, count(*) as total')
                                            ->groupBy('kewarganegaraan')
                                            ->get(),
            ];

            $pendaftarTerbaru = Mahasiswa::with('province')
                                    ->latest()
                                    ->limit(7)
                                    ->get();

            return view('dashboard', compact('stats', 'pendaftarTerbaru'));
        }

        // Mahasiswa 
        return view('dashboard');
    }
}