<?php
// app/Models/PendaftaranMahasiswa.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Mahasiswa extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'nama_lengkap',
        'jenis_kelamin',
        'status_menikah',
        'kewarganegaraan',
        'agama_id',
        'tempat_lahir',
        'tanggal_lahir',
        'lahir_luar_negeri',  
        'negara_lahir',       
        'province_code_lahir',
        'city_code_lahir',
        'alamat_ktp',
        'province_code',
        'city_code',
        'district_code',
        'village_code',
        'alamat_sekarang',
        'same_as_ktp',
        'province_code_sekarang',
        'city_code_sekarang',
        'district_code_sekarang',
        'village_code_sekarang',
        'email',
        'no_telepon',
        'no_hp',
        'foto',               
        'video_perkenalan',   
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'same_as_ktp'   => 'boolean',
        'lahir_luar_negeri' => 'boolean',
    ];

    // Relasi

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function agama()
    {
        return $this->belongsTo(Agama::class);
    }

    // Wilayah KTP
    public function province()
    {
        return $this->belongsTo(\Laravolt\Indonesia\Models\Province::class, 'province_code', 'code');
    }

    public function city()
    {
        return $this->belongsTo(\Laravolt\Indonesia\Models\City::class, 'city_code', 'code');
    }

    public function district()
    {
        return $this->belongsTo(\Laravolt\Indonesia\Models\District::class, 'district_code', 'code');
    }

    public function village()
    {
        return $this->belongsTo(\Laravolt\Indonesia\Models\Village::class, 'village_code', 'code');
    }

    // Wilayah Saat Ini
    public function provinceSekarang()
    {
        return $this->belongsTo(\Laravolt\Indonesia\Models\Province::class, 'province_code_sekarang', 'code');
    }

    public function citySekarang()
    {
        return $this->belongsTo(\Laravolt\Indonesia\Models\City::class, 'city_code_sekarang', 'code');
    }

    public function districtSekarang()
    {
        return $this->belongsTo(\Laravolt\Indonesia\Models\District::class, 'district_code_sekarang', 'code');
    }

    public function villageSekarang()
    {
        return $this->belongsTo(\Laravolt\Indonesia\Models\Village::class, 'village_code_sekarang', 'code');
    }

    // Wilayah Lahir
    public function provinceLahir()
    {
        return $this->belongsTo(\Laravolt\Indonesia\Models\Province::class, 'province_code_lahir', 'code');
    }

    public function cityLahir()
    {
        return $this->belongsTo(\Laravolt\Indonesia\Models\City::class, 'city_code_lahir', 'code');
    }
}