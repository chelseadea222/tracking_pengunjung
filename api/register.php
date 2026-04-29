<?php
session_start();
// Tambahkan slash setelah __DIR__
require_once __DIR__ . '/../api/proses_register.php'; 

// // Jika sudah login, jangan ke proses_register, tapi ke halaman utama
// if (isset($_SESSION['user_id'])) {
//     header("Location: tiket.php");
//     exit();
// }
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi - BromoTrack</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/register.css">
    <style>

    </style>
</head>
<body>
<div class="card-register">
    <div class="logo-title">BROMO<span>TRACK</span></div>
    <p class="subtitle">Buat akun baru untuk mencatat kunjungan</p>

    <?php if ($error): ?>
        <div class="alert alert-danger">
            <i class="bi bi-exclamation-circle me-2"></i><?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="">
        <div class="mb-3">
            <label class="form-label">Nama Lengkap</label>
            <input type="text" name="nama" class="form-control"
                placeholder="Masukkan nama lengkap"
                value="<?= htmlspecialchars($_POST['nama'] ?? '') ?>" required autocomplete="off">
        </div>
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control"
                placeholder="Masukkan email"
                value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required autocomplete="off">
        </div>
        <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control"
                placeholder="Minimal 6 karakter" required autocomplete="new-password">
        </div>
        <div class="mb-3">
            <label class="form-label">Konfirmasi Password</label>
            <input type="password" name="confirm_password" class="form-control"
                placeholder="Ulangi password" required autocomplete="new-password">
        </div>
        <button type="submit" class="btn-register">
            <i class="bi bi-person-check me-2"></i> Daftar Sekarang
        </button>
    </form>

    <hr class="divider">
    <p class="login-link">Sudah punya akun? <a href="login.php">Login di sini</a></p>
</div>
</body>
</html>