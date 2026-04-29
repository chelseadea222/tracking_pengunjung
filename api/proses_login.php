<?php
if (session_status() === PHP_SESSION_NONE) {
    session_save_path('/tmp');
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
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute([':email' => $email]);
        $users = $stmt->fetch();

        if ($users && password_verify($password, $users['password'])) {
            $_SESSION['user_id'] = $users['id'];
            $_SESSION['nama']    = $users['nama'];
            $_SESSION['email']   = $users['email'];
            $_SESSION['role']    = $users['role'];
            
            // Simpan session sebelum redirect
            session_write_close();

            // Pakai absolute path
            $base = 'https://' . $_SERVER['HTTP_HOST'];
            if (strtolower($users['role']) === 'admin') {
                header("Location: $base/tiket-harian.php");
            } else {
                header("Location: $base/tiket.php");
            }
            exit;
        } else {
            $error = 'Email atau password salah!';
        }
    }
}