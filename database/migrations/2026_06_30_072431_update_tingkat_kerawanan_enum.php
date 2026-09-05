<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Ubah kolom jadi VARCHAR dulu agar bebas diisi apapun
        DB::statement("ALTER TABLE kriminalitas MODIFY COLUMN tingkat_kerawanan VARCHAR(20) NOT NULL");

        // 2. Update semua data lama ke 'sedang'
        DB::table('kriminalitas')->update(['tingkat_kerawanan' => 'sedang']);

        // 3. Baru ubah jadi ENUM baru
        DB::statement("ALTER TABLE kriminalitas MODIFY COLUMN tingkat_kerawanan ENUM('rendah', 'sedang', 'tinggi') NOT NULL");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE kriminalitas MODIFY COLUMN tingkat_kerawanan ENUM('aman', 'waspada', 'rawan', 'sangat_rawan') NOT NULL");
    }
};