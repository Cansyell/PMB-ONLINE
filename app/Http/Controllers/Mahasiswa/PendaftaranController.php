<?php
// app/Http/Controllers/Mahasiswa/PendaftaranController.php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Agama;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravolt\Indonesia\Models\Province;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage; 
 

class PendaftaranController extends Controller
{
   
    public function index()
    {
        $pendaftaran = Mahasiswa::with(['agama',
                                        'province', 'city', 'district', 'village',
                                        'provinceLahir', 'cityLahir',
                                        'provinceSekarang', 'citySekarang', 'districtSekarang', 'villageSekarang',])
                            ->where('user_id', Auth::id())
                            ->first();

        $agamas    = Agama::orderBy('nama')->get();
        $provinces = Province::orderBy('name')->get();

        return view('mahasiswa.pendaftaran.index', compact('pendaftaran', 'agamas', 'provinces'));
    }

    public function create()
    {
        if (Mahasiswa::where('user_id', Auth::id())->exists()) {
            return redirect()->route('mahasiswa.pendaftaran.edit')
                             ->with('info', 'Anda sudah mendaftar. Silakan edit data.');
        }

        $agamas    = Agama::orderBy('nama')->get();
        $provinces = Province::orderBy('name')->get();

        return view('mahasiswa.pendaftaran.create', compact('agamas', 'provinces'));
    }

    public function store(Request $request)
    {
        $validated = $this->validateForm($request);
        $validated['user_id']     = Auth::id();
        $validated['same_as_ktp'] = $request->boolean('same_as_ktp');

        if ($validated['same_as_ktp']) {
            $validated['alamat_sekarang']        = $validated['alamat_ktp'];
            $validated['province_code_sekarang'] = $validated['province_code'];
            $validated['city_code_sekarang']     = $validated['city_code'];
            $validated['district_code_sekarang'] = $validated['district_code'];
            $validated['village_code_sekarang']  = $validated['village_code'];
        }

        // Upload foto
        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('mahasiswa/foto', 'public');
        }

        // Upload video
        if ($request->hasFile('video_perkenalan')) {
            $validated['video_perkenalan'] = $request->file('video_perkenalan')
                                                    ->store('mahasiswa/video', 'public');
        }

        $validated['lahir_luar_negeri'] = $request->boolean('lahir_luar_negeri');

        // Jika lahir luar negeri, kosongkan kolom wilayah lahir
        if ($validated['lahir_luar_negeri']) {
            $validated['province_code_lahir'] = null;
            $validated['city_code_lahir']     = null;
        } else {
            $validated['negara_lahir'] = null;
        }
        Mahasiswa::create($validated);

        return redirect()->route('mahasiswa.pendaftaran.index')
                         ->with('success', 'Pendaftaran berhasil disimpan!');
    }

    public function edit()
    {
        $pendaftaran = Mahasiswa::where('user_id', Auth::id())->firstOrFail();
        $agamas      = Agama::orderBy('nama')->get();
        $provinces   = Province::orderBy('name')->get();
        // dd($pendaftaran);
        return view('mahasiswa.pendaftaran.edit', compact('pendaftaran', 'agamas', 'provinces'));
    }

    public function update(Request $request)
    {
        $pendaftaran = Mahasiswa::where('user_id', Auth::id())->firstOrFail();
        $validated   = $this->validateForm($request, $pendaftaran->id);
        $validated['same_as_ktp'] = $request->boolean('same_as_ktp');
        
        if ($validated['same_as_ktp']) {
            $validated['alamat_sekarang']        = $validated['alamat_ktp'];
            $validated['province_code_sekarang'] = $validated['province_code'];
            $validated['city_code_sekarang']     = $validated['city_code'];
            $validated['district_code_sekarang'] = $validated['district_code'];
            $validated['village_code_sekarang']  = $validated['village_code'];
        }

        // Upload foto baru — hapus lama jika ada
        if ($request->hasFile('foto')) {
            if ($pendaftaran->foto) Storage::disk('public')->delete($pendaftaran->foto);
            $validated['foto'] = $request->file('foto')->store('mahasiswa/foto', 'public');
        }

        // Upload video baru — hapus lama jika ada
        if ($request->hasFile('video_perkenalan')) {
            if ($pendaftaran->video_perkenalan) Storage::disk('public')->delete($pendaftaran->video_perkenalan);
            $validated['video_perkenalan'] = $request->file('video_perkenalan')
                                                    ->store('mahasiswa/video', 'public');
        }
        $validated['lahir_luar_negeri'] = $request->boolean('lahir_luar_negeri');

        // Jika lahir luar negeri, kosongkan kolom wilayah lahir
        if ($validated['lahir_luar_negeri']) {
            $validated['province_code_lahir'] = null;
            $validated['city_code_lahir']     = null;
        } else {
            $validated['negara_lahir'] = null;
        }
        $pendaftaran->update($validated);

        return redirect()->route('mahasiswa.pendaftaran.index')
                         ->with('success', 'Data pendaftaran berhasil diperbarui!');
    }

    private function validateForm(Request $request, $ignoreId = null)
        {
            $emailRule = 'required|email|unique:mahasiswas,email';
            if ($ignoreId) $emailRule .= ',' . $ignoreId;

            $luarNegeri = $request->boolean('lahir_luar_negeri');

            return $request->validate([
                'nama_lengkap'           => 'required|string|max:255',
                'jenis_kelamin'          => 'required|in:Laki-laki,Perempuan',
                'status_menikah'         => 'required|in:Belum Menikah,Menikah,Cerai',
                'kewarganegaraan'        => 'required|in:WNI,WNA',
                'agama_id'               => 'required|exists:agamas,id',
                'tempat_lahir'           => 'required|string|max:100',
                'tanggal_lahir'          => 'required|date|before:today',
                'lahir_luar_negeri'      => 'boolean',

                // Kondisional berdasarkan lahir_luar_negeri
                'negara_lahir'           => $luarNegeri ? 'required|string|max:100' : 'nullable',
                'province_code_lahir'    => $luarNegeri ? 'nullable' : 'required|exists:indonesia_provinces,code',
                'city_code_lahir'        => $luarNegeri ? 'nullable' : 'required|exists:indonesia_cities,code',

                'alamat_ktp'             => 'required|string',
                'province_code'          => 'required|exists:indonesia_provinces,code',
                'city_code'              => 'required|exists:indonesia_cities,code',
                'district_code'          => 'required|exists:indonesia_districts,code',
                'village_code'           => 'required|exists:indonesia_villages,code',
                'same_as_ktp'            => 'boolean',
                'alamat_sekarang'        => 'required_if:same_as_ktp,false|nullable|string',
                'province_code_sekarang' => 'required_if:same_as_ktp,false|nullable|exists:indonesia_provinces,code',
                'city_code_sekarang'     => 'required_if:same_as_ktp,false|nullable|exists:indonesia_cities,code',
                'district_code_sekarang' => 'required_if:same_as_ktp,false|nullable|exists:indonesia_districts,code',
                'village_code_sekarang'  => 'required_if:same_as_ktp,false|nullable|exists:indonesia_villages,code',
                'email'                  => $emailRule,
                'no_telepon'             => 'nullable|digits_between:5,15',
                'no_hp'                  => 'required|digits_between:9,15',
                'foto'                   => $ignoreId ? 'nullable|image|mimes:jpeg,png|max:2048'
                                        : 'required|image|mimes:jpeg,png|max:2048',
                'video_perkenalan'       => $ignoreId ? 'nullable|file|mimetypes:video/mp4,video/mpeg,application/mp4|max:51200'
                                        : 'required|file|mimetypes:video/mp4,video/mpeg,application/mp4|max:51200',
            ], [
                'negara_lahir.required'              => 'Negara lahir wajib diisi.',
                'province_code_lahir.required'       => 'Provinsi lahir wajib dipilih.',
                'city_code_lahir.required'           => 'Kota/Kabupaten lahir wajib dipilih.',
                'email.email'                        => 'Isian tidak sesuai format email.',
                'no_hp.digits_between'               => 'Isian harus format angka (9–15 digit).',
                'no_telepon.digits_between'          => 'Isian harus format angka.',
                'alamat_sekarang.required_if'        => 'Alamat saat ini wajib diisi.',
                'province_code_sekarang.required_if' => 'Provinsi saat ini wajib dipilih.',
                'city_code_sekarang.required_if'     => 'Kota saat ini wajib dipilih.',
                'district_code_sekarang.required_if' => 'Kecamatan saat ini wajib dipilih.',
                'village_code_sekarang.required_if'  => 'Kelurahan saat ini wajib dipilih.',
                'foto.required'                      => 'Foto wajib diupload.',
                'foto.image'                         => 'File harus berupa gambar.',
                'foto.mimes'                         => 'Format foto harus JPEG, atau PNG.',
                'foto.max'                           => 'Ukuran foto maksimal 2 MB.',
                'video_perkenalan.required'          => 'Video perkenalan wajib diupload.',
                'video_perkenalan.mimes'             => 'Format video harus MP4.',
                'video_perkenalan.max'               => 'Ukuran video maksimal 50 MB.',
            ]);
        }

    public function cetakPdf()
        {
            // Ambil data mahasiswa milik user yang login
            $pendaftaran = Mahasiswa::with([
                'agama',
                'province', 'city', 'district', 'village',
                'provinceSekarang', 'citySekarang', 'districtSekarang', 'villageSekarang',
                'provinceLahir', 'cityLahir',
            ])->where('user_id', auth()->id())->firstOrFail();

            // Konversi foto ke base64
            $fotoBase64 = null;
            if ($pendaftaran->foto && Storage::disk('public')->exists($pendaftaran->foto)) {
                $fotoPath   = Storage::disk('public')->path($pendaftaran->foto);
                $fotoMime   = mime_content_type($fotoPath);
                $fotoBase64 = 'data:' . $fotoMime . ';base64,' . base64_encode(file_get_contents($fotoPath));
            }

            $pdf = Pdf::loadView('mahasiswa.pendaftaran.cetak-pdf', compact('pendaftaran', 'fotoBase64'))
                    ->setPaper('a4', 'portrait');

            $namaFile = 'bukti-pendaftaran-' . str()->slug($pendaftaran->nama_lengkap) . '.pdf';

            return $pdf->stream($namaFile);
        }
}