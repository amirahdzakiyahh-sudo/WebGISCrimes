@extends('layouts.admin')

@section('title', 'Tambah Data Kriminalitas')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold"><i class="fas fa-plus-circle me-2 text-accent"></i>Tambah Data Kriminalitas</h4>
    <a href="{{ route('admin.kriminalitas.index') }}" class="btn btn-light-soft btn-sm">
        <i class="fas fa-arrow-left me-1"></i>Kembali
    </a>
</div>

@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="card border-0 shadow-sm app-card">
    <div class="card-body">
        <form action="{{ route('admin.kriminalitas.store') }}" method="POST">
            @csrf

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="kecamatan_id" class="form-label">Kecamatan <span class="text-accent">*</span></label>
                    <select name="kecamatan_id" id="kecamatan_id" class="form-select @error('kecamatan_id') is-invalid @enderror" required>
                        <option value="">-- Pilih Kecamatan --</option>
                        @foreach ($kecamatans as $kec)
                            <option value="{{ $kec->id }}" {{ old('kecamatan_id') == $kec->id ? 'selected' : '' }}>
                                {{ $kec->nama_kecamatan }}
                            </option>
                        @endforeach
                    </select>
                    @error('kecamatan_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3 position-relative">
                    <label for="tahun_display" class="form-label">Tahun <span class="text-accent">*</span></label>
                    <input type="text" id="tahun_display" class="form-control @error('tahun') is-invalid @enderror"
                           value="{{ old('tahun') }}" placeholder="Pilih tahun" readonly autocomplete="off">
                    <input type="hidden" name="tahun" id="tahun" value="{{ old('tahun') }}">
                    @error('tahun')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror

                    <div id="yearPickerDropdown" class="year-picker-dropdown shadow">
                        <div class="year-picker-header">
                            <button type="button" id="decadePrev" class="year-picker-nav">&#9650;</button>
                            <span id="decadeLabel" class="fw-bold"></span>
                            <button type="button" id="decadeNext" class="year-picker-nav">&#9660;</button>
                        </div>
                        <div id="yearGrid" class="year-picker-grid"></div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="jenis_kriminalitas" class="form-label">Jenis Kriminalitas <span class="text-accent">*</span></label>
                    <select name="jenis_kriminalitas" id="jenis_kriminalitas" class="form-select @error('jenis_kriminalitas') is-invalid @enderror" required>
                        <option value="">-- Pilih Jenis Kriminalitas --</option>
                        @php
                            $jenisOptions = [
                                'Penganiayaan Berat (Anirat)',
                                'Pencurian dengan Kekerasan (Curas)',
                                'Penggelapan',
                                'Pengeroyokan',
                                'Penganiayaan Ringan (Aniring)',
                                'Pencurian Biasa',
                            ];
                        @endphp
                        @foreach ($jenisOptions as $opt)
                            <option value="{{ $opt }}" {{ old('jenis_kriminalitas') == $opt ? 'selected' : '' }}>{{ $opt }}</option>
                        @endforeach
                    </select>
                    @error('jenis_kriminalitas')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="jumlah_kasus" class="form-label">Jumlah Kasus <span class="text-accent">*</span></label>
                    <input type="number" name="jumlah_kasus" id="jumlah_kasus" min="0"
                           class="form-control @error('jumlah_kasus') is-invalid @enderror"
                           value="{{ old('jumlah_kasus') }}" required>
                    @error('jumlah_kasus')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Tingkat Kerawanan diubah menjadi opsional -->
            <div class="mb-3">
                <label for="tingkat_kerawanan" class="form-label">Tingkat Kerawanan <span class="text-muted fw-normal">(Opsional)</span></label>
                <select name="tingkat_kerawanan" id="tingkat_kerawanan" class="form-select @error('tingkat_kerawanan') is-invalid @enderror">
                    <option value="">-- Pilih Tingkat Kerawanan (Opsional) --</option>
                    <option value="rendah" {{ old('tingkat_kerawanan') == 'rendah' ? 'selected' : '' }}>Rendah</option>
                    <option value="sedang" {{ old('tingkat_kerawanan') == 'sedang' ? 'selected' : '' }}>Sedang</option>
                    <option value="tinggi" {{ old('tingkat_kerawanan') == 'tinggi' ? 'selected' : '' }}>Tinggi</option>
                </select>
                @error('tingkat_kerawanan')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <hr class="app-hr">
            <h6 class="fw-bold mb-3">Detail Alamat <span class="text-muted fw-normal">(Opsional)</span></h6>

            <div class="mb-3">
                <label for="alamat_detail" class="form-label">Alamat Detail</label>
                <input type="text" name="alamat_detail" id="alamat_detail" maxlength="255"
                       class="form-control @error('alamat_detail') is-invalid @enderror"
                       value="{{ old('alamat_detail') }}">
                @error('alamat_detail')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="row">
                <div class="col-md-3 mb-3">
                    <label for="rt" class="form-label">RT</label>
                    <input type="text" name="rt" id="rt" maxlength="5"
                           class="form-control @error('rt') is-invalid @enderror"
                           value="{{ old('rt') }}">
                    @error('rt')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-3 mb-3">
                    <label for="rw" class="form-label">RW</label>
                    <input type="text" name="rw" id="rw" maxlength="5"
                           class="form-control @error('rw') is-invalid @enderror"
                           value="{{ old('rw') }}">
                    @error('rw')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="kelurahan" class="form-label">Kelurahan</label>
                    <input type="text" name="kelurahan" id="kelurahan" maxlength="255"
                           class="form-control @error('kelurahan') is-invalid @enderror"
                           value="{{ old('kelurahan') }}">
                    @error('kelurahan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Titik Lokasi di Peta</label>
                <p class="text-muted small mb-2">Cari nama lokasi/alamat di bawah, ketik koordinat langsung di kolom Latitude/Longitude, atau klik langsung pada peta untuk menentukan titik lokasi kasus.</p>

                <div class="position-relative mb-2">
                    <input type="text" id="searchLocation" class="form-control"
                           placeholder="Cari nama lokasi, jalan, atau alamat...">
                    <div id="searchResults" class="search-results-dropdown"></div>
                </div>

                <div id="map" class="app-map"></div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="latitude" class="form-label">Latitude</label>
                    <input type="text" name="latitude" id="latitude"
                           class="form-control @error('latitude') is-invalid @enderror"
                           value="{{ old('latitude') }}" placeholder="Contoh: -3.4300">
                    @error('latitude')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="longitude" class="form-label">Longitude</label>
                    <input type="text" name="longitude" id="longitude"
                           class="form-control @error('longitude') is-invalid @enderror"
                           value="{{ old('longitude') }}" placeholder="Contoh: 104.2300">
                    @error('longitude')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="mt-3">
                <button type="submit" class="btn btn-accent">
                    <i class="fas fa-save me-1"></i>Simpan
                </button>
                <a href="{{ route('admin.kriminalitas.index') }}" class="btn btn-light-soft">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap">
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
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
    }

    .app-hr { border-color: var(--panel-border); opacity: 1; }

    .app-map { height: 400px; border-radius: 12px; overflow: hidden; z-index: 0; }

    .btn-accent {
        background: linear-gradient(135deg, var(--accent), var(--accent-soft));
        border: none;
        color: #fff;
        border-radius: 10px;
        font-weight: 700;
        padding: 0.55rem 1.2rem;
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

    .form-label { font-weight: 600; font-size: 0.85rem; color: var(--text-primary); }

    .form-control, .form-select {
        border-radius: 10px;
        border: 1px solid rgba(15,15,30,0.12);
        padding: 0.55rem 0.85rem;
        font-size: 0.9rem;
    }
    .form-control:focus, .form-select:focus {
        border-color: var(--accent);
        box-shadow: 0 0 0 0.2rem var(--accent-glow);
    }

    .alert-danger {
        border-radius: 12px;
        border: none;
        background: rgba(255, 77, 109, 0.1);
        color: #c0314a;
    }

    .year-picker-dropdown {
        display: none;
        position: absolute;
        top: 100%;
        left: 0;
        z-index: 1050;
        background: #ffffff;
        color: var(--text-primary);
        border: 1px solid var(--panel-border);
        border-radius: 12px;
        padding: 12px;
        width: 280px;
        margin-top: 6px;
        box-shadow: 0 10px 30px rgba(15,15,30,0.15);
    }
    .year-picker-dropdown.show { display: block; }
    .year-picker-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 10px;
        padding: 0 6px;
    }
    .year-picker-nav {
        background: none;
        border: none;
        color: var(--text-muted);
        font-size: 12px;
        cursor: pointer;
    }
    .year-picker-nav:hover { color: var(--accent); }
    .year-picker-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 8px;
    }
    .year-picker-grid .year-item {
        text-align: center;
        padding: 10px 0;
        border-radius: 50%;
        cursor: pointer;
        color: var(--text-muted);
        font-size: 14px;
    }
    .year-picker-grid .year-item:hover { background: rgba(255,77,109,0.08); }
    .year-picker-grid .year-item.current-decade { color: var(--text-primary); font-weight: 600; }
    .year-picker-grid .year-item.out-decade { color: #c4c4d0; }
    .year-picker-grid .year-item.selected {
        background: var(--accent);
        color: #fff;
        font-weight: 700;
    }

    .search-results-dropdown {
        display: none;
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        z-index: 1050;
        background: #fff;
        border: 1px solid rgba(15,15,30,0.12);
        border-radius: 10px;
        margin-top: 4px;
        max-height: 220px;
        overflow-y: auto;
        box-shadow: 0 10px 30px rgba(15,15,30,0.15);
    }
    .search-results-dropdown.show { display: block; }
    .search-result-item {
        padding: 0.6rem 0.9rem;
        font-size: 0.85rem;
        cursor: pointer;
        border-bottom: 1px solid rgba(15,15,30,0.06);
    }
    .search-result-item:last-child { border-bottom: none; }
    .search-result-item:hover { background: rgba(255,77,109,0.08); }
</style>
@endpush

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    (function () {
        const display = document.getElementById('tahun_display');
        const hiddenInput = document.getElementById('tahun');
        const dropdown = document.getElementById('yearPickerDropdown');
        const yearGrid = document.getElementById('yearGrid');
        const decadeLabel = document.getElementById('decadeLabel');
        const btnPrev = document.getElementById('decadePrev');
        const btnNext = document.getElementById('decadeNext');

        let currentYear = parseInt(hiddenInput.value) || new Date().getFullYear();
        let decadeStart = Math.floor(currentYear / 10) * 10 - 2;

        function renderGrid() {
            yearGrid.innerHTML = '';
            decadeLabel.textContent = (decadeStart + 2) + ' - ' + (decadeStart + 11);

            for (let i = 0; i < 16; i++) {
                const year = decadeStart + i;
                const div = document.createElement('div');
                div.classList.add('year-item');
                div.textContent = year;

                const isInDecade = year >= decadeStart + 2 && year <= decadeStart + 11;
                div.classList.add(isInDecade ? 'current-decade' : 'out-decade');

                if (year === parseInt(hiddenInput.value)) {
                    div.classList.add('selected');
                }

                div.addEventListener('click', function () {
                    hiddenInput.value = year;
                    display.value = year;
                    dropdown.classList.remove('show');
                });

                yearGrid.appendChild(div);
            }
        }

        display.addEventListener('click', function (e) {
            e.stopPropagation();
            dropdown.classList.toggle('show');
            renderGrid();
        });

        btnPrev.addEventListener('click', function () {
            decadeStart -= 10;
            renderGrid();
        });

        btnNext.addEventListener('click', function () {
            decadeStart += 10;
            renderGrid();
        });

        document.addEventListener('click', function (e) {
            if (!dropdown.contains(e.target) && e.target !== display) {
                dropdown.classList.remove('show');
            }
        });

        renderGrid();
    })();

    const kecamatanBoundaries = {
        @foreach ($kecamatans as $kec)
            @if ($kec->geojson)
                {{ $kec->id }}: {!! $kec->geojson !!},
            @endif
        @endforeach
    };

    const map = L.map('map').setView([-3.4300, 104.2300], 13);

    L.tileLayer('https://{s}.google.com/vt/lyrs=m&x={x}&y={y}&z={z}', {
        maxZoom: 20,
        subdomains: ['mt0', 'mt1', 'mt2', 'mt3'],
        attribution: '&copy; <a href="https://www.google.com/maps">Google Maps</a>'
    }).addTo(map);

    let marker = null;
    let boundaryLayer = null;

    function setMarker(lat, lng) {
        if (marker) {
            marker.setLatLng([lat, lng]);
        } else {
            marker = L.marker([lat, lng], { draggable: true }).addTo(map);
            marker.on('dragend', function (e) {
                const pos = marker.getLatLng();
                document.getElementById('latitude').value = pos.lat.toFixed(6);
                document.getElementById('longitude').value = pos.lng.toFixed(6);
            });
        }
        document.getElementById('latitude').value = lat.toFixed(6);
        document.getElementById('longitude').value = lng.toFixed(6);
    }

    function updateMarkerFromInput() {
        const latInput = document.getElementById('latitude');
        const lngInput = document.getElementById('longitude');
        const lat = parseFloat(latInput.value);
        const lng = parseFloat(lngInput.value);

        if (isNaN(lat) || isNaN(lng)) return;
        if (lat < -90 || lat > 90 || lng < -180 || lng > 180) return;

        setMarker(lat, lng);
        map.setView([lat, lng], 16);
    }

    document.getElementById('latitude').addEventListener('change', updateMarkerFromInput);
    document.getElementById('longitude').addEventListener('change', updateMarkerFromInput);

    const searchInput = document.getElementById('searchLocation');
    const searchResults = document.getElementById('searchResults');
    let searchTimeout = null;

    searchInput.addEventListener('input', function () {
        const keyword = this.value.trim();
        clearTimeout(searchTimeout);

        if (keyword.length < 3) {
            searchResults.classList.remove('show');
            return;
        }

        searchTimeout = setTimeout(function () {
            fetch('https://nominatim.openstreetmap.org/search?format=json&limit=5&countrycodes=id&q=' + encodeURIComponent(keyword + ', Prabumulih'))
                .then(function (res) { return res.json(); })
                .then(function (data) {
                    searchResults.innerHTML = '';
                    if (data.length === 0) {
                        searchResults.innerHTML = '<div class="search-result-item text-muted">Lokasi tidak ditemukan</div>';
                        searchResults.classList.add('show');
                        return;
                    }
                    data.forEach(function (place) {
                        const item = document.createElement('div');
                        item.className = 'search-result-item';
                        item.textContent = place.display_name;
                        item.addEventListener('click', function () {
                            const lat = parseFloat(place.lat);
                            const lng = parseFloat(place.lon);
                            setMarker(lat, lng);
                            map.setView([lat, lng], 16);
                            searchInput.value = place.display_name;
                            searchResults.classList.remove('show');
                        });
                        searchResults.appendChild(item);
                    });
                    searchResults.classList.add('show');
                })
                .catch(function () {
                    searchResults.classList.remove('show');
                });
        }, 500);
    });

    document.addEventListener('click', function (e) {
        if (!searchResults.contains(e.target) && e.target !== searchInput) {
            searchResults.classList.remove('show');
        }
    });

    function showBoundary(kecamatanId) {
        if (boundaryLayer) {
            map.removeLayer(boundaryLayer);
            boundaryLayer = null;
        }

        const geojsonData = kecamatanBoundaries[kecamatanId];
        if (!geojsonData) return;

        boundaryLayer = L.geoJSON(geojsonData, {
            style: {
                color: '#ff4d6d',
                weight: 2,
                fillColor: '#ff4d6d',
                fillOpacity: 0.15
            }
        }).addTo(map);

        map.fitBounds(boundaryLayer.getBounds());
    }

    map.on('click', function (e) {
        setMarker(e.latlng.lat, e.latlng.lng);
    });

    document.getElementById('kecamatan_id').addEventListener('change', function () {
        const kecamatanId = this.value;
        if (kecamatanId) {
            showBoundary(kecamatanId);
        } else if (boundaryLayer) {
            map.removeLayer(boundaryLayer);
            boundaryLayer = null;
        }
    });

    @if (old('kecamatan_id'))
        showBoundary({{ old('kecamatan_id') }});
        @if (old('latitude') && old('longitude'))
            setMarker({{ old('latitude') }}, {{ old('longitude') }});
        @endif
    @endif
</script>
@endpush