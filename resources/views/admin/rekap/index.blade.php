@extends('layouts.admin')

@section('title', 'Rekap Wilayah')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0"><i class="fas fa-layer-group me-2 text-accent"></i>Rekap Wilayah</h4>
</div>

<div class="card border-0 shadow-sm app-card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table align-middle custom-table mb-0">
                <thead>
                    <tr>
                        <th style="width: 70px;">NO</th>
                        <th>NAMA KECAMATAN</th>
                        <th class="text-center">TAHUN DATA</th>
                        <th class="text-center">JUMLAH KASUS</th>
                        <th class="text-center" style="width: 150px;">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($kecamatan as $index => $item)
                        @php
                            $totalKasus = $item->kriminalitas->count();
                            $tahunList = $item->kriminalitas->pluck('tahun')->unique()->sort()->implode(', ');
                        @endphp
                        <tr>
                            <td class="text-muted fw-semibold">{{ $index + 1 }}</td>
                            <td class="fw-bold text-dark">{{ $item->nama_kecamatan }}</td>
                            <td class="text-center">
                                @if($tahunList)
                                    <span class="badge badge-tahun">{{ $tahunList }}</span>
                                @else
                                    <span class="text-muted small">-</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <span class="badge badge-data">{{ $totalKasus }} kasus</span>
                            </td>
                            <td class="text-center">
                                <button type="button" class="btn btn-action-btn" 
                                        data-bs-toggle="collapse" 
                                        data-bs-target="#detail-{{ $item->id }}" 
                                        aria-expanded="false" 
                                        title="Lihat Detail Lokasi">
                                    <i class="fas fa-map-marker-alt text-accent"></i>
                                </button>
                            </td>
                        </tr>
                        <!-- Sub-Baris Detail Lokasi Dropdown -->
                        <tr class="collapse" id="detail-{{ $item->id }}">
                            <td colspan="5" class="p-3 bg-subtle-panel">
                                <div class="detail-box p-3 rounded-4 border bg-white shadow-sm">
                                    <div class="d-flex align-items-center mb-3 gap-2">
                                        <i class="fas fa-map-marked-alt text-accent fs-5"></i>
                                        <h6 class="fw-bold text-dark mb-0">Rincian Lokasi Kejadian - {{ $item->nama_kecamatan }}</h6>
                                    </div>
                                    
                                    @if($item->kriminalitas->count() > 0)
                                        <div class="table-responsive">
                                            <table class="table table-sm table-hover align-middle mb-0 text-start">
                                                <thead class="table-light">
                                                    <tr class="text-secondary small">
                                                        <th style="width: 90px;">TAHUN</th>
                                                        <th>JENIS KRIMINALITAS</th>
                                                        <th>LOKASI / ALAMAT</th>
                                                        <th style="width: 140px;">KERAWANAN</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($item->kriminalitas as $krim)
                                                        @php
                                                            // 1. Penanganan Lokasi / Alamat / Koordinat
                                                            $lokasiTeks = $krim->lokasi 
                                                                        ?? $krim->alamat 
                                                                        ?? $krim->lokasi_kejadian 
                                                                        ?? $krim->nama_lokasi 
                                                                        ?? null;

                                                            if (!$lokasiTeks && isset($krim->latitude) && isset($krim->longitude)) {
                                                                $lokasiTeks = $krim->latitude . ', ' . $krim->longitude;
                                                            } elseif (!$lokasiTeks && isset($krim->lat) && isset($krim->lng)) {
                                                                $lokasiTeks = $krim->lat . ', ' . $krim->lng;
                                                            }

                                                            // 2. Penanganan Tingkat Kerawanan
                                                            $kerawananVal = $krim->kerawanan 
                                                                          ?? $krim->tingkat_kerawanan 
                                                                          ?? $krim->status_kerawanan 
                                                                          ?? $krim->kategori_kerawanan 
                                                                          ?? '-';

                                                            $kerawananLower = strtolower($kerawananVal);
                                                        @endphp
                                                        <tr>
                                                            <td class="fw-semibold text-muted">{{ $krim->tahun ?? '-' }}</td>
                                                            <td class="fw-bold text-dark">{{ $krim->jenis_kriminalitas ?? $krim->jenis ?? '-' }}</td>
                                                            <td class="text-secondary">
                                                                <i class="fas fa-location-dot text-danger me-1"></i>
                                                                {{ $lokasiTeks ?? 'Lokasi tidak spesifik' }}
                                                            </td>
                                                            <td>
                                                                @if(str_contains($kerawananLower, 'tinggi'))
                                                                    <span class="badge badge-kerawanan badge-tinggi">TINGGI</span>
                                                                @elseif(str_contains($kerawananLower, 'sedang'))
                                                                    <span class="badge badge-kerawanan badge-sedang">SEDANG</span>
                                                                @elseif(str_contains($kerawananLower, 'rendah'))
                                                                    <span class="badge badge-kerawanan badge-rendah">RENDAH</span>
                                                                @elseif($kerawananVal !== '-')
                                                                    <span class="badge badge-kerawanan bg-light text-dark border">{{ strtoupper($kerawananVal) }}</span>
                                                                @else
                                                                    <span class="text-muted small">-</span>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @else
                                        <div class="text-center py-3 text-muted small">
                                            <i class="fas fa-info-circle me-1"></i>Belum ada data lokasi kejadian tercatat untuk wilayah ini.
                                        </div>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">Belum ada data kecamatan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap">
<style>
    :root {
        --accent: #ff4d6d;
        --accent-glow: rgba(255, 77, 109, 0.25);
        --text-primary: #0f172a;
        --panel-border: rgba(226, 232, 240, 0.8);
    }

    body { 
        font-family: 'Plus Jakarta Sans', sans-serif; 
        color: var(--text-primary); 
    }

    .text-accent { color: var(--accent) !important; }

    /* Main Card Frame */
    .app-card {
        border-radius: 20px !important;
        border: 1px solid var(--panel-border) !important;
        background: #ffffff;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.03) !important;
    }

    /* Table Design */
    .custom-table th {
        font-size: 0.72rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.6px;
        color: #94a3b8;
        padding: 1.1rem 1.25rem;
        border-bottom: 1px solid #f1f5f9;
        background: #ffffff;
    }

    .custom-table td {
        padding: 1.1rem 1.25rem;
        border-bottom: 1px solid #f8fafc;
        font-size: 0.9rem;
    }

    /* Soft Pill Badges */
    .badge-data {
        background: #fef3c7;
        color: #d97706;
        font-weight: 700;
        padding: 0.4rem 0.85rem;
        border-radius: 20px;
        font-size: 0.78rem;
    }

    .badge-tahun {
        background: #f1f5f9;
        color: #475569;
        font-weight: 700;
        padding: 0.4rem 0.75rem;
        border-radius: 8px;
        font-size: 0.78rem;
    }

    /* Action Buttons */
    .btn-action-btn {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        background: #fff1f2;
        border: 1px solid #ffe4e6;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
    }

    .btn-action-btn:hover {
        background: var(--accent);
        border-color: var(--accent);
        transform: translateY(-2px);
    }

    .btn-action-btn:hover i {
        color: #ffffff !important;
    }

    /* Inner Dropdown Details */
    .bg-subtle-panel {
        background-color: #f8fafc !important;
    }

    .detail-box {
        border-color: #e2e8f0 !important;
    }

    /* Kerawanan Badges */
    .badge-kerawanan {
        font-weight: 800;
        font-size: 0.7rem;
        padding: 0.35rem 0.75rem;
        border-radius: 20px;
        letter-spacing: 0.5px;
    }

    .badge-tinggi {
        background: rgba(255, 77, 109, 0.12);
        color: #ff4d6d;
    }

    .badge-sedang {
        background: #fef3c7;
        color: #d97706;
    }

    .badge-rendah {
        background: #dcfce7;
        color: #15803d;
    }
</style>
@endpush