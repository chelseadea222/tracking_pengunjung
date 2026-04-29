<?php
require_once 'koneksi.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

$email = mysqli_real_escape_string($conn, $_POST['email']);
$query = "SELECT * FROM users WHERE email='$email'";
$result = mysqli_query($conn, $query);

if ($result && mysqli_num_rows($result) > 0) {
    $user = mysqli_fetch_assoc($result);
    if (password_verify($_POST['password'], $user['password'])) {
        $_SESSION['role'] = $user['role'];
        // redirect sesuai role
    } else {
        $error = "Password salah!";
    }
} else {
    $error = "Email tidak ditemukan!";
}
}