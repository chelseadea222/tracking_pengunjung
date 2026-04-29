<?php
session_start();
require_once 'proses_dashboard.php'; // Penting agar data angka muncul
require_once 'api_dashboard.php';    // Penting agar data grafik muncul
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin Tracking Pengunjung Bromo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/dashboard.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark shadow-sm">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Bromo<span>Dashboard</span></a>
            <div class="d-flex align-items-center ms-auto">
                <a href="login.php" class="btn btn-light me-2">
                    <i class="bi bi-box-arrow-in-right"></i> Masuk
                </a>
                <a href="register.php" class="btn btn-warning">
                    <i class="bi bi-person-plus"></i> Daftar
                </a>
            </div>
        </div>
    </nav>

    <div class="container-fluid mt-4">

        <!-- Statistik -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card text-center shadow">
                    <div class="card-body">
                        <i class="bi bi-people fs-2 text-primary"></i>
                        <h6>Total Pemesanan</h6>
                        <h4><?= $totalPengunjung ?></h4>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center shadow">
                    <div class="card-body">
                        <i class="bi bi-person-check fs-2 text-success"></i>
                        <h6>Pemesanan Hari Ini</h6>
                        <h4><?= $pengunjungHariIni ?></h4>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center shadow">
                    <div class="card-body">
                        <i class="bi bi-cash-stack fs-2 text-warning"></i>
                        <h6>Total Pembayaran</h6>
                        <h4>Rp <?= number_format($totalPembayaran, 0, ',', '.') ?></h4>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center shadow">
                    <div class="card-body">
                        <i class="bi bi-ticket-perforated fs-2 text-danger"></i>
                        <h6>Tiket Terjual</h6>
                        <h4><?= $totalTiket ?></h4>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabel -->
        <div class="card mb-4 shadow">
            <div class="card-body">
                <h5 class="mb-3"><i class="bi bi-table me-2 text-warning"></i>Data Pemesanan Terbaru</h5>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>WhatsApp</th>
                                <th>Tanggal</th>
                                <th>Meeting Point</th>
                                <th>Paket</th>
                                <th>Peserta</th>
                                <th>Estimasi Bayar</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($data)): ?>
                                <tr>
                                    <td colspan="8" class="text-center opacity-50">Belum ada data pemesanan.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach (array_slice($data, 0, 10) as $i => $item): ?>
                                    <tr>
                                        <td><?= $i + 1 ?></td>
                                        <td><?= htmlspecialchars($item['nama'] ?? '-') ?></td>
                                        <td><?= htmlspecialchars($item['whatsapp'] ?? '-') ?></td>
                                        <td><?= htmlspecialchars($item['tanggal'] ?? '-') ?></td>
                                        <td><?= htmlspecialchars($item['titik_kumpul'] ?? '-') ?></td>
                                        <td><span class="badge-paket"><?= htmlspecialchars($item['paket'] ?? '-') ?></span></td>
                                        <td><?= htmlspecialchars($item['jumlah'] ?? 0) ?> orang</td>
                                        <td>Rp <?= number_format(($item['jumlah'] ?? 0) * $hargaTiket, 0, ',', '.') ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Grafik Tiket Harian -->
        <div class="card mb-4 shadow">
            <div class="card-body">
                <h5 class="mb-1">
                    <i class="bi bi-bar-chart-line me-2 text-warning"></i>Grafik Tiket Terjual Bulanan
                </h5>
                <small class="text-white-50 d-block mb-3">Berdasarkan data pemesanan sistem</small>
                <canvas id="chartLokal" height="80"></canvas>
            </div>
        </div>

        <!-- Grafik BPS -->
        <div class="card mb-4 shadow">
            <div class="card-body">
                <h5 class="mb-1">
                    <i class="bi bi-graph-up me-2 text-info"></i>Grafik Wisatawan Kab. Probolinggo
                    <span class="badge-bps ms-2">Sumber: BPS</span>
                </h5>
                <small class="text-white-50 d-block mb-3">
                    <?= empty($bps_raw) ? 'Data estimasi (API BPS tidak terhubung)' : 'Data resmi Badan Pusat Statistik' ?>
                </small>
                <canvas id="chartBPS" height="80"></canvas>
            </div>
        </div>

    </div>

    <script>
    // ─── GRAFIK TIKET LOKAL ───
    new Chart(document.getElementById('chartLokal').getContext('2d'), {
        type: 'line',
        data: {
            labels: <?= json_encode(array_keys($monthlyStats)) ?>,
            datasets: [{
                label: 'Tiket Terjual',
                data: <?= json_encode(array_values($monthlyStats)) ?>,
                borderColor: '#E8621A',
                backgroundColor: 'rgba(232,98,26,.2)',
                pointBackgroundColor: '#D4A017',
                pointBorderColor: '#fff',
                pointRadius: 5,
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { labels: { color: '#fff' } } },
            scales: {
                x: { ticks: { color: '#fff' }, grid: { color: 'rgba(255,255,255,.1)' } },
                y: { ticks: { color: '#fff' }, grid: { color: 'rgba(255,255,255,.1)' }, beginAtZero: true }
            }
        }
    });

    // ─── GRAFIK BPS ───
    new Chart(document.getElementById('chartBPS').getContext('2d'), {
        type: 'bar',
        data: {
            labels: <?= json_encode($bps_labels) ?>,
            datasets: [{
                label: 'Jumlah Wisatawan (BPS Probolinggo)',
                data: <?= json_encode($bps_values) ?>,
                borderColor: '#3498db',
                backgroundColor: 'rgba(52,152,219,.3)',
                borderWidth: 2,
                borderRadius: 6
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { labels: { color: '#fff' } } },
            scales: {
                x: { ticks: { color: '#fff' }, grid: { color: 'rgba(255,255,255,.1)' } },
                y: {
                    ticks: {
                        color: '#fff',
                        callback: val => val >= 1000 ? (val/1000).toFixed(0) + 'rb' : val
                    },
                    grid: { color: 'rgba(255,255,255,.1)' },
                    beginAtZero: true
                }
            }
        }
    });
    </script>

</body>
</html>