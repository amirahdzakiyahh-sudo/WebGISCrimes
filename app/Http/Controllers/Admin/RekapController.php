<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kecamatan;
use Illuminate\Http\Request;

class RekapController extends Controller
{
    public function index()
    {
        // Mengambil data kecamatan beserta data relasi kriminalitas
        $kecamatan = Kecamatan::with('kriminalitas')->get();

        return view('admin.rekap.index', compact('kecamatan'));
    }
}