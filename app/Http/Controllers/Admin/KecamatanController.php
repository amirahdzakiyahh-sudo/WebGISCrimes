<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kecamatan;
use Illuminate\Http\Request;

class KecamatanController extends Controller
{
    public function index()
    {
        $kecamatans = Kecamatan::paginate(10);
        return view('admin.kecamatan.index', compact('kecamatans'));
    }

    public function create()
    {
        return view('admin.kecamatan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kecamatan'    => 'required|string|max:255',
            'kode_kecamatan'    => 'nullable|string|max:50',
            'total_keseluruhan' => 'required|integer',
            'total_2023'        => 'required|integer',
            'total_2024'        => 'required|integer',
            'total_2025'        => 'required|integer',
            'geojson'           => 'nullable|file|mimes:json,geojson,txt',
        ]);

        $geojson = null;
        if ($request->hasFile('geojson')) {
            $geojson = file_get_contents($request->file('geojson')->getRealPath());
        }

        Kecamatan::create([
            'nama_kecamatan'    => $request->nama_kecamatan,
            'kode_kecamatan'    => $request->kode_kecamatan,
            'total_keseluruhan' => $request->total_keseluruhan,
            'total_2023'        => $request->total_2023,
            'total_2024'        => $request->total_2024,
            'total_2025'        => $request->total_2025,
            'geojson'           => $geojson,
        ]);

        return redirect()->route('admin.kecamatan.index')
            ->with('success', 'Kecamatan berhasil ditambahkan!');
    }

    public function edit(Kecamatan $kecamatan)
    {
        return view('admin.kecamatan.edit', compact('kecamatan'));
    }

    public function update(Request $request, Kecamatan $kecamatan)
    {
        $request->validate([
            'nama_kecamatan'    => 'required|string|max:255',
            'kode_kecamatan'    => 'nullable|string|max:50',
            'total_keseluruhan' => 'required|integer',
            'total_2023'        => 'required|integer',
            'total_2024'        => 'required|integer',
            'total_2025'        => 'required|integer',
            'geojson'           => 'nullable|file|mimes:json,geojson,txt',
        ]);

        $geojson = $kecamatan->geojson;
        if ($request->hasFile('geojson')) {
            $geojson = file_get_contents($request->file('geojson')->getRealPath());
        }

        $kecamatan->update([
            'nama_kecamatan'    => $request->nama_kecamatan,
            'kode_kecamatan'    => $request->kode_kecamatan,
            'total_keseluruhan' => $request->total_keseluruhan,
            'total_2023'        => $request->total_2023,
            'total_2024'        => $request->total_2024,
            'total_2025'        => $request->total_2025,
            'geojson'           => $geojson,
        ]);

        return redirect()->route('admin.kecamatan.index')
            ->with('success', 'Kecamatan berhasil diupdate!');
    }

    public function destroy(Kecamatan $kecamatan)
    {
        $kecamatan->delete();
        return redirect()->route('admin.kecamatan.index')
            ->with('success', 'Kecamatan berhasil dihapus!');
    }
}