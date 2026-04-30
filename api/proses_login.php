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
        // ✅ Query hanya email dulu
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute([':email' => $email]);
        $users = $stmt->fetch(); // ✅ pakai $user konsisten

        if ($users && password_verify($password, $users['password'])) {
            // ✅ Set session dulu, JANGAN session_write_close sebelum redirect
            $_SESSION['user_id'] = $users['id'];
            $_SESSION['nama']    = $users['nama'];
            $_SESSION['email']   = $users['email'];
            $_SESSION['role']    = strtolower($users['role']);

            // ✅ Redirect berdasarkan role
            if (strtolower($users['role']) === 'admin') {
                header('Location: tiket-harian.php');
            } else {
                header('Location: tiket.php');
            }
            exit;
        } else {
            $error = 'Email atau password salah!';
        }
    }
}
