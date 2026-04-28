<?php
require_once 'koneksi.php';

$stmt = $pdo->query("SELECT * FROM tiket_harian ORDER BY tanggal DESC");
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

$totalPengunjung   = count($data);
$pengunjungHariIni = 0;
$totalTiket        = 0;
$totalPembayaran   = 0;
$hargaTiket        = 60000;
$today             = date("Y-m-d");

foreach ($data as $item) {
    $totalTiket      += $item['jumlah'];
    $totalPembayaran += $item['jumlah'] * $hargaTiket;
    if ($item['tanggal'] === $today) {
        $pengunjungHariIni++;
    }
}

$monthlyStats = [];
foreach ($data as $item) {
    $bulan = substr($item['tanggal'], 0, 7);
    if (!isset($monthlyStats[$bulan])) {
        $monthlyStats[$bulan] = 0;
    }
    $monthlyStats[$bulan] += $item['jumlah'];
}
