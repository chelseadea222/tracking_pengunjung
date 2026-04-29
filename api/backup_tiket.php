<?php
session_start();
require_once __DIR__ . '/../api/proses_backup.php';
if (!isset($_SESSION['nama'])) {
    header("Location: login.php");
    exit();
}

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Backup Tiket Harian</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/backup_tiket.css">
    <style>
    </style>
</head>
<body class="container mt-5">

    <h2><i class="bi bi-archive"></i> Backup Data Tiket Harian</h2>

<!-- Tabel Data -->
<div class="glass-card">
    <div class="sec-title">
        <i class="bi bi-table"></i> Data Tiket Harian per Bulan
    </div>
    <table class="table">
        <thead>
            <tr>
                <th>Nama</th>
                <th>Tanggal</th>
                <th>Tiket</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($monthlyData as $bulan => $items): ?>
                <tr>
                    <td colspan="4" style="color:var(--gold); font-weight:700; text-align:center;">
                        Bulan: <?= htmlspecialchars($bulan) ?>
                    </td>
                </tr>
                <?php foreach ($items as $item): ?>
                    <tr>
                        <td><?= htmlspecialchars($item['nama']) ?></td>
                        <td><?= htmlspecialchars($item['tanggal']) ?></td>
                        <td><?= htmlspecialchars($item['jumlah']) ?></td>
                        <td>
                            <?php if ($item['status'] === 'Lunas'): ?>
                                <span class="badge-lunas">Lunas</span>
                            <?php else: ?>
                                <span class="badge-pending">Pending</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- Tombol Backup & Navigasi (hanya di bawah tabel) -->
<div class="mt-3 d-flex flex-wrap gap-2">
    <a href="backup_tiket.php?backup=csv" class="btn-glass btn-csv">
        <i class="bi bi-file-earmark-spreadsheet"></i> Backup ke CSV
    </a>
    <a href="backup_tiket.php?backup=json" class="btn-glass btn-json">
        <i class="bi bi-calendar-check"></i> Backup Per Bulan (JSON)
    </a>
    <a href="dashboard.php" class="btn-glass btn-dashboard">
        <i class="bi bi-speedometer2"></i> Balik ke Dashboard
    </a>
    <a href="tiket_harian.php" class="btn-glass btn-tiket">
        <i class="bi bi-calendar-day"></i> Data Tiket Harian
    </a>
</div>

</body>
</html>