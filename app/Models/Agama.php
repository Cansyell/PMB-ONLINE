<?php
// app/Models/Agama.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Agama extends Model
{
    protected $fillable = ['nama'];

    public function pendaftarans()
    {
        return $this->hasMany(PendaftaranMahasiswa::class);
    }
}