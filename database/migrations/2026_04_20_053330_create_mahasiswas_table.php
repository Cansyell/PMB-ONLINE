<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mahasiswas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

            // ── Data Pribadi ──────────────────────────────────────────────
            $table->string('nama_lengkap');
            $table->enum('jenis_kelamin', ['Laki-laki', 'Perempuan']);
            $table->enum('status_menikah', ['Belum Menikah', 'Menikah', 'Cerai']);
            $table->enum('kewarganegaraan', ['WNI', 'WNA'])->default('WNI');
            $table->unsignedBigInteger('agama_id')->nullable();

            // ── Data Kelahiran ────────────────────────────────────────────
            $table->string('tempat_lahir', 100);
            $table->date('tanggal_lahir');
            $table->char('province_code_lahir', 2)->nullable();
            $table->char('city_code_lahir', 4)->nullable();

            // ── Alamat KTP ────────────────────────────────────────────────
            $table->text('alamat_ktp');
            $table->char('province_code', 2)->nullable();
            $table->char('city_code', 4)->nullable();
            $table->char('district_code', 7)->nullable();
            $table->char('village_code', 10)->nullable();

            // ── Alamat Saat Ini ───────────────────────────────────────────
            $table->text('alamat_sekarang')->nullable();
            $table->boolean('same_as_ktp')->default(false);
            $table->char('province_code_sekarang', 2)->nullable();
            $table->char('city_code_sekarang', 4)->nullable();
            $table->char('district_code_sekarang', 7)->nullable();
            $table->char('village_code_sekarang', 10)->nullable();

            // ── Kontak ────────────────────────────────────────────────────
            $table->string('email')->unique();
            $table->string('no_telepon', 20)->nullable();
            $table->string('no_hp', 20);

            $table->timestamps();
            $table->softDeletes();

            // ── Foreign Key (hanya ke tabel lokal) ───────────────────────
            $table->foreign('agama_id')->references('id')->on('agamas')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mahasiswas');
    }
};