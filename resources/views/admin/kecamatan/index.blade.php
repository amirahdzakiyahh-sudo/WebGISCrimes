@extends('layouts.admin')

@section('title', 'Data Kecamatan')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold"><i class="fas fa-map me-2 text-accent"></i>Data Kecamatan</h4>
    <a href="{{ route('admin.kecamatan.create') }}" class="btn btn-accent btn-sm">
        <i class="fas fa-plus me-1"></i>Tambah Kecamatan
    </a>
</div>

<div class="card border-0 shadow-sm app-card">
    <div class="card-body p-0">
        <table class="table table-hover mb-0 app-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Kecamatan</th>
                    <th>Kode</th>
                    <th>GeoJSON</th>
                    <th>Jumlah Data</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($kecamatans as $k)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $k->nama_kecamatan }}</td>
                    <td>{{ $k->kode_kecamatan ?? '-' }}</td>
                    <td>
                        @if($k->geojson)
                            <span class="badge-kerawanan badge-rendah">Ada</span>
                        @else
                            <span class="badge-kerawanan badge-default">Belum</span>
                        @endif
                    </td>
                    <td><span class="badge-kerawanan badge-sedang">{{ $k->kriminalitas_count }} data</span></td>
                    <td>
                        <a href="{{ route('admin.kecamatan.edit', $k->id) }}" class="btn btn-icon btn-icon-edit">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('admin.kecamatan.destroy', $k->id) }}" method="POST" class="d-inline"
                            onsubmit="return confirm('Hapus kecamatan ini?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-icon btn-icon-delete"><i class="fas fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center text-muted py-4">Belum ada data kecamatan</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-3">{{ $kecamatans->links() }}</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap">
<style>
    /* ===== Tema selaras dengan halaman Data Kriminalitas ===== */
    :root {
        --accent: #ff4d6d;
        --accent-glow: rgba(255, 77, 109, 0.25);
        --accent-soft: #ff7a8a;
        --text-primary: #1c1c28;
        --text-muted: #6b6b80;
        --panel-border: rgba(15, 15, 30, 0.06);
    }

    body { font-family: 'Plus Jakarta Sans', sans-serif; color: var(--text-primary); }

    .text-accent { color: var(--accent) !important; }

    .app-card {
        border-radius: 16px !important;
        border: 1px solid var(--panel-border) !important;
        box-shadow: 0 8px 28px rgba(15,15,30,0.06) !important;
        overflow: hidden;
    }

    /* ===== Tombol ===== */
    .btn-accent {
        background: linear-gradient(135deg, var(--accent), var(--accent-soft));
        border: none;
        color: #fff;
        border-radius: 10px;
        font-weight: 700;
        padding: 0.45rem 1rem;
        box-shadow: 0 6px 16px var(--accent-glow);
        transition: transform 0.15s ease, box-shadow 0.15s ease;
    }
    .btn-accent:hover {
        color: #fff;
        transform: translateY(-1px);
        box-shadow: 0 10px 22px var(--accent-glow);
    }

    .btn-light-soft {
        background: rgba(0,0,0,0.04);
        border: 1px solid var(--panel-border);
        color: var(--text-primary);
        border-radius: 10px;
        font-weight: 600;
        transition: all 0.15s ease;
    }
    .btn-light-soft:hover {
        background: rgba(0,0,0,0.07);
        color: var(--text-primary);
    }

    /* ===== Tabel ===== */
    .app-table thead {
        background: #fbfbfd;
    }
    .app-table thead th {
        color: var(--text-muted);
        font-size: 0.72rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-weight: 700;
        border-bottom: 1px solid var(--panel-border);
        padding: 0.9rem 1rem;
        white-space: nowrap;
    }
    .app-table tbody td {
        padding: 0.85rem 1rem;
        vertical-align: middle;
        font-size: 0.86rem;
        border-bottom: 1px solid var(--panel-border);
        color: var(--text-primary);
    }
    .app-table tbody tr:last-child td { border-bottom: none; }
    .app-table tbody tr:hover { background: rgba(255,77,109,0.04); }

    /* ===== Badge ===== */
    .badge-kerawanan {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 700;
        letter-spacing: 0.3px;
    }
    .badge-rendah { background: rgba(46, 204, 113, 0.15); color: #1e9e58; }
    .badge-sedang { background: rgba(245, 166, 35, 0.15); color: #c97e0e; }
    .badge-tinggi { background: rgba(255, 77, 109, 0.15); color: #e23e5b; }
    .badge-default { background: rgba(107, 107, 128, 0.15); color: var(--text-muted); }

    /* ===== Tombol aksi ikon ===== */
    .btn-icon {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        border: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 0.8rem;
        transition: transform 0.15s ease;
    }
    .btn-icon:hover { transform: translateY(-1px); }
    .btn-icon-edit { background: rgba(245, 166, 35, 0.12); color: #c97e0e; }
    .btn-icon-edit:hover { background: #f5a623; color: #fff; }
    .btn-icon-delete { background: rgba(255, 77, 109, 0.12); color: var(--accent); margin-left: 4px; }
    .btn-icon-delete:hover { background: var(--accent); color: #fff; }

    /* ===== Pagination ===== */
    .pagination .page-link {
        color: var(--text-primary);
        border-radius: 8px;
        margin: 0 2px;
        border: 1px solid var(--panel-border);
    }
    .pagination .page-item.active .page-link {
        background: var(--accent);
        border-color: var(--accent);
        color: #fff;
    }
</style>
@endpush