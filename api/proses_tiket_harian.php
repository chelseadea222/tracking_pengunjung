<?php
require_once 'koneksi.php';

if (!isset($_COOKIE['u_id'])) {
    header('Location: /login.php');
    exit;
}

if ($_COOKIE['u_role'] !== 'admin') {
    header('Location: /tiket.php');
    exit;
}

// ─── UPDATE STATUS ───
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['aksi'] ?? '') === 'update_status') {
    $id     = (int)$_POST['tiket_id'];
    $status = $_POST['status'];
    if (in_array($status, ['Lunas', 'Pending'])) {
        $pdo->prepare("UPDATE tiket_harian SET status = ? WHERE id = ?")->execute([$status, $id]);
    }
    header('Location: /tiket-harian.php?ok=update');
    exit;
}

// ─── HAPUS ───
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['aksi'] ?? '') === 'hapus') {
    $id = (int)$_POST['hapus_id'];
    $pdo->prepare("DELETE FROM tiket_harian WHERE id = ?")->execute([$id]);
    header('Location: /tiket-harian.php?ok=hapus');
    exit;
}

// ─── PASTIKAN KOLOM status ADA ───
try {
    $pdo->query("ALTER TABLE tiket_harian ADD COLUMN IF NOT EXISTS status VARCHAR(20) DEFAULT 'Pending'");
} catch (Exception $e) {}

// ─── STATISTIK ───
$total_pengunjung = $pdo->query("SELECT COALESCE(SUM(jumlah),0) FROM tiket_harian")->fetchColumn();
$total_hari_ini   = $pdo->query("SELECT COALESCE(SUM(jumlah),0) FROM tiket_harian WHERE tanggal = CURDATE()")->fetchColumn();
$total_tiket      = $pdo->query("SELECT COUNT(*) FROM tiket_harian")->fetchColumn();
$total_users      = $pdo->query("SELECT COUNT(*) FROM users WHERE role='user'")->fetchColumn();

// ─── SEMUA DATA ───
$semua = $pdo->query("SELECT * FROM tiket_harian ORDER BY tanggal DESC")->fetchAll();

// ─── DATA GRAFIK LOKAL (6 bulan terakhir) ───
// ─── DATA GRAFIK LOKAL (6 bulan terakhir) ───
$grafik = $pdo->query("
    SELECT DATE_FORMAT(tanggal,'%b %Y') as bulan, 
           DATE_FORMAT(tanggal,'%Y-%m') as bulan_sort,
           SUM(jumlah) as total
    FROM tiket_harian
    WHERE tanggal >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)
    GROUP BY DATE_FORMAT(tanggal,'%Y-%m'), DATE_FORMAT(tanggal,'%b %Y')
    ORDER BY bulan_sort ASC
")->fetchAll();
$g_labels = json_encode(array_column($grafik, 'bulan'));
$g_data   = json_encode(array_column($grafik, 'total'));