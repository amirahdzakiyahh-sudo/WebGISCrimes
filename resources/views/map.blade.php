@extends('layouts.app')

@section('title', 'Peta Kriminalitas')

@push('styles')
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap">
<style>
    :root {
        --bg-dark: #f4f4f7;
        --panel-bg: rgba(255, 255, 255, 0.95);
        --panel-border: rgba(15, 15, 30, 0.06);
        --accent: #ff4d6d;
        --accent-glow: rgba(255, 77, 109, 0.25);
        --text-primary: #1c1c28;
        --text-muted: #6b6b80;
        --success: #2ecc71;
        --warning: #f5a623;
        --danger: #ff4d6d;
    }

    * { margin: 0; padding: 0; box-sizing: border-box; }

    body {
        font-family: 'Plus Jakarta Sans', sans-serif;
        background: var(--bg-dark);
    }

    #map {
        width: 100%;
        height: 100vh;
        position: absolute;
        top: 0;
        left: 0;
        z-index: 1;
    }

    /* ===== Scrollbar ===== */
    ::-webkit-scrollbar { width: 6px; }
    ::-webkit-scrollbar-track { background: transparent; }
    ::-webkit-scrollbar-thumb { background: rgba(0,0,0,0.15); border-radius: 10px; }
    ::-webkit-scrollbar-thumb:hover { background: rgba(0,0,0,0.3); }

    /* ===== Header Atas ===== */
    .top-header {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        z-index: 1001;
        background: rgba(255, 255, 255, 0.92);
        backdrop-filter: blur(18px);
        -webkit-backdrop-filter: blur(18px);
        border-bottom: 1px solid var(--panel-border);
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        padding: 0.6rem 1.5rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        animation: slideInDown 0.5s ease-out;
    }

    @keyframes slideInDown {
        from { opacity: 0; transform: translateY(-15px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .top-header-left {
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .top-header-logo {
        height: 44px;
        width: auto;
        object-fit: contain;
        flex-shrink: 0;
    }

    .top-header-text {
        display: flex;
        flex-direction: column;
        line-height: 1.25;
    }

    .top-header-title {
        color: var(--text-primary);
        font-weight: 800;
        font-size: 1rem;
        letter-spacing: 0.3px;
    }

    .top-header-subtitle {
        color: var(--text-muted);
        font-weight: 600;
        font-size: 0.75rem;
        letter-spacing: 0.2px;
    }

    .top-header-page-title {
        display: flex;
        align-items: center;
        gap: 8px;
        color: var(--text-primary);
        font-weight: 700;
        font-size: 0.9rem;
        background: rgba(0,0,0,0.04);
        border: 1px solid var(--panel-border);
        border-radius: 12px;
        padding: 0.5rem 1rem;
    }

    .top-header-page-title .dot {
        width: 8px; height: 8px;
        background: var(--accent);
        border-radius: 50%;
        box-shadow: 0 0 10px var(--accent);
        animation: pulse 1.8s infinite;
        flex-shrink: 0;
    }

    .mobile-menu-btn { display: none; }

    @keyframes pulse {
        0%, 100% { opacity: 1; transform: scale(1); }
        50% { opacity: 0.5; transform: scale(1.3); }
    }

    /* ===== Panel Filter ===== */
    .filter-panel {
        position: absolute;
        top: 96px;
        left: 24px;
        z-index: 1000;
        background: var(--panel-bg);
        backdrop-filter: blur(18px);
        -webkit-backdrop-filter: blur(18px);
        border-radius: 18px;
        padding: 1.4rem;
        width: 280px;
        border: 1px solid var(--panel-border);
        box-shadow: 0 8px 32px rgba(0,0,0,0.1);
        animation: slideInLeft 0.5s ease-out;
    }

    @keyframes slideInLeft {
        from { opacity: 0; transform: translateX(-20px); }
        to { opacity: 1; transform: translateX(0); }
    }

    .filter-panel .panel-title {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 8px;
        color: var(--text-primary);
        font-weight: 700;
        font-size: 0.95rem;
        margin-bottom: 1.1rem;
        letter-spacing: 0.3px;
        cursor: pointer;
    }

    .filter-panel .panel-title-left {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .filter-panel .panel-toggle {
        display: none;
        background: rgba(0,0,0,0.04);
        border: none;
        color: var(--text-muted);
        width: 28px;
        height: 28px;
        border-radius: 8px;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        font-size: 0.8rem;
        transition: transform 0.25s ease;
    }

    .filter-panel.collapsed .panel-toggle { transform: rotate(180deg); }

    .filter-panel .panel-title .icon-badge {
        width: 28px;
        height: 28px;
        border-radius: 9px;
        background: linear-gradient(135deg, var(--accent), #ff8a5c);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.8rem;
        box-shadow: 0 4px 12px var(--accent-glow);
    }

    .panel-body {
        display: grid;
        grid-template-rows: 1fr;
        transition: grid-template-rows 0.3s ease, opacity 0.25s ease;
        opacity: 1;
    }
    .panel-body-inner { overflow: hidden; min-height: 0; }
    .filter-panel.collapsed .panel-body {
        grid-template-rows: 0fr;
        opacity: 0;
    }

    .field-group { margin-bottom: 0.9rem; }

    .field-group label {
        display: block;
        font-size: 0.7rem;
        text-transform: uppercase;
        letter-spacing: 0.6px;
        color: var(--text-muted);
        margin-bottom: 0.4rem;
        font-weight: 600;
    }

    .filter-panel select {
        appearance: none;
        -webkit-appearance: none;
        background: rgba(0,0,0,0.03) url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='6' viewBox='0 0 10 6'%3E%3Cpath d='M1 1l4 4 4-4' stroke='%236b6b80' stroke-width='1.5' fill='none' fill-rule='evenodd'/%3E%3C/svg%3E") no-repeat right 14px center;
        border: 1px solid rgba(0,0,0,0.1);
        color: var(--text-primary);
        border-radius: 11px;
        padding: 0.65rem 2rem 0.65rem 0.9rem;
        width: 100%;
        font-size: 0.85rem;
        font-family: inherit;
        transition: all 0.2s ease;
        cursor: pointer;
    }

    .filter-panel select:hover { background-color: rgba(0,0,0,0.05); border-color: rgba(0,0,0,0.2); }
    .filter-panel select:focus { outline: none; border-color: var(--accent); box-shadow: 0 0 0 3px var(--accent-glow); }
    .filter-panel select option { background: #ffffff; color: #1c1c28; }

    /* ===== Control Transparansi Slider ===== */
    .transparency-section {
        margin-top: 0.6rem;
        margin-bottom: 0.8rem;
    }

    .transparency-header {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 0.88rem;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 0.6rem;
    }

    .transparency-header i {
        font-size: 0.95rem;
        color: #0066ff;
    }

    .transparency-row {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .transparency-label {
        font-size: 0.78rem;
        color: #555;
        font-weight: 500;
        white-space: nowrap;
        width: 82px;
    }

    .transparency-slider-container {
        flex: 1;
        display: flex;
        align-items: center;
    }

    .transparency-range {
        -webkit-appearance: none;
        appearance: none;
        width: 100%;
        height: 8px;
        border-radius: 5px;
        background: #e0e0e0;
        outline: none;
        cursor: pointer;
    }

    .transparency-range::-webkit-slider-thumb {
        -webkit-appearance: none;
        appearance: none;
        width: 18px;
        height: 18px;
        border-radius: 50%;
        background: #0066ff;
        cursor: pointer;
        box-shadow: 0 2px 6px rgba(0,102,255,0.3);
        transition: transform 0.1s ease;
    }

    .transparency-range::-webkit-slider-thumb:hover {
        transform: scale(1.15);
    }

    .transparency-value {
        font-size: 0.78rem;
        font-weight: 600;
        color: #333;
        width: 38px;
        text-align: right;
    }

    /* ===== Toggle Switch Titik Kejadian ===== */
    .toggle-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0.2rem 0;
    }

    .toggle-row span {
        font-size: 0.75rem;
        font-weight: 700;
        color: var(--text-primary);
    }

    .switch {
        position: relative;
        display: inline-block;
        width: 38px;
        height: 20px;
    }

    .switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .slider {
        position: absolute;
        cursor: pointer;
        top: 0; left: 0; right: 0; bottom: 0;
        background-color: #ccc;
        transition: .3s;
        border-radius: 20px;
    }

    .slider:before {
        position: absolute;
        content: "";
        height: 14px;
        width: 14px;
        left: 3px;
        bottom: 3px;
        background-color: white;
        transition: .3s;
        border-radius: 50%;
    }

    input:checked + .slider {
        background-color: var(--accent);
    }

    input:checked + .slider:before {
        transform: translateX(18px);
    }

    .btn-filter {
        background: linear-gradient(135deg, var(--accent), #ff7a8a);
        border: none;
        color: #fff;
        border-radius: 11px;
        padding: 0.7rem 1rem;
        width: 100%;
        font-size: 0.85rem;
        font-weight: 700;
        font-family: inherit;
        cursor: pointer;
        margin-top: 0.3rem;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        box-shadow: 0 6px 16px var(--accent-glow);
        transition: transform 0.15s ease, box-shadow 0.15s ease;
    }

    .btn-filter:hover { transform: translateY(-2px); box-shadow: 0 10px 22px var(--accent-glow); }
    .btn-filter:active { transform: translateY(0); }

    .btn-filter.loading { pointer-events: none; opacity: 0.7; }
    .btn-filter .spinner {
        width: 14px; height: 14px;
        border: 2px solid rgba(255,255,255,0.4);
        border-top-color: #fff;
        border-radius: 50%;
        animation: spin 0.7s linear infinite;
        display: none;
    }
    .btn-filter.loading .spinner { display: inline-block; }
    .btn-filter.loading .btn-label { display: none; }
    @keyframes spin { to { transform: rotate(360deg); } }

    /* ===== Statistik mini di dalam panel ===== */
    .panel-divider {
        height: 1px;
        background: rgba(0,0,0,0.08);
        margin: 0.8rem 0;
    }
    .stat-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 0.78rem;
        color: var(--text-muted);
        margin-bottom: 4px;
    }
    .stat-row b { color: var(--text-primary); font-weight: 700; }

    /* ===== Legenda & Info Logo Area Bottom-Left ===== */
    .bottom-left-container {
        position: absolute;
        bottom: 28px;
        left: 24px;
        z-index: 1000;
        display: flex;
        align-items: flex-end;
        gap: 12px;
    }

    .legend {
        background: var(--panel-bg);
        backdrop-filter: blur(18px);
        -webkit-backdrop-filter: blur(18px);
        border-radius: 16px;
        padding: 1.1rem 1.3rem;
        border: 1px solid var(--panel-border);
        color: var(--text-primary);
        min-width: 195px;
        max-height: 70vh;
        overflow-y: auto;
        box-shadow: 0 8px 28px rgba(0,0,0,0.1);
        animation: slideInLeft 0.6s ease-out;
    }

    .legend h6 {
        color: var(--text-muted);
        font-weight: 700;
        margin-bottom: 0.8rem;
        font-size: 0.7rem;
        text-transform: uppercase;
        letter-spacing: 0.6px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .legend-item {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 0.55rem;
        font-size: 0.82rem;
        font-weight: 500;
    }

    .legend-item:last-child {
        margin-bottom: 0;
    }

    .legend-color {
        width: 14px;
        height: 14px;
        border-radius: 5px;
        flex-shrink: 0;
        box-shadow: 0 0 8px currentColor;
    }

    /* ===== Tombol & Popover Info Logo Kejadian ===== */
    .logo-info-wrapper {
        position: relative;
        animation: slideInLeft 0.7s ease-out;
    }

    .logo-info-btn {
        background: var(--panel-bg);
        backdrop-filter: blur(18px);
        -webkit-backdrop-filter: blur(18px);
        border: 1px solid var(--panel-border);
        color: var(--accent);
        border-radius: 50%;
        width: 38px;
        height: 38px;
        font-size: 1rem;
        font-weight: 700;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 8px 28px rgba(0,0,0,0.1);
        transition: all 0.2s ease;
    }

    .logo-info-btn:hover {
        background: #ffffff;
        transform: translateY(-2px) scale(1.05);
        box-shadow: 0 10px 30px rgba(0,0,0,0.15);
    }

    /* Popover Daftar Logo Kejadian */
    .logo-info-popover {
        display: none;
        position: absolute;
        bottom: 50px;
        left: 0;
        width: 270px;
        background: var(--panel-bg);
        backdrop-filter: blur(18px);
        -webkit-backdrop-filter: blur(18px);
        border: 1px solid var(--panel-border);
        border-radius: 16px;
        padding: 1.1rem;
        box-shadow: 0 12px 32px rgba(0,0,0,0.15);
        z-index: 1005;
        animation: fadeIn 0.2s ease-out;
    }

    .logo-info-popover.active {
        display: block;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(8px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .logo-info-popover h6 {
        color: var(--text-muted);
        font-weight: 700;
        margin-bottom: 0.8rem;
        font-size: 0.7rem;
        text-transform: uppercase;
        letter-spacing: 0.6px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .logo-info-popover h6 .close-popover {
        cursor: pointer;
        font-size: 0.85rem;
        color: var(--text-muted);
    }

    .logo-info-popover h6 .close-popover:hover {
        color: var(--accent);
    }

    .logo-list {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .logo-item {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 0.78rem;
        font-weight: 600;
        color: var(--text-primary);
        padding: 4px 6px;
        border-radius: 8px;
        transition: background 0.15s;
    }

    .logo-item:hover {
        background: rgba(0,0,0,0.03);
    }

    .logo-badge-icon {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        background-color: #ffffff;
        border: 2px solid var(--accent);
        box-shadow: 0 2px 6px rgba(255,77,109,0.3);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--accent);
        font-size: 11px;
        flex-shrink: 0;
    }

    /* ===== Tombol Admin ===== */
    .admin-btn {
        position: absolute;
        top: 96px;
        right: 24px;
        z-index: 1000;
        background: var(--panel-bg);
        backdrop-filter: blur(18px);
        -webkit-backdrop-filter: blur(18px);
        border: 1px solid var(--panel-border);
        color: var(--text-primary);
        border-radius: 12px;
        padding: 0.7rem 1.2rem;
        font-size: 0.85rem;
        font-weight: 600;
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 8px;
        box-shadow: 0 8px 24px rgba(0,0,0,0.1);
        transition: all 0.2s ease;
        animation: slideInRight 0.5s ease-out;
    }

    @keyframes slideInRight {
        from { opacity: 0; transform: translateX(20px); }
        to { opacity: 1; transform: translateX(0); }
    }

    .admin-btn:hover {
        background: linear-gradient(135deg, var(--accent), #ff7a8a);
        color: #fff;
        transform: translateY(-2px);
        box-shadow: 0 10px 26px var(--accent-glow);
    }

    /* ===== Styling Kontrol Layer (Pilih Basemap) ===== */
    .leaflet-control-layers {
        border: 1px solid var(--panel-border) !important;
        border-radius: 14px !important;
        background: var(--panel-bg) !important;
        backdrop-filter: blur(18px);
        -webkit-backdrop-filter: blur(18px);
        box-shadow: 0 8px 24px rgba(0,0,0,0.12) !important;
        padding: 8px 12px !important;
        font-family: 'Plus Jakarta Sans', sans-serif !important;
        color: var(--text-primary) !important;
    }
    .leaflet-control-layers-toggle {
        width: 36px !important;
        height: 36px !important;
        background-size: 20px 20px !important;
    }
    .leaflet-control-layers-expanded {
        padding: 10px 14px !important;
    }
    .leaflet-control-layers label {
        font-size: 0.82rem !important;
        font-weight: 600 !important;
        margin-bottom: 4px !important;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    /* ===== LEAFLET POPUP DESAIN BARU ===== */
    .leaflet-popup-content-wrapper {
        background: #ffffff;
        color: var(--text-primary);
        border-radius: 16px;
        border: 1px solid rgba(0, 0, 0, 0.06);
        box-shadow: 0 12px 30px rgba(0, 0, 0, 0.15);
        padding: 0;
        overflow: hidden;
    }
    .leaflet-popup-pane { z-index: 1200 !important; }
    .leaflet-popup { z-index: 1200 !important; }
    .leaflet-popup-content { margin: 0 !important; width: 240px !important; }
    .leaflet-popup-tip { background: #ffffff; }
    
    .leaflet-popup-close-button {
        color: #8c8c9e !important;
        font-size: 16px !important;
        top: 10px !important;
        right: 10px !important;
        z-index: 5;
        transition: color 0.2s;
    }
    .leaflet-popup-close-button:hover { color: #ff4d6d !important; }

    .popup-card-custom {
        font-family: 'Plus Jakarta Sans', sans-serif;
        padding: 14px 16px;
    }

    .popup-card-header {
        display: flex;
        align-items: center;
        gap: 10px;
        padding-bottom: 10px;
        margin-bottom: 10px;
        border-bottom: 1px dashed rgba(0, 0, 0, 0.1);
    }

    .popup-card-icon {
        width: 32px;
        height: 32px;
        border-radius: 10px;
        background: #fff0f3;
        color: #ff4d6d;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        flex-shrink: 0;
    }

    .popup-card-icon.icon-kecamatan {
        background: #eef2ff;
        color: #4f46e5;
    }

    .popup-card-title {
        font-weight: 700;
        font-size: 0.9rem;
        color: #1e293b;
        line-height: 1.25;
        padding-right: 12px;
    }

    .popup-card-body {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .popup-info-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        font-size: 0.78rem;
    }

    .popup-info-label {
        color: #64748b;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .popup-info-value {
        color: #0f172a;
        font-weight: 700;
    }

    .popup-badge-kasus {
        background: #fff0f3;
        color: #ff4d6d;
        border: 1px solid rgba(255, 77, 109, 0.2);
        padding: 3px 8px;
        border-radius: 6px;
        font-weight: 700;
        font-size: 0.75rem;
    }

    .popup-badge-kerawanan {
        padding: 3px 10px;
        border-radius: 6px;
        font-weight: 700;
        font-size: 0.75rem;
        color: #ffffff;
        letter-spacing: 0.3px;
    }

    /* ===== Efek pulse marker kustom ===== */
    .custom-marker-icon { transition: transform 0.15s ease; }
    .custom-marker-icon:hover { transform: scale(1.15); z-index: 999 !important; }

    /* ===== Restyle Zoom Control ===== */
    .leaflet-top.leaflet-right {
        top: 85px !important;
        right: 24px !important;
    }

    .leaflet-control-zoom {
        border: 1px solid var(--panel-border) !important;
        border-radius: 12px !important;
        overflow: hidden;
        box-shadow: 0 8px 24px rgba(0,0,0,0.1) !important;
        background: var(--panel-bg) !important;
        backdrop-filter: blur(18px);
    }

    .leaflet-control-zoom a {
        background: transparent !important;
        color: var(--text-primary) !important;
        border-bottom: 1px solid var(--panel-border) !important;
        font-weight: 700 !important;
        font-size: 16px !important;
        line-height: 30px !important;
        width: 34px !important;
        height: 34px !important;
        transition: all 0.2s ease;
    }

    .leaflet-control-zoom a:last-child {
        border-bottom: none !important;
    }

    .leaflet-control-zoom a:hover {
        background: var(--accent) !important;
        color: #fff !important;
    }

    /* ===== Responsif (Tampilan HP) ===== */
    @media (max-width: 768px) {
        .top-header {
            padding: 0.5rem 0.8rem;
            flex-direction: row;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
        }
        .top-header-left { width: auto; gap: 8px; }
        .top-header-logo { height: 30px; }
        .top-header-title { font-size: 0.7rem; }
        .top-header-subtitle { display: none; }
        .top-header-page-title { display: none; }

        .mobile-menu-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 34px;
            height: 34px;
            border-radius: 9px;
            background: rgba(0,0,0,0.04);
            border: none;
            color: var(--text-primary);
            font-size: 1rem;
            cursor: pointer;
            flex-shrink: 0;
        }

        .admin-btn { display: none; }

        .filter-panel {
            top: 64px;
            left: 12px;
            right: auto;
            width: 230px;
            padding: 0.7rem 0.8rem;
            border-radius: 12px;
            max-height: none;
            overflow: visible;
            box-shadow: 0 6px 18px rgba(0,0,0,0.18);
        }

        .filter-panel .panel-title { display: none; }

        .filter-panel .panel-body { display: block; }
        .filter-panel .panel-body-inner { padding-top: 0; overflow: visible; }

        .filter-panel .field-group { margin-bottom: 0.6rem; }
        .filter-panel .field-group label { font-size: 0.62rem; margin-bottom: 0.25rem; }
        .filter-panel select {
            padding: 0.45rem 1.7rem 0.45rem 0.7rem;
            font-size: 0.75rem;
            border-radius: 9px;
        }

        .filter-panel .btn-filter {
            padding: 0.5rem 0.8rem;
            font-size: 0.75rem;
            border-radius: 9px;
        }

        .filter-panel .panel-divider { margin: 0.7rem 0; }
        .filter-panel .stat-row { font-size: 0.68rem; }

        .filter-panel.mobile-hidden { display: none; }

        .leaflet-top.leaflet-right {
            top: 64px !important;
            right: 12px !important;
        }

        .bottom-left-container {
            left: 12px;
            bottom: 12px;
            flex-direction: column;
            align-items: flex-start;
            gap: 8px;
        }

        .legend {
            min-width: 0;
            width: 170px;
            max-height: none;
            padding: 0.75rem 0.9rem;
            border-radius: 12px;
        }

        .legend h6 {
            margin-bottom: 0.55rem;
            font-size: 0.64rem;
        }
        .legend-item {
            margin-bottom: 0.4rem;
            font-size: 0.72rem;
            gap: 8px;
        }
        .legend-color { width: 11px; height: 11px; }

        .logo-info-popover {
            width: 230px;
            bottom: 45px;
        }
    }

    @media (max-width: 768px) and (orientation: landscape) {
        .filter-panel { top: 60px; }
        .legend { max-height: 30vh; overflow-y: auto; }
    }
    
    /* --- TEMPELKAN KODE CSS BARU UNTUK PANEL & INFO BAR DI BAWAH INI --- */

    /* 1. Styling untuk Panel Basemap (Kartu Pilihan Peta) */
    .basemap-panel {
        position: absolute;
        bottom: 20px;
        right: 20px;
        z-index: 1000;
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(10px);
        padding: 12px;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.15);
        width: 240px;
        font-family: inherit;
    }
    .basemap-panel-title { font-size: 11px; font-weight: bold; color: #666; margin-bottom: 8px; letter-spacing: 0.5px; }
    .basemap-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 8px; margin-bottom: 10px; }
    .basemap-card { background: #f8f9fa; border: 2px solid transparent; border-radius: 8px; padding: 4px; text-align: center; cursor: pointer; transition: all 0.2s ease; }
    .basemap-card:hover { background: #e9ecef; }
    .basemap-card.active { border-color: #f63b3b; background: #eff6ff; }
    .basemap-thumb { height: 40px; border-radius: 6px; margin-bottom: 4px; background-size: cover; background-position: center; }
    .basemap-card span { font-size: 11px; font-weight: 500; color: #333; }
    .basemap-option { border-top: 1px solid #eee; padding-top: 8px; font-size: 12px; color: #444; }
    .basemap-option label { display: flex; align-items: center; gap: 6px; cursor: pointer; }
    
    /* Warna Thumbnail Basemap */
    .terrain-bg { background-color: #d1e7dd; }
    .osm-bg { background-color: #cfe2ff; }
    .topo-bg { background-color: #f8d7da; }
    .satelit-bg { background-color: #343a40; }
    .hillshade-bg { background-color: #e2e3e5; }
    .light-bg { background-color: #f8f9fa; border: 1px solid #ccc; }
    .dark-bg { background-color: #212529; }

    /* 2. Styling untuk Bar Koordinat & Skala di Pojok Kiri Bawah */
    .map-info-bar {
    position: absolute;
    bottom: 20px;
    left: 50%;
    transform: translateX(-50%);
    z-index: 1000;
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
    display: flex;
    align-items: center;
    padding: 6px 14px;
    border-radius: 30px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.15);
    font-family: inherit;
    font-size: 12px;
    color: #333;
    gap: 12px;
}
    .info-item { display: flex; align-items: center; gap: 6px; white-space: nowrap; }
    .dot-blue { width: 8px; height: 8px; background-color: #ee420e; border-radius: 50%; }
    .btn-salin { background: none; border: none; color: #eb6425; font-weight: 600; cursor: pointer; padding: 0; font-size: 12px; }
    .btn-salin:hover { text-decoration: underline; }
    .info-divider { width: 1px; height: 16px; background-color: #d1d5db; }

</style>
@endpush

@section('content')
<div id="map"></div>

{{-- ===== Header Atas ===== --}}
<div class="top-header">
    <div class="top-header-left">
        <img src="{{ asset('images/logo-prabumulih.png') }}" alt="Logo Kota Prabumulih" class="top-header-logo">
        <div class="top-header-text">
            <span class="top-header-title">PEMERINTAH KOTA PRABUMULIH</span>
            <span class="top-header-subtitle">Sistem Informasi Pemetaan Kriminalitas</span>
        </div>
    </div>

    <div class="top-header-page-title">
        <span class="dot"></span> Peta Kriminalitas Kota Prabumulih
    </div>

    <button type="button" class="mobile-menu-btn" id="mobileMenuBtn" aria-label="Menu">
        <i class="fas fa-bars"></i>
    </button>
</div>

<div class="filter-panel" id="filterPanel">
    <div class="panel-title" onclick="toggleFilterPanel()">
        <span class="panel-title-left">
            <span class="icon-badge"><i class="fas fa-sliders-h"></i></span>
            Filter Data
        </span>
        <button type="button" class="panel-toggle" id="panelToggleBtn" aria-label="Lipat/Buka Filter">
            <i class="fas fa-chevron-up"></i>
        </button>
    </div>

    <div class="panel-body" id="panelBody">
      <div class="panel-body-inner">
        <div class="field-group">
            <label>Tahun</label>
            <select id="filterTahun">
                <option value="">Semua Tahun</option>
                @foreach($tahunList as $t)
                    <option value="{{ $t->tahun }}">{{ $t->tahun }}</option>
                @endforeach
            </select>
        </div>

        <div class="field-group">
            <label>Jenis Kriminalitas</label>
            <select id="filterJenis">
                <option value="">Semua Jenis</option>
                <option value="Penganiayaan Berat (Anirat)">Penganiayaan Berat (Anirat)</option>
                <option value="Pencurian dengan Kekerasan (Curas)">Pencurian dengan Kekerasan (Curas)</option>
                <option value="Penggelapan">Penggelapan</option>
                <option value="Pengeroyokan">Pengeroyokan</option>
                <option value="Penganiayaan Ringan (Aniring)">Penganiayaan Ringan (Aniring)</option>
                <option value="Pencurian Biasa">Pencurian Biasa</option>
            </select>
        </div>

        {{-- ===== SECTION TRANSPARANSI SLIDER (Area Rawan) ===== --}}
        <div class="transparency-section">
            <div class="transparency-header">
                <i class="fas fa-adjust"></i>
                <span>Transparansi</span>
            </div>
            <div class="transparency-row">
                <span class="transparency-label">Area Rawan:</span>
                <div class="transparency-slider-container">
                    <input type="range" id="transparencySlider" class="transparency-range" min="0" max="100" value="30" oninput="updatePolygonOpacity(this.value)">
                </div>
                <span class="transparency-value" id="transparencyValue">30%</span>
            </div>
        </div>

        {{-- ===== Toggle Switch Titik Kejadian ===== --}}
        <div class="field-group">
            <div class="toggle-row">
                <span>Tampilkan Titik Kejadian</span>
                <label class="switch">
                    <input type="checkbox" id="toggleMarkers" checked onchange="toggleMarkerVisibility()">
                    <span class="slider"></span>
                </label>
            </div>
        </div>

        <button class="btn-filter" id="btnFilter" onclick="loadMap()">
            <span class="spinner"></span>
            <span class="btn-label"><i class="fas fa-search me-1"></i>Tampilkan</span>
        </button>

        <div class="panel-divider"></div>

        <div class="stat-row">
            <span>Total Kasus</span>
            <b id="statTotal">-</b>
        </div>
        <div class="stat-row">
            <span>Wilayah Terdampak</span>
            <b id="statWilayah">-</b>
        </div>
      </div>
    </div>
</div>

{{-- ===== Container Bawah Kiri: Legenda + Tombol Tanda Seru Logo Kejadian Sampingnya ===== --}}
<div class="bottom-left-container">
    {{-- 1. Legenda Tingkat Kerawanan --}}
    <div class="legend">
        <h6><i class="fas fa-layer-group"></i> Tingkat Kerawanan</h6>
        <div class="legend-item"><div class="legend-color" style="background:#00fd6a;color:#00fd6a"></div> Rendah</div>
        <div class="legend-item"><div class="legend-color" style="background:#fcf700;color:#fcf700"></div> Sedang</div>
        <div class="legend-item"><div class="legend-color" style="background:#f70000;color:#f70000"></div> Tinggi</div>
    </div>

    {{-- 2. Tombol Tanda Seru & Popover Logo Kejadian di Samping Legenda --}}
    <div class="logo-info-wrapper">
        <button type="button" class="logo-info-btn" onclick="toggleLogoInfoPopover(event)" title="Info Logo Kejadian">
            <i class="fas fa-exclamation"></i>
        </button>

        <div class="logo-info-popover" id="logoInfoPopover">
            <h6>
                <span><i class="fas fa-icons me-1"></i> Arti Logo Kejadian</span>
                <i class="fas fa-times close-popover" onclick="toggleLogoInfoPopover(event)"></i>
            </h6>
            <div class="logo-list">
                <div class="logo-item">
                    <div class="logo-badge-icon"><i class="fas fa-skull-crossbones"></i></div>
                    <span>Penganiayaan Berat (Anirat)</span>
                </div>
                <div class="logo-item">
                    <div class="logo-badge-icon"><i class="fas fa-mask"></i></div>
                    <span>Pencurian Kekerasan (Curas)</span>
                </div>
                <div class="logo-item">
                    <div class="logo-badge-icon"><i class="fas fa-users-slash"></i></div>
                    <span>Pengeroyokan</span>
                </div>
                <div class="logo-item">
                    <div class="logo-badge-icon"><i class="fas fa-file-invoice-dollar"></i></div>
                    <span>Penggelapan</span>
                </div>
                <div class="logo-item">
                    <div class="logo-badge-icon"><i class="fas fa-user-injured"></i></div>
                    <span>Penganiayaan Ringan (Aniring)</span>
                </div>
                <div class="logo-item">
                    <div class="logo-badge-icon"><i class="fas fa-user-secret"></i></div>
                    <span>Pencurian Biasa</span>
                </div>
            </div>

            {{-- Tambahan Catatan: Titik kejadian tidak mencakup keseluruhan kasus --}}
            <div style="margin-top: 12px; padding-top: 10px; border-top: 1px solid #eee; font-size: 0.78rem; line-height: 1.4; color: #666; display: flex; align-items: flex-start; gap: 6px;">
                <i class="fas fa-info-circle text-info" style="margin-top: 2px;"></i>
                <span>Titik kejadian tidak mencakup keseluruhan kasus di wilayah ini.</span>
            </div>
        </div>
    </div>
</div>

<!-- Widget Koordinat & Skala Kustom -->
<div class="map-info-bar">
    <div class="info-item">
        <span class="dot-blue"></span>
        <span id="coordText">-5.xxxx, 105.xxxx</span>
        <button id="copyCoordBtn" class="btn-salin" onclick="copyCoordinates()">Salin</button>
        <span id="closeCoordBtn" class="btn-close" style="display:none;">&times;</span>
    </div>
    <div class="info-divider"></div>
    <div class="info-item">
        <i class="fas fa-mountain" style="font-size: 11px; color: #666;"></i>
        <span id="elevationText">58 mdpl</span>
    </div>
    <div class="info-divider"></div>
    <div class="info-item">
        <svg width="18" height="8" viewBox="0 0 20 8" fill="none" stroke="#444" stroke-width="2"><path d="M1 4h18M1 1v6M19 1v6"/></svg>
        <span id="scaleText">10 km</span>
    </div>
</div>
@endsection

@push('scripts')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
    // 1. Opsi-Opsi Basemap
    var osmTile = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; OpenStreetMap'
    });

    var satelliteTile = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
        maxZoom: 19,
        attribution: '&copy; Esri &mdash; Source: Esri, i-cubed, USDA, USGS, AEX, GeoEye, Getmapping, Aerogrid, IGN, IGP, UPR-EGP, and the GIS User Community'
    });

    var topoTile = L.tileLayer('https://{s}.tile.opentopomap.org/{z}/{x}/{y}.png', {
        maxZoom: 17,
        attribution: 'Map data: &copy; OpenStreetMap contributors, SRTM | Map style: &copy; OpenTopoMap'
    });

    // 2. Inisialisasi Peta
    var map = L.map('map', {
        center: [-3.4312, 104.2341],
        zoom: 12,
        zoomControl: false,
        layers: [osmTile]
    });

    L.control.zoom({
        position: 'topright'
    }).addTo(map);

    // 3. Menambahkan Kontrol Pemilihan Layer Peta di POJOK KANAN BAWAH
    var baseMaps = {
        "Peta Standar (OSM)": osmTile,
        "Peta Satelit": satelliteTile,
        "Peta Topografi / Terrain": topoTile
    };

    L.control.layers(baseMaps, null, { position: 'bottomright' }).addTo(map);

    setTimeout(function() {
        map.invalidateSize();
    }, 300);

    var geojsonLayer = null;
    var markerLayer = L.layerGroup().addTo(map);

    // 4. Kontrol Skala & Bar Koordinat (DIPERBARUI: Dinamis & Di Tengah Bawah)
    L.control.scale({ imperial: false, position: 'bottomleft' }).addTo(map);

    let elevationTimeout;
    map.on('mousemove', function(e) {
        let lat = e.latlng.lat.toFixed(5);
        let lng = e.latlng.lng.toFixed(5);
        
        // Update Teks Koordinat
        let coordTextEl = document.getElementById('coordText');
        if (coordTextEl) coordTextEl.textContent = `${lat}, ${lng}`;

        // Update Teks Elevasi secara Dinamis berdasarkan Kursor
        clearTimeout(elevationTimeout);
        elevationTimeout = setTimeout(() => {
            fetch(`https://api.open-elevation.com/api/v1/lookup?locations=${lat},${lng}`)
                .then(res => res.json())
                .then(data => {
                    if (data && data.results && data.results.length > 0) {
                        let meters = Math.round(data.results[0].elevation);
                        let elevTextEl = document.getElementById('elevationText');
                        if (elevTextEl) elevTextEl.textContent = `${meters} mdpl`;
                    }
                })
                .catch(err => {
                    let elevTextEl = document.getElementById('elevationText');
                    if (elevTextEl) elevTextEl.textContent = `- mdpl`;
                });
        }, 400);
    });

    // 5. Penentuan Warna Berdasarkan Kerawanan
    function getColor(kerawanan) {
        if (!kerawanan) return '#94a3b8';
        var k = kerawanan.toString().toLowerCase().trim();

        if (k === 'tinggi') return '#f70000';
        if (k === 'sedang') return '#fcf700';
        if (k === 'rendah') return '#00fd6a';

        return '#94a3b8';
    }

    // 6. MASTER DATA KERAWANAN LENGKAP
    const dataKerawanan = {
        "2023": {
            "Cambai": "Tinggi",
            "Prabumulih Utara": "Tinggi",
            "Prabumulih Selatan": "Rendah",
            "Prabumulih Barat": "Sedang",
            "Prabumulih Timur": "Sedang",
            "Rambang Kapak Tengah": "Sedang"
        },
        "2024": {
            "Prabumulih Timur": "Tinggi",
            "Prabumulih Barat": "Sedang",
            "Prabumulih Utara": "Sedang",
            "Cambai": "Sedang",
            "Prabumulih Selatan": "Rendah",
            "Rambang Kapak Tengah": "Rendah"
        },
        "2025": {
            "Prabumulih Timur": "Tinggi",
            "Prabumulih Barat": "Rendah",
            "Prabumulih Selatan": "Rendah",
            "Cambai": "Sedang",
            "Prabumulih Utara": "Sedang",
            "Rambang Kapak Tengah": "Sedang"
        }
    };

    function getKerawananWilayah(kecamatan, tahunSelected) {
        if (!tahunSelected || tahunSelected === '' || tahunSelected === 'Semua' || tahunSelected === 'Semua Tahun') {
            return null;
        }
        var namaBersih = kecamatan.trim();
        if (dataKerawanan[tahunSelected] && dataKerawanan[tahunSelected][namaBersih]) {
            return dataKerawanan[tahunSelected][namaBersih];
        }
        return "Sedang"; 
    }

    // Icon Marker Kriminalitas
    function getCrimeIcon(jenis) {
        if (!jenis) return 'fa-exclamation-triangle';
        var j = jenis.toString().toLowerCase();

        if (j.includes('anirat') || j.includes('penganiayaan berat')) return 'fa-skull-crossbones';
        if (j.includes('curas') || j.includes('kekerasan')) return 'fa-mask';
        if (j.includes('pengeroyokan')) return 'fa-users-slash';
        if (j.includes('penggelapan')) return 'fa-file-invoice-dollar';
        if (j.includes('aniring') || j.includes('penganiayaan ringan')) return 'fa-user-injured';
        if (j.includes('pencurian biasa')) return 'fa-user-secret';

        return 'fa-exclamation-circle';
    }

    function updatePolygonOpacity(valPercent) {
        document.getElementById('transparencyValue').textContent = valPercent + '%';
        var opacityDec = parseFloat(valPercent) / 100;

        if (geojsonLayer) {
            geojsonLayer.setStyle({
                fillOpacity: opacityDec
            });
        }
    }

    function toggleLogoInfoPopover(e) {
        if (e) e.stopPropagation();
        var popover = document.getElementById('logoInfoPopover');
        if (popover) {
            popover.classList.toggle('active');
        }
    }

    document.addEventListener('click', function(e) {
        var popover = document.getElementById('logoInfoPopover');
        var btn = document.querySelector('.logo-info-btn');
        if (popover && popover.classList.contains('active')) {
            if (!popover.contains(e.target) && !btn.contains(e.target)) {
                popover.classList.remove('active');
            }
        }
    });

    // 7. Render Marker Titik Kejadian
    function renderMarkers(features) {
        markerLayer.clearLayers();
        if (!features || features.length === 0) return;

        features.forEach(function (f) {
            var props = f.properties;
            var lat = parseFloat(props.latitude);
            var lng = parseFloat(props.longitude);

            if (!isNaN(lat) && !isNaN(lng) && lat !== 0 && lng !== 0) {
                var crimeIcon = getCrimeIcon(props.jenis);

                var customIcon = L.divIcon({
                    className: 'custom-marker-icon',
                    html: `<div style="
                        background-color: #ffffff;
                        width: 32px; 
                        height: 32px;
                        border-radius: 50%;
                        border: 2px solid #ff4d6d;
                        box-shadow: 0 4px 10px rgba(255,77,109,0.35);
                        display: flex; 
                        align-items: center; 
                        justify-content: center;
                        color: #ff4d6d; 
                        font-size: 13px;
                    "><i class="fas ${crimeIcon}"></i></div>`,
                    iconSize: [32, 32],
                    iconAnchor: [16, 16]
                });

                var marker = L.marker([lat, lng], { icon: customIcon });

                var popupContent = `
                    <div class="popup-card-custom">
                        <div class="popup-card-header">
                            <div class="popup-card-icon">
                                <i class="fas ${crimeIcon}"></i>
                            </div>
                            <div class="popup-card-title">
                                ${props.jenis || 'Kriminalitas'}
                            </div>
                        </div>
                        <div class="popup-card-body">
                            <div class="popup-info-row">
                                <span class="popup-info-label">
                                    <i class="fas fa-map-marker-alt"></i> Kecamatan
                                </span>
                                <span class="popup-info-value">${props.kecamatan || '-'}</span>
                            </div>
                            <div class="popup-info-row">
                                <span class="popup-info-label">
                                    <i class="far fa-calendar-alt"></i> Tahun
                                </span>
                                <span class="popup-info-value">${props.tahun || '-'}</span>
                            </div>
                            <div class="popup-info-row" style="margin-top: 2px;">
                                <span class="popup-info-label">
                                    <i class="fas fa-chart-line"></i> Total Kasus
                                </span>
                                <span class="popup-badge-kasus">${props.jumlah_kasus || 1} Kejadian</span>
                            </div>
                        </div>
                    </div>
                `;

                marker.bindPopup(popupContent);
                markerLayer.addLayer(marker);
            }
        });

        var toggleElement = document.getElementById('toggleMarkers');
        if (toggleElement && !toggleElement.checked) {
            map.removeLayer(markerLayer);
        } else {
            if (!map.hasLayer(markerLayer)) {
                map.addLayer(markerLayer);
            }
        }
    }

    function toggleMarkerVisibility() {
        var toggleElement = document.getElementById('toggleMarkers');
        if (toggleElement.checked) {
            if (!map.hasLayer(markerLayer)) {
                map.addLayer(markerLayer);
            }
        } else {
            if (map.hasLayer(markerLayer)) {
                map.removeLayer(markerLayer);
            }
        }
    }

    function toggleFilterPanel() {
        var panel = document.getElementById('filterPanel');
        if (panel) {
            panel.classList.toggle('collapsed');
        }
    }

    // 8. Load Data Peta & GeoJSON Wilayah
    function loadMap() {
        var tahunSelect = document.getElementById('filterTahun');
        var jenisSelect = document.getElementById('filterJenis');
        
        var tahun = tahunSelect ? tahunSelect.value : '';
        var jenis = jenisSelect ? jenisSelect.value : '';

        var sliderVal = document.getElementById('transparencySlider') ? document.getElementById('transparencySlider').value : 30;
        var currentOpacity = parseFloat(sliderVal) / 100;

        fetch(`/api/map-data?tahun=${encodeURIComponent(tahun)}&jenis=${encodeURIComponent(jenis)}`)
            .then(res => res.json())
            .then(data => {
                if (geojsonLayer) map.removeLayer(geojsonLayer);

                var polygonsData = data.polygons || { features: [] };
                var markersData = data.markers ? data.markers.features : [];

                var casesPerKecamatan = {};
                var countMarkersPerKecamatan = {}; // Menyimpan jumlah titik marker fisik per kecamatan
                
                markersData.forEach(function(m) {
                    var kec = (m.properties.kecamatan || '').trim();
                    var jumlah = parseInt(m.properties.jumlah_kasus) || 1;
                    if (kec) {
                        casesPerKecamatan[kec] = (casesPerKecamatan[kec] || 0) + jumlah;
                        countMarkersPerKecamatan[kec] = (countMarkersPerKecamatan[kec] || 0) + 1; // Menghitung jumlah titik fisik
                    }
                });

                geojsonLayer = L.geoJSON(polygonsData, {
                    style: function(f) {
                        var namaKec = (f.properties.kecamatan || '').trim();
                        var kerawanan = getKerawananWilayah(namaKec, tahun);
                        
                        var warnaFill = kerawanan ? getColor(kerawanan) : '#94a3b8';

                        return {
                            fillColor: warnaFill,
                            weight: 1.5,
                            color: '#333333',
                            fillOpacity: currentOpacity
                        };
                    },
                    onEachFeature: function(f, layer) {
                        var namaKec = (f.properties.kecamatan || '').trim();
                        var kerawanan = getKerawananWilayah(namaKec, tahun);
                        var isSemuaTahun = (!tahun || tahun === '' || tahun === 'Semua Tahun');
                        
                        var colorBadge = kerawanan ? getColor(kerawanan) : '#64748b';
                        var displayTahun = isSemuaTahun ? 'Semua Tahun' : tahun;
                        var displayKerawanan = kerawanan ? kerawanan : 'Gabungan';
                        
                        var jumlahTitikKec = countMarkersPerKecamatan[namaKec] || 0; // Jumlah titik koordinat marker di peta
                        
                        // --- BAGIAN INI YANG DISESUAIKAN AGAR DINAMIK MENGIKUTI FILTER TAHUN ---
                        var totalNilaiKasus = 0;
                        if (tahun === '2023') {
                            totalNilaiKasus = f.properties.total_2023 || 0;
                        } else if (tahun === '2024') {
                            totalNilaiKasus = f.properties.total_2024 || 0;
                        } else if (tahun === '2025') {
                            totalNilaiKasus = f.properties.total_2025 || 0;
                        } else {
                            totalNilaiKasus = f.properties.total_keseluruhan || 0;
                        }
                        // ---------------------------------------------------------------------

                        var popupContent = `
                            <div class="popup-card-custom">
                                <div class="popup-card-header">
                                    <div class="popup-card-icon icon-kecamatan">
                                        <i class="fas fa-map-marked-alt"></i>
                                    </div>
                                    <div class="popup-card-title">
                                        ${namaKec}
                                    </div>
                                </div>
                                <div class="popup-card-body">
                                    <div class="popup-info-row">
                                        <span class="popup-info-label">
                                            <i class="far fa-calendar-alt"></i> Tahun
                                        </span>
                                        <span class="popup-info-value">${displayTahun}</span>
                                    </div>
                                    <div class="popup-info-row">
                                        <span class="popup-info-label">
                                            <i class="fas fa-map-pin"></i> Titik Teridentifikasi
                                        </span>
                                        <span class="popup-info-value">${jumlahTitikKec} Titik</span>
                                    </div>
                                    <div class="popup-info-row" style="border-top: 1px dashed #eee; margin-top: 4px; padding-top: 4px;">
                                        <span class="popup-info-label" style="color: #d9534f; font-weight: bold;">
                                            <i class="fas fa-chart-bar"></i> Total Keseluruhan
                                        </span>
                                        <span class="popup-info-value" style="color: #d9534f; font-weight: bold;">${totalNilaiKasus} Kasus</span>
                                    </div>
                                    <div class="popup-info-row" style="margin-top: 2px;">
                                        <span class="popup-info-label">
                                            <i class="fas fa-shield-alt"></i> Kerawanan
                                        </span>
                                        <span class="popup-badge-kerawanan" style="background-color: ${colorBadge};">
                                            ${displayKerawanan}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        `;

                        layer.bindPopup(popupContent);
                    }
                }).addTo(map);

                renderMarkers(markersData);

                var totalKasus = 0;
                var wilayahSet = new Set();
                
                markersData.forEach(m => {
                    totalKasus += parseInt(m.properties.jumlah_kasus) || 1;
                    if(m.properties.kecamatan) wilayahSet.add(m.properties.kecamatan);
                });

                if(document.getElementById('statTotal')) document.getElementById('statTotal').textContent = totalKasus;
                if(document.getElementById('statWilayah')) document.getElementById('statWilayah').textContent = wilayahSet.size;

                if (geojsonLayer.getBounds().isValid()) {
                    map.fitBounds(geojsonLayer.getBounds());
                }
            })
            .catch(err => console.error(err));
    }
</script>
@endpush