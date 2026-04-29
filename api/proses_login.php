<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'koneksi.php'; 

$error = ''; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') { 
    $email = trim($_POST['email'] ?? ''); 
    $password = $_POST['password'] ?? ''; 
    
    if (!$email || !$password) { 
        $error = 'Email dan password wajib diisi!'; 
    } else { 
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email"); 
        $stmt->execute([ ':email' => $email ]); 
        $user = $stmt->fetch(); // <-- Pakai $user (tanpa 's')
        
        // Pengecekan password (Semua disamakan pakai $user tanpa 's')
        if ($user && password_verify($password, $user['password'])) { 
            $_SESSION['user_id'] = $user['id']; 
            $_SESSION['nama'] = $user['nama']; 
            $_SESSION['email'] = $user['email']; 
            $_SESSION['role'] = $user['role']; 
            
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
?>