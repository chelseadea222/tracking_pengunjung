<?php
$host     = getenv('TIDB_HOST');
$port     = getenv('TIDB_PORT');
$dbname   = getenv('TIDB_DATABASE');
$username = getenv('TIDB_USER');
$password = getenv('TIDB_PASSWORD');

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
