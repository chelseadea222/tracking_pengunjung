<?php
// Session configuration untuk Vercel (gunakan memory atau disable cookies)
if (session_status() === PHP_SESSION_NONE) {
    // Untuk Vercel: gunakan save handler database atau skip session_save_path
    ini_set('session.gc_maxlifetime', 86400);
    session_start();
}

// Database credentials - GUNAKAN ENV VARIABLES DI PRODUCTION
$host     = getenv('DB_HOST') ?: 'gateway01.ap-southeast-1.prod.alicloud.tidbcloud.com';
$port     = getenv('DB_PORT') ?: '4000';
$dbname   = getenv('DB_NAME') ?: 'Tracking';
$username = getenv('DB_USER') ?: 'rYKFcN4zmjYBxLa.root';
$password = getenv('DB_PASS') ?: 'h0UwkOyj9GVT7FpW';

try {
    // SSL untuk TiDB di Vercel
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ];
    
    // Set SSL options untuk TiDB (jangan disable di production)
    if ($_ENV['ENVIRONMENT'] !== 'development') {
        $options[PDO::MYSQL_ATTR_SSL_CA] = '/etc/ssl/certs/ca-certificates.crt';
        $options[PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT] = true;
    } else {
        $options[PDO::MYSQL_ATTR_SSL_CA] = false;
        $options[PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT] = false;
    }
    
    $dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4";
    $pdo = new PDO($dsn, $username, $password, $options);
    
} catch (PDOException $e) {
    http_response_code(500);
    error_log('DB Connection Error: ' . $e->getMessage());
    die(json_encode([
        'error' => 'Koneksi database gagal',
        'message' => (getenv('DEBUG') ? $e->getMessage() : 'Hubungi administrator')
    ]));
}
?>