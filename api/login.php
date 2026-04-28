<?php

ob_start();

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/proses_login.php';

if (isset($_SESSION['role'])) {
    if (strtolower($_SESSION['role']) === 'admin') {
        header('Location: tiket_harian.php');
        exit;
    } else {
        header('Location: tiket.php');
        exit;
    }
}
// kalau sudah login, langsung ke tiket harian
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - BromoTrack</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/login.css">
    <style>
    </style>
</head>
<body>

<div class="card-login">
    <div class="logo-title">BROMO<span>TRACK</span></div>
    <p class="subtitle">Masuk ke sistem tracking pengunjung</p>

    <?php if ($error): ?>
        <div class="alert-custom">
            <i class="bi bi-exclamation-circle me-2"></i><?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="">
    <div class="mb-3">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control"
            placeholder="Masukkan email" required autocomplete="off">
    </div>
    <div class="mb-3">
        <label class="form-label">Password</label>
        <input type="password" name="password" class="form-control"
            placeholder="Masukkan password" required autocomplete="new-password">
    </div>
    <button type="submit" class="btn-login">
        <i class="bi bi-box-arrow-in-right me-2"></i> Masuk
    </button>
</form>


    <hr class="divider">
    <p class="reg-link">Belum punya akun? <a href="register.php">Daftar di sini</a></p>
</div>
</body>
</html>
