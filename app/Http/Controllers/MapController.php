<?php

namespace App\Http\Controllers;

use App\Models\Kecamatan;
use App\Models\Kriminalitas;
use Illuminate\Http\Request;

class MapController extends Controller
{
    /**
     * Pastikan controller ini bebas dari proteksi auth manapun.
     */
    public function __construct()
    {
        // Kosongkan agar controller ini murni publik
    }

    public function index()
    {
        $tahunList = Kriminalitas::select('tahun')->distinct()->orderBy('tahun', 'desc')->get();
        $jenisList = Kriminalitas::select('jenis_kriminalitas')->distinct()->get();
        $kecamatans = Kecamatan::whereNotNull('geojson')->get();
        return view('map', compact('tahunList', 'jenisList', 'kecamatans'));
    }

    public function mapData(Request $request)
    {
        $query = Kecamatan::with(['kriminalitas' => function($q) use ($request) {
            if ($request->tahun) $q->where('tahun', $request->tahun);
            if ($request->jenis) $q->where('jenis_kriminalitas', $request->jenis);
        }])->get();

        $polygonFeatures = [];
        $markerFeatures = [];

        foreach ($query as $kecamatan) {
            if (!$kecamatan->geojson) continue;
            $geo = json_decode($kecamatan->geojson, true);

            // Kalau yang tersimpan berupa Feature lengkap, ambil geometry-nya saja
            if (isset($geo['type']) && $geo['type'] === 'Feature' && isset($geo['geometry'])) {
                $geo = $geo['geometry'];
            }

            // MENGAMBIL DATA MANUAL DARI KOLOM DATABASE KECAMATAN
            $totalKeseluruhan = $kecamatan->total_keseluruhan;

            // Selalu tambahkan polygon kecamatan (walau tidak ada data kriminalitas)
            $polygonFeatures[] = [
                'type' => 'Feature',
                'geometry' => $geo,
                'properties' => [
                    'kecamatan'         => $kecamatan->nama_kecamatan,
                    'total_keseluruhan' => $totalKeseluruhan, 
                    'total_2023'        => $kecamatan->total_2023, // Ditambahkan
                    'total_2024'        => $kecamatan->total_2024, // Ditambahkan
                    'total_2025'        => $kecamatan->total_2025, // Ditambahkan
                ]
            ];

            // Tambahkan marker titik kejadian untuk tiap data kriminalitas
            foreach ($kecamatan->kriminalitas as $k) {
                if (!$k->latitude || !$k->longitude) continue;

                $markerFeatures[] = [
                    'type' => 'Feature',
                    'geometry' => [
                        'type' => 'Point',
                        'coordinates' => [(float) $k->longitude, (float) $k->latitude],
                    ],
                    'properties' => [
                        'kecamatan'         => $kecamatan->nama_kecamatan,
                        'tahun'             => $k->tahun,
                        'jenis'             => $k->jenis_kriminalitas,
                        'jumlah_kasus'      => $k->jumlah_kasus,
                        'tingkat_kerawanan' => $k->tingkat_kerawanan,
                        'latitude'          => $k->latitude,
                        'longitude'         => $k->longitude,
                    ]
                ];
            }
        }

        return response()->json([
            'polygons' => [
                'type'     => 'FeatureCollection',
                'features' => $polygonFeatures,
            ],
            'markers' => [
                'type'     => 'FeatureCollection',
                'features' => $markerFeatures,
            ],
        ]);
    }
}