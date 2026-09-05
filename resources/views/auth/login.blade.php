@extends('layouts.app')

@section('title', 'Login Admin')

@push('styles')
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap">
<style>
    * { font-family: 'Plus Jakarta Sans', sans-serif; }

    body {
        min-height: 100vh;
        margin: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        overflow: hidden;
        background: #0f0f1a;
        padding: 20px;
    }

    /* ===== Gradient mesh animasi di background ===== */
    .bg-mesh {
        position: fixed;
        inset: 0;
        z-index: 0;
        background:
            radial-gradient(circle at 15% 20%, rgba(255, 77, 109, 0.35), transparent 45%),
            radial-gradient(circle at 85% 15%, rgba(99, 102, 241, 0.3), transparent 45%),
            radial-gradient(circle at 50% 90%, rgba(255, 122, 138, 0.25), transparent 50%),
            linear-gradient(160deg, #12121f 0%, #1a1a2e 60%, #0f0f1a 100%);
    }

    .bg-mesh::before {
        content: '';
        position: absolute;
        inset: 0;
        background-image: radial-gradient(rgba(255,255,255,0.06) 1px, transparent 1px);
        background-size: 26px 26px;
    }

    .blob {
        position: absolute;
        border-radius: 50%;
        filter: blur(60px);
        opacity: 0.5;
        animation: float 10s ease-in-out infinite;
    }
    .blob-1 {
        width: 280px; height: 280px;
        background: #ff4d6d;
        top: -60px; left: -60px;
        animation-delay: 0s;
    }
    .blob-2 {
        width: 320px; height: 320px;
        background: #6366f1;
        bottom: -80px; right: -60px;
        animation-delay: 2s;
    }
    @keyframes float {
        0%, 100% { transform: translate(0, 0) scale(1); }
        50% { transform: translate(20px, -25px) scale(1.08); }
    }

    /* ===== Kartu login glassmorphism ===== */
    .login-card {
        position: relative;
        z-index: 10;
        width: 100%;
        max-width: 400px;
        background: rgba(255, 255, 255, 0.97);
        backdrop-filter: blur(24px);
        -webkit-backdrop-filter: blur(24px);
        border: 1px solid rgba(255, 255, 255, 0.4);
        border-radius: 24px;
        padding: 2.75rem 2.25rem;
        box-shadow:
            0 25px 60px rgba(0, 0, 0, 0.45),
            0 0 0 1px rgba(255, 255, 255, 0.06) inset;
        animation: cardIn 0.6s cubic-bezier(0.16, 1, 0.3, 1);
    }

    @keyframes cardIn {
        from { opacity: 0; transform: translateY(24px) scale(0.97); }
        to { opacity: 1; transform: translateY(0) scale(1); }
    }

    .brand-icon {
        width: 64px;
        height: 64px;
        margin: 0 auto 1.1rem;
        background: linear-gradient(135deg, #ff4d6d, #ff7a8a);
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: 1.6rem;
        box-shadow: 0 10px 28px rgba(255, 77, 109, 0.45);
        position: relative;
    }

    .brand-icon::after {
        content: '';
        position: absolute;
        inset: -6px;
        border-radius: 24px;
        border: 1.5px solid rgba(255, 77, 109, 0.3);
        animation: ring 2.4s ease-out infinite;
    }

    @keyframes ring {
        0% { transform: scale(1); opacity: 0.6; }
        100% { transform: scale(1.25); opacity: 0; }
    }

    .login-title {
        text-align: center;
        color: #0f172a;
        font-weight: 800;
        font-size: 1.4rem;
        letter-spacing: -0.3px;
        margin-bottom: 0.2rem;
    }

    .login-subtitle {
        text-align: center;
        color: #94a3b8;
        font-size: 0.85rem;
        font-weight: 600;
        margin-bottom: 2rem;
    }

    .form-group { margin-bottom: 1.2rem; }

    .form-group label {
        display: block;
        font-size: 0.75rem;
        font-weight: 700;
        color: #475569;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 0.5rem;
    }

    .input-wrapper {
        position: relative;
        display: flex;
        align-items: center;
    }

    .input-wrapper i {
        position: absolute;
        left: 16px;
        color: #cbd5e1;
        font-size: 0.95rem;
        pointer-events: none;
        transition: color 0.2s ease;
    }

    .input-wrapper input {
        width: 100%;
        padding: 0.85rem 1rem 0.85rem 46px;
        border-radius: 13px;
        border: 1.5px solid #e5e7eb;
        background: #f8fafc;
        font-size: 0.9rem;
        color: #0f172a;
        font-weight: 600;
        outline: none;
        transition: all 0.2s ease;
    }

    .input-wrapper input::placeholder { color: #b6bdc9; font-weight: 500; }

    .input-wrapper input:focus {
        background: #ffffff;
        border-color: #ff4d6d;
        box-shadow: 0 0 0 4px rgba(255, 77, 109, 0.12);
    }

    .input-wrapper input:focus ~ i { color: #ff4d6d; }

    .btn-login {
        width: 100%;
        padding: 0.9rem;
        background: linear-gradient(135deg, #ff4d6d, #ff7a8a);
        color: #ffffff;
        border: none;
        border-radius: 13px;
        font-size: 0.92rem;
        font-weight: 700;
        letter-spacing: 0.2px;
        cursor: pointer;
        box-shadow: 0 10px 24px rgba(255, 77, 109, 0.35);
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        margin-top: 1.6rem;
    }

    .btn-login:hover {
        transform: translateY(-2px);
        box-shadow: 0 14px 30px rgba(255, 77, 109, 0.45);
    }

    .btn-login:active { transform: translateY(0); }

    .back-link {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        margin-top: 1.5rem;
        color: #94a3b8;
        text-decoration: none;
        font-size: 0.82rem;
        font-weight: 600;
        transition: color 0.2s ease;
    }

    .back-link:hover { color: #ff4d6d; }

    .alert-modern {
        background: rgba(255, 77, 109, 0.08);
        border: 1px solid rgba(255, 77, 109, 0.25);
        color: #d6304f;
        border-radius: 12px;
        padding: 0.7rem 1rem;
        font-size: 0.82rem;
        font-weight: 600;
        margin-bottom: 1.2rem;
        display: flex;
        align-items: center;
        gap: 8px;
    }
</style>
@endpush

@section('content')
<div class="bg-mesh">
    <div class="blob blob-1"></div>
    <div class="blob blob-2"></div>
</div>

<div class="login-card">
    <div class="text-center">
        <div class="brand-icon">
            <i class="fas fa-map-marked-alt"></i>
        </div>
        <div class="login-title">WebGIS Kriminalitas</div>
        <div class="login-subtitle">Masuk sebagai Administrator</div>
    </div>

    @if($errors->any())
        <div class="alert-modern">
            <i class="fas fa-circle-exclamation"></i>
            <span>{{ $errors->first() }}</span>
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf
        <div class="form-group">
            <label>Email</label>
            <div class="input-wrapper">
                <input type="email" name="email" placeholder="admin@webgis.com" value="{{ old('email') }}" required autofocus>
                <i class="fas fa-envelope"></i>
            </div>
        </div>
        <div class="form-group">
            <label>Password</label>
            <div class="input-wrapper">
                <input type="password" name="password" placeholder="••••••••" required>
                <i class="fas fa-lock"></i>
            </div>
        </div>
        <button type="submit" class="btn-login">
            <i class="fas fa-arrow-right-to-bracket"></i> Masuk
        </button>
    </form>

    <a href="{{ route('map.index') }}" class="back-link">
        <i class="fas fa-arrow-left"></i> Kembali ke Peta
    </a>
</div>
@endsection