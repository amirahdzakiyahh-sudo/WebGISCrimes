<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kriminalitas;
use App\Models\Kecamatan;
use Illuminate\Http\Request;

class KriminalitasController extends Controller
{
    public function index()
    {
        $data = Kriminalitas::with('kecamatan')->orderBy('tahun','desc')->paginate(10);
        return view('admin.kriminalitas.index', compact('data'));
    }

    public function create()
    {
        $kecamatans = Kecamatan::orderBy('nama_kecamatan')->get();
        return view('admin.kriminalitas.create', compact('kecamatans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kecamatan_id'      => 'required|exists:kecamatans,id',
            'tahun'             => 'required|integer|min:2000|max:2099',
            'jenis_kriminalitas'=> 'required|string|max:255',
            'jumlah_kasus'      => 'required|integer|min:0',
            'tingkat_kerawanan' => 'nullable|in:rendah,sedang,tinggi',
            'latitude'  => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'alamat_detail' => 'nullable|string|max:255',
            'rt'            => 'nullable|string|max:5',
            'rw'            => 'nullable|string|max:5',
            'kelurahan'     => 'nullable|string|max:255',
        ]);

        Kriminalitas::create($request->all());

        return redirect()->route('admin.kriminalitas.index')
            ->with('success', 'Data kriminalitas berhasil ditambahkan!');
    }

    public function edit(Kriminalitas $kriminalita)
    {
        $kecamatans = Kecamatan::orderBy('nama_kecamatan')->get();
        return view('admin.kriminalitas.edit', compact('kriminalita', 'kecamatans'));
    }

    public function update(Request $request, Kriminalitas $kriminalita)
    {
        $request->validate([
            'kecamatan_id'      => 'required|exists:kecamatans,id',
            'tahun'             => 'required|integer|min:2000|max:2099',
            'jenis_kriminalitas'=> 'required|string|max:255',
            'jumlah_kasus'      => 'required|integer|min:0',
            'tingkat_kerawanan' => 'nullable|in:rendah,sedang,tinggi',
            'alamat_detail' => 'nullable|string|max:255',
            'rt'            => 'nullable|string|max:5',
            'rw'            => 'nullable|string|max:5',
            'kelurahan'     => 'nullable|string|max:255',
        ]);

        $kriminalita->update($request->all());

        return redirect()->route('admin.kriminalitas.index')
            ->with('success', 'Data kriminalitas berhasil diupdate!');
    }

    public function destroy(Kriminalitas $kriminalita)
    {
        $kriminalita->delete();
        return redirect()->route('admin.kriminalitas.index')
            ->with('success', 'Data kriminalitas berhasil dihapus!');
    }

    public function bulkDestroy(Request $request)
    {
        $ids = $request->input('ids');

        if ($ids && is_array($ids)) {
            Kriminalitas::whereIn('id', $ids)->delete();
            return redirect()->route('admin.kriminalitas.index')
                ->with('success', 'Data kriminalitas terpilih berhasil dihapus!');
        }

        return redirect()->route('admin.kriminalitas.index')
            ->with('error', 'Tidak ada data yang dipilih untuk dihapus.');
    }
}