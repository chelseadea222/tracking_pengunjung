<?php
// Ganti session dengan cara ini untuk Vercel
if (session_status() === PHP_SESSION_NONE) {
    session_save_path('/tmp');
    session_start();
}

$host     = 'gateway01.ap-southeast-1.prod.alicloud.tidbcloud.com';
$port     = '4000';
$dbname   = 'Tracking';
$username = 'rYKFcN4zmjYBxLa.root';
$password = 'h0UwkOyj9GVT7FpW';

try {
    $dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4";
    $pdo = new PDO($dsn, $username, $password, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::MYSQL_ATTR_SSL_CA       => false,
        PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    die(json_encode([
        'error' => 'Koneksi database gagal',
        'message' => $e->getMessage()
    ]));
}
?>