@extends('layouts.admin')

@section('title', 'Edit Kecamatan')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1"><i class="fas fa-edit me-2 text-accent"></i>Edit Kecamatan</h4>
        <p class="text-muted small mb-0">Ubah informasi detail nama, kode, total keseluruhan kasus, atau peta wilayah kecamatan.</p>
    </div>
    <a href="{{ route('admin.kecamatan.index') }}" class="btn btn-light-soft btn-sm px-3">
        <i class="fas fa-arrow-left me-1"></i>Kembali
    </a>
</div>

@if ($errors->any())
    <div class="alert alert-danger d-flex align-items-center gap-2 mb-4">
        <i class="fas fa-exclamation-circle fs-5"></i>
        <ul class="mb-0 ps-3">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="card border-0 shadow-sm app-card" style="max-width:650px">
    <div class="card-body p-4">
        <form action="{{ route('admin.kecamatan.update', $kecamatan->id) }}" method="POST" enctype="multipart/form-data">
            @csrf 
            @method('PUT')

            <div class="mb-3">
                <label for="nama_kecamatan" class="form-label">Nama Kecamatan <span class="text-accent">*</span></label>
                <div class="input-icon-wrapper">
                    <i class="fas fa-map-marker-alt"></i>
                    <input type="text" name="nama_kecamatan" id="nama_kecamatan"
                           class="form-control @error('nama_kecamatan') is-invalid @enderror"
                           value="{{ old('nama_kecamatan', $kecamatan->nama_kecamatan) }}" placeholder="Contoh: Prabumulih Timur">
                </div>
                @error('nama_kecamatan')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="kode_kecamatan" class="form-label">Kode Kecamatan</label>
                <div class="input-icon-wrapper">
                    <i class="fas fa-hashtag"></i>
                    <input type="text" name="kode_kecamatan" id="kode_kecamatan"
                           class="form-control @error('kode_kecamatan') is-invalid @enderror"
                           value="{{ old('kode_kecamatan', $kecamatan->kode_kecamatan) }}" placeholder="Contoh: 16.74.02">
                </div>
                @error('kode_kecamatan')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="total_keseluruhan" class="form-label">Total Keseluruhan Kasus (Gabungan) <span class="text-accent">*</span></label>
                <div class="input-icon-wrapper">
                    <i class="fas fa-chart-bar"></i>
                    <input type="number" name="total_keseluruhan" id="total_keseluruhan"
                           class="form-control @error('total_keseluruhan') is-invalid @enderror"
                           value="{{ old('total_keseluruhan', $kecamatan->total_keseluruhan ?? 0) }}" min="0" placeholder="Contoh: 120" required>
                </div>
                @error('total_keseluruhan')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="total_2023" class="form-label">Total Kasus 2023 <span class="text-accent">*</span></label>
                    <div class="input-icon-wrapper">
                        <i class="fas fa-calendar-alt"></i>
                        <input type="number" name="total_2023" id="total_2023"
                               class="form-control @error('total_2023') is-invalid @enderror"
                               value="{{ old('total_2023', $kecamatan->total_2023 ?? 0) }}" min="0" required>
                    </div>
                    @error('total_2023')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4 mb-3">
                    <label for="total_2024" class="form-label">Total Kasus 2024 <span class="text-accent">*</span></label>
                    <div class="input-icon-wrapper">
                        <i class="fas fa-calendar-alt"></i>
                        <input type="number" name="total_2024" id="total_2024"
                               class="form-control @error('total_2024') is-invalid @enderror"
                               value="{{ old('total_2024', $kecamatan->total_2024 ?? 0) }}" min="0" required>
                    </div>
                    @error('total_2024')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4 mb-3">
                    <label for="total_2025" class="form-label">Total Kasus 2025 <span class="text-accent">*</span></label>
                    <div class="input-icon-wrapper">
                        <i class="fas fa-calendar-alt"></i>
                        <input type="number" name="total_2025" id="total_2025"
                               class="form-control @error('total_2025') is-invalid @enderror"
                               value="{{ old('total_2025', $kecamatan->total_2025 ?? 0) }}" min="0" required>
                    </div>
                    @error('total_2025')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <hr class="app-hr my-4">

            <div class="mb-4">
                <label for="geojson" class="form-label">
                    <i class="fas fa-draw-polygon text-accent me-1"></i>Upload GeoJSON Baru <span class="text-muted fw-normal">(Opsional)</span>
                </label>
                <div class="input-icon-wrapper">
                    <i class="fas fa-file-code"></i>
                    <input type="file" name="geojson" id="geojson"
                           class="form-control @error('geojson') is-invalid @enderror" accept=".json,.geojson">
                </div>
                @error('geojson')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror

                @if($kecamatan->geojson)
                    <div class="status-badge status-badge-success mt-3">
                        <i class="fas fa-check-circle"></i>
                        <span>GeoJSON sudah tersedia — upload baru hanya untuk mengganti berkas lama</span>
                    </div>
                @else
                    <div class="status-badge status-badge-muted mt-3">
                        <i class="fas fa-info-circle"></i>
                        <span>Belum ada data file GeoJSON untuk kecamatan ini</span>
                    </div>
                @endif
            </div>

            <div class="d-flex align-items-center gap-2 pt-2">
                <button type="submit" class="btn btn-accent px-4">
                    <i class="fas fa-save me-1"></i>Update Data
                </button>
                <a href="{{ route('admin.kecamatan.index') }}" class="btn btn-light-soft px-3">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap">
<style>
    :root {
        --accent: #ff4d6d;
        --accent-glow: rgba(255, 77, 109, 0.25);
        --accent-soft: #ff7a8a;
        --text-primary: #0f172a;
        --text-muted: #64748b;
        --panel-border: rgba(226, 232, 240, 0.8);
    }

    body { font-family: 'Plus Jakarta Sans', sans-serif; color: var(--text-primary); }

    .text-accent { color: var(--accent) !important; }

    .app-card {
        border-radius: 20px !important;
        border: 1px solid var(--panel-border) !important;
        background: #ffffff;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.03) !important;
    }

    .app-hr { border-color: #e2e8f0; opacity: 0.6; }

    /* Input with Icon Wrapper */
    .input-icon-wrapper {
        position: relative;
        display: flex;
        align-items: center;
    }

    .input-icon-wrapper i {
        position: absolute;
        left: 14px;
        color: #94a3b8;
        font-size: 0.95rem;
        pointer-events: none;
        transition: color 0.2s ease;
    }

    .input-icon-wrapper .form-control {
        padding-left: 42px;
        border-radius: 12px;
        border: 1.5px solid #e2e8f0;
        background: #f8fafc;
        font-size: 0.9rem;
        font-weight: 600;
        color: #0f172a;
        transition: all 0.2s ease;
    }

    .input-icon-wrapper .form-control:focus {
        background: #ffffff;
        border-color: var(--accent);
        box-shadow: 0 0 0 4px var(--accent-glow);
    }

    .input-icon-wrapper .form-control:focus + i,
    .input-icon-wrapper:focus-within i {
        color: var(--accent);
    }

    /* Status Badges */
    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 0.5rem 0.85rem;
        border-radius: 10px;
        font-size: 0.82rem;
        font-weight: 600;
    }

    .status-badge-success {
        background: rgba(16, 185, 129, 0.1);
        color: #059669;
    }

    .status-badge-muted {
        background: #f1f5f9;
        color: #64748b;
    }

    /* Buttons */
    .btn-accent {
        background: linear-gradient(135deg, var(--accent), var(--accent-soft));
        border: none;
        color: #fff;
        border-radius: 12px;
        font-weight: 700;
        padding: 0.6rem 1.4rem;
        box-shadow: 0 6px 18px var(--accent-glow);
        transition: all 0.2s ease;
    }

    .btn-accent:hover {
        color: #fff;
        transform: translateY(-2px);
        box-shadow: 0 8px 22px var(--accent-glow);
    }

    .btn-light-soft {
        background: #f1f5f9;
        border: 1px solid #e2e8f0;
        color: #475569;
        border-radius: 12px;
        font-weight: 600;
        padding: 0.6rem 1.2rem;
        transition: all 0.2s ease;
    }

    .btn-light-soft:hover {
        background: #e2e8f0;
        color: #0f172a;
    }

    .form-label {
        font-weight: 700;
        font-size: 0.83rem;
        color: #334155;
        margin-bottom: 6px;
    }

    .alert-danger {
        border-radius: 14px;
        border: none;
        background: rgba(255, 77, 109, 0.1);
        color: #c0314a;
    }
</style>
@endpush