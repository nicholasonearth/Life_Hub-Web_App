<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi untuk menambah kolom Level dan EXP.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Menambah kolom level (default mulai dari level 1)
            $table->integer('level')->default(1)->after('email'); 
            
            // Menambah kolom experience (XP)
            $table->integer('experience')->default(0)->after('level');
        });
    }

    /**
     * Batalkan migrasi (Hapus kolom jika di-rollback).
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['level', 'experience']);
        });
    }
};