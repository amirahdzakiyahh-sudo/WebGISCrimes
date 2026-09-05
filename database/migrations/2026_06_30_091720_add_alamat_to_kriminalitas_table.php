<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kriminalitas', function (Blueprint $table) {
            $table->string('alamat_detail')->nullable()->after('kecamatan_id');
            $table->string('rt', 5)->nullable()->after('alamat_detail');
            $table->string('rw', 5)->nullable()->after('rt');
            $table->string('kelurahan')->nullable()->after('rw');
        });
    }

    public function down(): void
    {
        Schema::table('kriminalitas', function (Blueprint $table) {
            $table->dropColumn(['alamat_detail', 'rt', 'rw', 'kelurahan']);
        });
    }
};