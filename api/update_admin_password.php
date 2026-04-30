<?php
require_once 'koneksi.php';

// Hash password "123"
$hashed = password_hash("123", PASSWORD_DEFAULT);

// Update admin account
$stmt = $pdo->prepare("UPDATE users SET password = ? WHERE email = 'admin@bromo.com'");
if ($stmt->execute([$hashed])) {
    echo "✅ Password admin berhasil di-update!<br>";
    echo "Email: admin@bromo.com<br>";
    echo "Password: 123<br>";
    echo "<br>Sekarang coba login lagi.";
} else {
    echo "❌ Gagal update password admin";
}
?>
