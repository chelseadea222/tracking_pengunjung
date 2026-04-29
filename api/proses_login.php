<?php
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

            // Pastikan session disimpan sebelum redirect
            session_write_close();

            $target_url = (strtolower($user['role']) === 'admin') ? 'tiket_harian.php' : 'tiket.php';
            header("Location: " . $target_url, true, 302);
            exit;
            
        } else {
            $error = 'Email atau password salah!';
        }
    }
}
