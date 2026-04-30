<?php
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
            setcookie('user_id', $users['id'], time() + 3600, '/', '', true, true);
            setcookie('nama', $users['nama'], time() + 3600, '/', '', true, false);
            setcookie('email', $users['email'], time() + 3600, '/', '', true, false);
            setcookie('role', strtolower($users['role']), time() + 3600, '/', '', true, false);

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