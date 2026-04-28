<?php
/**
 * auth_check.php
 * File helper untuk cek autentikasi dan role user
 * Include file ini di setiap halaman yang butuh proteksi
 * 
 * Cara pakai:
 * require_once 'auth_check.php';           // cek login saja
 * require_once 'auth_check.php'; cekAdmin(); // cek login + harus admin
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ─── CEK SUDAH LOGIN ───
function cekLogin() {
    if (!isset($_SESSION['user_id'])) {
        $_SESSION['pesan_error'] = 'Silakan login terlebih dahulu.';
        header('Location: login.php');
        exit;
    }
}

// ─── CEK ROLE ADMIN ───
function cekAdmin() {
    cekLogin(); // wajib login dulu
    if ($_SESSION['role'] !== 'admin') {
        $_SESSION['pesan_error'] = 'Akses ditolak. Halaman ini hanya untuk admin.';
        header('Location: tiket.php');
        exit;
    }
}

// ─── CEK ROLE USER BIASA ───
function cekUser() {
    cekLogin();
    if ($_SESSION['role'] !== 'user') {
        header('Location: tiket_harian.php');
        exit;
    }
}

// ─── AMBIL DATA SESSION ───
function getUserId() {
    return $_SESSION['user_id'] ?? null;
}

function getUsername() {
    return $_SESSION['username'] ?? $_SESSION['nama'] ?? 'Guest';
}

function getRole() {
    return $_SESSION['role'] ?? 'user';
}

function isAdmin() {
    return ($_SESSION['role'] ?? '') === 'admin';
}

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// ─── CSRF TOKEN ───
function generateCSRF() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function cekCSRF() {
    $token = $_POST['csrf_token'] ?? '';
    if (!hash_equals($_SESSION['csrf_token'] ?? '', $token)) {
        http_response_code(403);
        die('<h3 style="color:red;font-family:sans-serif;">403 - Token tidak valid. Akses ditolak.</h3>');
    }
}
?>