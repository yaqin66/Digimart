<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Tidak Ditemukan — DigiMart</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
<div class="auth-wrapper">
    <div class="auth-card" style="text-align: center; padding: 60px 40px;">
        <div style="font-size: 5rem; font-weight: 800; color: transparent; background: var(--brand-gradient); -webkit-background-clip: text; line-height: 1; margin-bottom: 20px;">
            404
        </div>
        <h1 style="font-size: 1.5rem; font-weight: 700; color: var(--text-primary); margin-bottom: 12px;">
            Halaman Tidak Ditemukan
        </h1>
        <p style="color: var(--text-muted); font-size: 0.95rem; margin-bottom: 30px; line-height: 1.6;">
            Maaf, halaman yang Anda tuju mungkin telah dihapus, diubah namanya, atau tidak pernah ada sejak awal.
        </p>
        
        <a href="{{ route('dashboard') }}" class="btn btn-primary" style="padding: 12px 24px;">
            <i class="fa-solid fa-house"></i> Kembali ke Beranda
        </a>
    </div>
</div>
</body>
</html>
