<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'koneksi.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!$email || !$password) {
        $error = 'Email dan password wajib diisi!';
    } else {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        // Pengecekan password
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['nama'] = $user['nama'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = $user['role'];

            $target_url = (strtolower($user['role']) === 'admin') ? 'tiket_harian.php' : 'tiket.php';
            
            if (!headers_sent()) {
                header("Location: " . $target_url);
            }
            // Jika PHP Header gagal, Javascript yang akan mengambil alih redirect-nya
            echo "<script>window.location.href='" . $target_url . "';</script>";
            exit;
            
        } else {
            $error = 'Email atau password salah!';
        }
    }
}
