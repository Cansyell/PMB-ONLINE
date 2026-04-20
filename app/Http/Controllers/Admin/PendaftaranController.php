<?php
// app/Http/Controllers/Admin/PendaftaranController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa;
use App\Models\Agama;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Laravolt\Indonesia\Models\Province;

class PendaftaranController extends Controller
{
    public function index()
    {
        $pendaftarans = Mahasiswa::with(['user', 'agama', 'province', 'city'])
                            ->latest()
                            ->paginate(15);

        return view('admin.pendaftaran.index', compact('pendaftarans'));
    }

    public function show($id)
    {
        $pendaftaran = Mahasiswa::with([
            'user', 'agama',
            'province', 'city', 'district', 'village',
            'provinceSekarang', 'citySekarang', 'districtSekarang', 'villageSekarang',
            'provinceLahir', 'cityLahir',
        ])->findOrFail($id);

        return view('admin.pendaftaran.show', compact('pendaftaran'));
    }

    public function edit($id)
    {
        $pendaftaran = Mahasiswa::with([
            'agama',
            'province', 'city', 'district', 'village',
            'provinceSekarang', 'citySekarang', 'districtSekarang', 'villageSekarang',
            'provinceLahir', 'cityLahir',
        ])->findOrFail($id);

        $agamas    = Agama::orderBy('nama')->get();
        $provinces = Province::orderBy('name')->get();

        return view('admin.pendaftaran.edit', compact('pendaftaran', 'agamas', 'provinces'));
    }

    public function update(Request $request, $id)
    {
        $pendaftaran = Mahasiswa::findOrFail($id);

        $isSameAsKtp    = $request->boolean('same_as_ktp');
        $isLuarNegeri   = $request->boolean('lahir_luar_negeri');

        $validated = $request->validate([
            // Data Pribadi
            'nama_lengkap'    => 'required|string|max:255',
            'jenis_kelamin'   => 'required|in:Laki-laki,Perempuan',
            'status_menikah'  => 'required|in:Belum Menikah,Menikah,Cerai',
            'kewarganegaraan' => 'required|in:WNI,WNA',
            'agama_id'        => 'required|exists:agamas,id',

            // Data Kelahiran
            'tempat_lahir'        => 'required|string|max:255',
            'tanggal_lahir'       => 'required|date',
            'lahir_luar_negeri'   => 'boolean',
            'negara_lahir'        => $isLuarNegeri ? 'required|string|max:255' : 'nullable|string|max:255',
            'province_code_lahir' => !$isLuarNegeri ? 'required|string' : 'nullable|string',
            'city_code_lahir'     => !$isLuarNegeri ? 'required|string' : 'nullable|string',

            // Alamat KTP
            'alamat_ktp'    => 'required|string|max:500',
            'province_code' => 'required|string',
            'city_code'     => 'required|string',
            'district_code' => 'required|string',
            'village_code'  => 'required|string',

            // Alamat Sekarang
            'same_as_ktp'            => 'boolean',
            'alamat_sekarang'        => !$isSameAsKtp ? 'required|string|max:500' : 'nullable|string|max:500',
            'province_code_sekarang' => !$isSameAsKtp ? 'required|string' : 'nullable|string',
            'city_code_sekarang'     => !$isSameAsKtp ? 'required|string' : 'nullable|string',
            'district_code_sekarang' => !$isSameAsKtp ? 'required|string' : 'nullable|string',
            'village_code_sekarang'  => !$isSameAsKtp ? 'required|string' : 'nullable|string',

            // Kontak
            'email'       => 'required|email|max:255',
            'no_hp'       => 'required|digits_between:9,15',
            'no_telepon'  => 'nullable|digits_between:7,15',

            // Media
            'foto'              => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'video_perkenalan'  => 'nullable|mimetypes:video/mp4|max:51200',
        ]);

        // Handle upload foto
        if ($request->hasFile('foto')) {
            if ($pendaftaran->foto) {
                Storage::disk('public')->delete($pendaftaran->foto);
            }
            $validated['foto'] = $request->file('foto')->store('foto', 'public');
        }

        // Handle upload video
        if ($request->hasFile('video_perkenalan')) {
            if ($pendaftaran->video_perkenalan) {
                Storage::disk('public')->delete($pendaftaran->video_perkenalan);
            }
            $validated['video_perkenalan'] = $request->file('video_perkenalan')
                                                ->store('video_perkenalan', 'public');
        }

        // Bersihkan data wilayah lahir yang tidak relevan
        if ($isLuarNegeri) {
            $validated['province_code_lahir'] = null;
            $validated['city_code_lahir']     = null;
        } else {
            $validated['negara_lahir'] = null;
        }

        // Bersihkan data alamat sekarang jika sama dengan KTP
        if ($isSameAsKtp) {
            $validated['alamat_sekarang']        = null;
            $validated['province_code_sekarang'] = null;
            $validated['city_code_sekarang']     = null;
            $validated['district_code_sekarang'] = null;
            $validated['village_code_sekarang']  = null;
        }

        $pendaftaran->update($validated);

        return redirect()->route('admin.pendaftaran.show', $id)
                         ->with('success', 'Data pendaftar berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $pendaftaran = Mahasiswa::findOrFail($id);

        if ($pendaftaran->foto) {
            Storage::disk('public')->delete($pendaftaran->foto);
        }
        if ($pendaftaran->video_perkenalan) {
            Storage::disk('public')->delete($pendaftaran->video_perkenalan);
        }

        $pendaftaran->delete();

        return redirect()->route('admin.pendaftaran.index')
                         ->with('success', 'Data pendaftaran berhasil dihapus.');
    }
}