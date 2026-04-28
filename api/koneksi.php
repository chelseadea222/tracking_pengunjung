<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$host     = getenv('TIDB_HOST');
$port     = getenv('TIDB_PORT');
$db_name  = getenv('TIDB_DATABASE');
$username = getenv('TIDB_USER');
$password = getenv('TIDB_PASSWORD');

// Mencari lokasi sertifikat SSL bawaan server Vercel (Amazon Linux / Debian)
$ca_path = '/etc/ssl/certs/ca-certificates.crt'; 
if (!file_exists($ca_path)) {
    $ca_path = '/etc/pki/tls/certs/ca-bundle.crt'; 
}

try {
    $dsn = "mysql:host=$host;port=$port;dbname=$db_name;charset=utf8mb4";
    $pdo = new PDO($dsn, $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        // Dua baris di bawah ini WAJIB untuk TiDB Serverless
        PDO::MYSQL_ATTR_SSL_CA => $ca_path,
        PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false
    ]);
} catch (PDOException $e) {
    die("<b style='color:red'>Koneksi database gagal:</b> " . $e->getMessage());
}
?>
