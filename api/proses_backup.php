<?php
require_once 'koneksi.php';

$stmt = $pdo->query("SELECT * FROM tiket_harian ORDER BY tanggal ASC");
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

$monthlyData = [];
foreach ($rows as $row) {
    $bulan = date('F Y', strtotime($row['tanggal']));
    $monthlyData[$bulan][] = $row;
}

// export JSON
if (isset($_GET['backup']) && $_GET['backup'] === 'json') {
    header('Content-Type: application/json');
    header('Content-Disposition: attachment; filename="backup_tiket_perbulan.json"');
    echo json_encode($monthlyData, JSON_PRETTY_PRINT);
    exit;
}

// export CSV
if (isset($_GET['backup']) && $_GET['backup'] === 'csv') {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="backup_tiket_perbulan.csv"');
    $output = fopen("php://output", "w");
    fputcsv($output, ["Bulan", "Nama", "Tanggal", "Tiket", "Status"]);
    foreach ($monthlyData as $bulan => $items) {
        foreach ($items as $item) {
            fputcsv($output, [$bulan, $item['nama'], $item['tanggal'], $item['jumlah'], $item['status']]);
        }
    }
    fclose($output);
    exit;
}