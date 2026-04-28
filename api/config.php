<?php
$host     = getenv('gateway01.ap-southeast-1.prod.alicloud.tidbcloud.com');
$port     = getenv('4000');
$dbname   = getenv('Tracking');
$username = getenv('rYKFcN4zmjYBxLa.root');
$password = getenv('h0UwkOyj9GVT7FpW');

$ca_path = '/etc/ssl/certs/ca-certificates.crt'; 
if (!file_exists($ca_path)) {
    $ca_path = '/etc/pki/tls/certs/ca-bundle.crt'; 
}

try {
    $pdo = new PDO(
        "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8", 
        $username, 
        $password,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::MYSQL_ATTR_SSL_CA => $ca_path,
            PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false
        ]
    );
} catch (PDOException $e) {
    die("Koneksi gagal: " . $e->getMessage());
}
?>
