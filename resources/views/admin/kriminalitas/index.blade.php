@extends('layouts.admin')

@section('title', 'Data Kriminalitas')

@section('content')
<!-- Header Section -->
<div class="row align-items-center mb-4">
    <div class="col-md-6 mb-3 mb-md-0">
        <h4 class="fw-extrabold mb-1" style="color: var(--text-primary); letter-spacing: -0.5px;">
            <i class="fas fa-chart-pie me-2 text-accent"></i>Manajemen Data Kriminalitas
        </h4>
        <p class="text-muted small mb-0">Pusat data titik rawan, statistik, dan rekam jejak kriminalitas wilayah.</p>
    </div>
    <div class="col-md-6 text-md-end">
        <div class="d-flex justify-content-md-end align-items-center gap-2">
            <!-- Tombol Hapus Terpilih (Bulk Delete) -->
            <button type="button" id="btnBulkDelete" class="btn btn-danger btn-sm px-3 shadow-sm animate__animated animate__fadeIn" style="display: none; border-radius: 10px; font-weight: 600;">
                <i class="fas fa-trash-alt me-1"></i>Hapus Terpilih (<span id="selectedCount">0</span>)
            </button>
            <a href="{{ route('admin.kriminalitas.create') }}" class="btn btn-accent btn-sm px-3 py-2">
                <i class="fas fa-plus-circle me-1"></i>Tambah Data Baru
            </a>
        </div>
    </div>
</div>

{{-- Notifikasi Sukses --}}
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert" style="border-radius: 12px; background: rgba(46, 204, 113, 0.12); color: #1b8a4a; font-weight: 500;">
        <div class="d-flex align-items-center">
            <i class="fas fa-check-circle fs-5 me-2"></i>
            <div>{{ session('success') }}</div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<form id="bulkDeleteForm" action="{{ route('admin.kriminalitas.bulk-destroy') }}" method="POST">
    @csrf
    @method('DELETE')

    <div class="card border-0 shadow-sm app-card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 app-table align-middle">
                    <thead>
                        <tr>
                            <th style="width: 45px;" class="text-center">
                                <input type="checkbox" id="selectAll" class="form-check-input cursor-pointer shadow-none">
                            </th>
                            <th style="width: 60px;" class="text-center">No</th>
                            <th>Kecamatan</th>
                            <th class="text-center">Tahun</th>
                            <th>Jenis Kriminalitas</th>
                            <th>Jumlah Kasus</th>
                            <th>Tingkat Kerawanan</th>
                            <th class="text-center" style="width: 120px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($data as $index => $item)
                        <tr>
                            <td class="text-center">
                                <input type="checkbox" name="ids[]" value="{{ $item->id }}" class="form-check-input item-checkbox cursor-pointer shadow-none">
                            </td>
                            <td class="text-center fw-semibold text-muted">{{ $data->firstItem() + $index }}</td>
                            <td>
                                <div class="fw-bold text-dark d-flex align-items-center">
                                    <i class="fas fa-map-marker-alt text-accent me-2 opacity-75"></i>
                                    {{ $item->kecamatan->nama_kecamatan ?? '-' }}
                                </div>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-light text-dark border px-2.5 py-1 fw-bold" style="border-radius: 8px;">{{ $item->tahun }}</span>
                            </td>
                            <td class="fw-semibold text-secondary">{{ $item->jenis_kriminalitas }}</td>
                            <td>
                                <span class="badge bg-light text-dark border px-3 py-2 rounded-pill fw-bold" style="font-size: 0.85rem; box-shadow: 0 2px 5px rgba(0,0,0,0.02);">
                                    <i class="fas fa-chart-line text-accent me-1"></i> {{ $item->jumlah_kasus }}
                                </span>
                            </td>
                            <td>
                                @php
                                    $rawKerawanan = strtolower(trim($item->tingkat_kerawanan ?? ''));
                                    // Deteksi fleksibel berdasarkan teks yang diinput
                                    $badgeClass = 'default';
                                    if (str_contains($rawKerawanan, 'rendah')) {
                                        $badgeClass = 'rendah';
                                    } elseif (str_contains($rawKerawanan, 'sedang')) {
                                        $badgeClass = 'sedang';
                                    } elseif (str_contains($rawKerawanan, 'tinggi')) {
                                        $badgeClass = 'tinggi';
                                    }
                                @endphp
                                <span class="badge-kerawanan badge-{{ $badgeClass }}">
                                    <i class="fas fa-circle me-1" style="font-size: 6px;"></i>
                                    {{ strtoupper($item->tingkat_kerawanan ?: 'TIDAK TERSEDIA') }}
                                </span>
                            </td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-1">
                                    <a href="{{ route('admin.kriminalitas.edit', $item->id) }}" class="btn btn-icon btn-icon-edit" title="Edit Data">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" class="btn btn-icon btn-icon-delete btn-delete-single" data-id="{{ $item->id }}" title="Hapus Data">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-5">
                                <div class="py-4">
                                    <div class="mb-3">
                                        <span class="d-inline-flex align-items-center justify-content-center bg-light text-muted rounded-circle" style="width: 70px; height: 70px;">
                                            <i class="fas fa-folder-open fa-2x opacity-50"></i>
                                        </span>
                                    </div>
                                    <h6 class="fw-bold text-dark mb-1">Belum Ada Data Kriminalitas</h6>
                                    <p class="small text-muted mb-3">Silakan tambahkan data baru untuk mulai memetakan wilayah kerawanan.</p>
                                    <a href="{{ route('admin.kriminalitas.create') }}" class="btn btn-accent btn-sm px-3">
                                        <i class="fas fa-plus me-1"></i>Tambah Data Sekarang
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</form>

{{-- Form Tersembunyi untuk Hapus Satuan via SweetAlert --}}
<form id="deleteSingleForm" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

<!-- Pagination Section -->
<div class="mt-4 d-flex justify-content-center">
    {{ $data->links() }}
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<style>
    :root {
        --accent: #ff4d6d;
        --accent-glow: rgba(255, 77, 109, 0.25);
        --accent-soft: #ff7a8a;
        --text-primary: #1c1c28;
        --text-muted: #6b6b80;
        --panel-border: rgba(15, 15, 30, 0.08);
    }

    body { font-family: 'Plus Jakarta Sans', sans-serif; color: var(--text-primary); background-color: #f8f9fc; }
    .text-accent { color: var(--accent) !important; }
    .cursor-pointer { cursor: pointer; }
    .fw-extrabold { font-weight: 800; }

    .app-card {
        border-radius: 16px !important;
        border: 1px solid var(--panel-border) !important;
        box-shadow: 0 10px 30px rgba(15,15,30,0.03) !important;
        overflow: hidden;
        background: #ffffff;
    }

    .btn-accent {
        background: linear-gradient(135deg, var(--accent), var(--accent-soft));
        border: none;
        color: #fff;
        border-radius: 10px;
        font-weight: 700;
        box-shadow: 0 6px 16px var(--accent-glow);
        transition: all 0.2s ease;
    }
    .btn-accent:hover {
        color: #fff;
        transform: translateY(-1px);
        box-shadow: 0 10px 22px var(--accent-glow);
    }

    .app-table thead {
        background: #fdfdff;
    }
    .app-table thead th {
        color: var(--text-muted);
        font-size: 0.72rem;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        font-weight: 700;
        border-bottom: 2px solid var(--panel-border);
        padding: 1.1rem 1rem;
        white-space: nowrap;
    }
    .app-table tbody td {
        padding: 1rem;
        font-size: 0.88rem;
        border-bottom: 1px solid var(--panel-border);
        color: var(--text-primary);
    }
    .app-table tbody tr:last-child td { border-bottom: none; }
    .app-table tbody tr:hover { background: rgba(255,77,109,0.015); }

    /* Badge Kerawanan yang Ditingkatkan */
    .badge-kerawanan {
        display: inline-flex;
        align-items: center;
        padding: 6px 12px;
        border-radius: 30px;
        font-size: 0.68rem;
        font-weight: 700;
        letter-spacing: 0.5px;
    }
    .badge-rendah { background: rgba(46, 204, 113, 0.12); color: #1b8a4a; }
    .badge-sedang { background: rgba(245, 166, 35, 0.12); color: #b57205; }
    .badge-tinggi { background: rgba(255, 77, 109, 0.12); color: #d6304f; }
    .badge-default { background: rgba(107, 107, 128, 0.12); color: var(--text-muted); }

    /* Tombol Ikon Aksi */
    .btn-icon {
        width: 35px;
        height: 35px;
        border-radius: 10px;
        border: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 0.85rem;
        transition: all 0.2s ease;
    }
    .btn-icon:hover { transform: translateY(-2px); }
    .btn-icon-edit { background: rgba(245, 166, 35, 0.12); color: #b57205; }
    .btn-icon-edit:hover { background: #f5a623; color: #fff; box-shadow: 0 4px 12px rgba(245, 166, 35, 0.3); }
    .btn-icon-delete { background: rgba(255, 77, 109, 0.12); color: var(--accent); }
    .btn-icon-delete:hover { background: var(--accent); color: #fff; box-shadow: 0 4px 12px var(--accent-glow); }

    /* Pagination Styling */
    .pagination .page-link {
        color: var(--text-primary);
        border-radius: 10px;
        margin: 0 3px;
        border: 1px solid var(--panel-border);
        font-weight: 600;
        padding: 0.5rem 0.8rem;
    }
    .pagination .page-item.active .page-link {
        background: var(--accent);
        border-color: var(--accent);
        color: #fff;
        box-shadow: 0 4px 12px var(--accent-glow);
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const selectAllCheckbox = document.getElementById('selectAll');
        const itemCheckboxes = document.querySelectorAll('.item-checkbox');
        const btnBulkDelete = document.getElementById('btnBulkDelete');
        const selectedCountSpan = document.getElementById('selectedCount');
        const bulkDeleteForm = document.getElementById('bulkDeleteForm');

        function updateBulkDeleteButton() {
            const checkedCount = document.querySelectorAll('.item-checkbox:checked').length;
            selectedCountSpan.textContent = checkedCount;

            if (checkedCount > 0) {
                btnBulkDelete.style.display = 'inline-flex';
            } else {
                btnBulkDelete.style.display = 'none';
            }
        }

        if (selectAllCheckbox) {
            selectAllCheckbox.addEventListener('change', function () {
                itemCheckboxes.forEach(checkbox => {
                    checkbox.checked = selectAllCheckbox.checked;
                });
                updateBulkDeleteButton();
            });
        }

        itemCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function () {
                updateBulkDeleteButton();
                if (!this.checked && selectAllCheckbox) {
                    selectAllCheckbox.checked = false;
                } else if (document.querySelectorAll('.item-checkbox:checked').length === itemCheckboxes.length) {
                    if (selectAllCheckbox) selectAllCheckbox.checked = true;
                }
            });
        });

        // SweetAlert untuk Bulk Delete (Hapus Terpilih)
        if (btnBulkDelete) {
            btnBulkDelete.addEventListener('click', function () {
                const count = document.querySelectorAll('.item-checkbox:checked').length;
                Swal.fire({
                    title: 'Hapus Data Terpilih?',
                    text: `Apakah Anda yakin ingin menghapus ${count} data kriminalitas yang dicentang secara permanen?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ff4d6d',
                    cancelButtonColor: '#6b6b80',
                    confirmButtonText: 'Ya, Hapus Sekarang!',
                    cancelButtonText: 'Batal',
                    reverseButtons: true,
                    customClass: { popup: 'app-card' }
                }).then((result) => {
                    if (result.isConfirmed) {
                        bulkDeleteForm.submit();
                    }
                });
            });
        }

        // SweetAlert untuk Hapus Satuan
        const deleteSingleButtons = document.querySelectorAll('.btn-delete-single');
        const deleteSingleForm = document.getElementById('deleteSingleForm');

        deleteSingleButtons.forEach(button => {
            button.addEventListener('click', function () {
                const id = this.getAttribute('data-id');
                Swal.fire({
                    title: 'Hapus Data Ini?',
                    text: "Data wilayah kriminalitas yang dihapus tidak dapat dikembalikan!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ff4d6d',
                    cancelButtonColor: '#6b6b80',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        deleteSingleForm.action = `/admin/kriminalitas/${id}`;
                        deleteSingleForm.submit();
                    }
                });
            });
        });
    });
</script>
@endpush