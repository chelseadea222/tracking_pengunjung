<?php
require_once 'koneksi.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!$email || !$password) {
        $error = 'Email dan password wajib diisi!';
    } else {
        // ✅ Query hanya email dulu
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute([':email' => $email]);
        $user = $stmt->fetch(); // ✅ pakai $user konsisten

        if ($user && password_verify($password, $user['password'])) {
            // ✅ Set session dulu, JANGAN session_write_close sebelum redirect
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['nama']    = $user['nama'];
            $_SESSION['email']   = $user['email'];
            $_SESSION['role']    = $user['role'];

            // ✅ Redirect berdasarkan role
            if (strtolower($user['role']) === 'admin') {
                header('Location: /tiket-harian');
            } else {
                header('Location: /tiket');
            }
            exit;
        } else {
            $error = 'Email atau password salah!';
        }
    }
}