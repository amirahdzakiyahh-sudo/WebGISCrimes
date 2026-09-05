<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kriminalitas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kecamatan_id')->constrained('kecamatans')->onDelete('cascade');
            $table->integer('tahun');
            $table->string('jenis_kriminalitas');
            $table->integer('jumlah_kasus');
            $table->text('faktor')->nullable();
            $table->enum('tingkat_kerawanan', ['aman', 'waspada', 'rawan', 'sangat_rawan']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kriminalitas');
    }
};