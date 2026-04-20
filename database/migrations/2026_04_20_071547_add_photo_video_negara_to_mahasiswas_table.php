<?php
// database/migrations/xxxx_add_photo_video_negara_to_mahasiswas_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('mahasiswas', function (Blueprint $table) {
            $table->boolean('lahir_luar_negeri')->default(false)->after('city_code_lahir');
            $table->string('negara_lahir', 100)->nullable()->after('lahir_luar_negeri');
            $table->string('foto')->nullable()->after('no_hp');
            $table->string('video_perkenalan')->nullable()->after('foto');
        });
    }

    public function down(): void
    {
        Schema::table('mahasiswas', function (Blueprint $table) {
            $table->dropColumn(['lahir_luar_negeri', 'negara_lahir', 'foto', 'video_perkenalan']);
        });
    }
};