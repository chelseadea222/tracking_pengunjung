<?php
require_once __DIR__ . '/koneksi.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!$email || !$password) {
        $error = 'Email dan password wajib diisi!';
    } else {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute([':email' => $email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $expire = time() + 3600;
            setcookie('u_id',   $user['id'],              $expire, '/', '', true, true);
            setcookie('u_nama', $user['nama'],             $expire, '/', '', true, false);
            setcookie('u_role', strtolower($user['role']), $expire, '/', '', true, false);

            $base = 'https://' . $_SERVER['HTTP_HOST'];
            if (strtolower($user['role']) === 'admin') {
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