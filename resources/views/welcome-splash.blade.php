<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selamat Datang | Peta Kriminalitas Kota Prabumulih</title>
    <!-- Bootstrap CSS & FontAwesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap">
    <style>
        :root {
            --btn-gradient: linear-gradient(135deg, #a84232 0%, #c85a47 100%);
        }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f4f0ea;
            background-image: 
                radial-gradient(at 15% 20%, rgba(163, 133, 106, 0.15) 0px, transparent 45%),
                radial-gradient(at 85% 85%, rgba(112, 128, 104, 0.12) 0px, transparent 45%),
                radial-gradient(at 50% 50%, rgba(194, 120, 97, 0.1) 0px, transparent 60%),
                radial-gradient(#d4cebc 1.5px, transparent 1.5px);
            background-size: 100% 100%, 100% 100%, 100% 100%, 24px 24px;
            height: 100vh;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }
        .splash-card {
            background: rgba(255, 255, 255, 0.96);
            backdrop-filter: blur(20px);
            padding: 3.5rem 2.5rem;
            border-radius: 28px;
            box-shadow: 0 20px 45px -15px rgba(110, 95, 78, 0.15);
            text-align: center;
            max-width: 580px;
            width: 90%;
            border: 1px solid rgba(212, 206, 188, 0.6);
            animation: fadeInUp 0.8s cubic-bezier(0.16, 1, 0.3, 1);
        }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px) scale(0.97); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }
        .logo-container {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.75rem;
        }
        .logo-container img {
            height: 110px;
            object-fit: contain;
            filter: drop-shadow(0 8px 16px rgba(110, 95, 78, 0.12));
            transition: transform 0.3s ease;
        }
        .logo-container img:hover {
            transform: scale(1.05);
        }
        .splash-title {
            font-weight: 800;
            color: #382e2b;
            font-size: 1.85rem;
            letter-spacing: -0.025em;
            margin-bottom: 0.75rem;
        }
        .splash-subtitle {
            color: #786a63;
            font-size: 0.95rem;
            line-height: 1.6;
            margin-bottom: 2.25rem;
            font-weight: 500;
        }
        .badge-info-custom {
            display: inline-block;
            background: rgba(112, 128, 104, 0.12);
            color: #55664d;
            font-weight: 600;
            font-size: 0.75rem;
            padding: 0.35rem 0.85rem;
            border-radius: 50px;
            margin-bottom: 1rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        .btn-masuk {
            background: var(--btn-gradient);
            border: none;
            color: #fff;
            padding: 0.85rem 2.75rem;
            font-weight: 700;
            font-size: 1rem;
            border-radius: 14px;
            box-shadow: 0 10px 25px -5px rgba(168, 66, 50, 0.35);
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }
        .btn-masuk:hover {
            color: #fff;
            transform: translateY(-3px);
            box-shadow: 0 15px 30px -5px rgba(168, 66, 50, 0.5);
        }
        .btn-masuk i {
            transition: transform 0.2s ease;
        }
        .btn-masuk:hover i {
            transform: translateX(4px);
        }
        .countdown-text {
            display: block;
            margin-top: 1rem;
            font-size: 0.85rem;
            color: #8c7e75;
        }
    </style>
</head>
<body>

    <div class="splash-card">
        <!-- Badge Kecil -->
        <div class="badge-info-custom">
            <i class="fas fa-shield-alt me-1"></i> Sistem Informasi Geografis
        </div>

        <!-- Logo Kota Prabumulih -->
        <div class="logo-container">
            <img src="{{ asset('images/logo-prabumulih.png') }}" alt="Logo Kota Prabumulih" onerror="this.src='https://via.placeholder.com/110?text=Logo+Prabumulih'">
        </div>

        <h1 class="splash-title">Peta Kriminalitas Prabumulih</h1>
        <p class="splash-subtitle">"Peta Cerdas Berbasis Web untuk Menjelajahi Pola dan Tingkat Kerawanan Kriminalitas di Kota Prabumulih"</p>

        <!-- Tombol Menuju Peta Utama -->
        <a href="{{ route('map.index') }}" class="btn btn-masuk">
            <span>Masuk ke Peta</span> 
            <i class="fas fa-arrow-right"></i>
        </a>

        <!-- Keterangan Waktu Otomatis -->
        <small class="countdown-text">Dialihkan otomatis ke peta dalam <span id="countdown">3</span> detik...</small>
    </div>

    <!-- Script Pengalihan Otomatis -->
    <script>
        let timeLeft = 3; // Atur durasi tampil dalam detik (misal: 3 detik)
        const countdownEl = document.getElementById('countdown');
        const targetUrl = "{{ route('map.index') }}";

        const timer = setInterval(() => {
            timeLeft--;
            countdownEl.textContent = timeLeft;
            if (timeLeft <= 0) {
                clearInterval(timer);
                window.location.href = targetUrl;
            }
        }, 1000);
    </script>

</body>
</html>