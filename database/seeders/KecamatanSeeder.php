<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use App\Models\Kecamatan;

class KecamatanSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Tentukan lokasi path file geojson di dalam folder storage
        $path = storage_path('app/wilayah_kota_prabumulih.geojson');

        if (!File::exists($path)) {
            $this->command->error("File GeoJSON tidak ditemukan di: {$path}");
            return;
        }

        // 2. Membaca isi file geojson
        $json = File::get($path);
        $data = json_decode($json, true);

        if (!isset($data['features'])) {
            $this->command->error("Struktur GeoJSON tidak valid.");
            return;
        }

        // 3. Looping data fitur wilayah untuk dimasukkan ke database satu per satu (hemat RAM)
        foreach ($data['features'] as $feature) {
            $props = $feature['properties'];
            $geom = $feature['geometry'];

            // Mengambil nama kecamatan dan kode kecamatan dari properti GeoJSON asli
            $namaKecamatan = $props['WADMKC'] ?? $props['NAMOBJ'] ?? null;
            $kodeKecamatan = $props['KDCPUM'] ?? null;

            if ($namaKecamatan && $kodeKecamatan) {
                Kecamatan::updateOrCreate(
                    ['kode_kecamatan' => $kodeKecamatan],
                    [
                        'nama_kecamatan' => $namaKecamatan,
                        'geojson' => json_encode($geom) // Menyimpan hanya object struktur geometrinya saja
                    ]
                );
            }
        }

        $this->command->info("Seeding wilayah Kota Prabumulih berhasil diselesaikan!");
    }
}