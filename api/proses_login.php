<?php
require_once 'koneksi.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!$email || !$password) {
        $error = 'Email dan password wajib diisi!';
    } else {
        $query = "SELECT * FROM users WHERE email='" . mysqli_real_escape_string($conn, $email) . "'";
        $result = mysqli_query($conn, $query);

        if ($result && mysqli_num_rows($result) > 0) {
            $user = mysqli_fetch_assoc($result); // ambil data user sebagai array asosiatif
        } else {
            $user = false; // tidak ada user ditemukan
        }

        // Pengecekan password
        if ($users && password_verify($password, $users['password'])) {
            $_SESSION['user_id'] = $users['id'];
            $_SESSION['nama'] = $users['nama'];
            $_SESSION['email'] = $users['email'];
            $_SESSION['role'] = $users['role'];

            // Pastikan session disimpan sebelum redirect
            session_write_close();

            if (isset($_SESSION['role'])) {
            if (strtolower($_SESSION['role']) === 'admin') {
                header('Location: tiket_harian.php');
                exit;
            } else {
                header('Location: tiket.php');
                exit;
        }
        }
            
        } else {
            $error = 'Email atau password salah!';
        }
    }
}
