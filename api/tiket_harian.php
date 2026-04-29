<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Gunakan slash

require_once __DIR__ . '/api_tiket.php'; 
require_once __DIR__ . '/proses_tiket_harian.php'; 


// Hapus atau perbaiki logika ini. 
// Biasanya halaman ini justru HARUS diakses jika sudah login sebagai admin.
if (!isset($_COOKIE['role']) || $_COOKIE['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - BromoTrack</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/tiket_harian.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
    </style>
</head>
<body>
<div class="wrap">

    <nav class="topbar">
        <div class="brand">BROMO<span>TRACK</span> <small style="font-size:.7rem;opacity:.4;font-family:Lato">ADMIN</small></div>
        <div class="d-flex align-items-center gap-2">
            <span class="admin-badge"><i class="bi bi-shield-fill me-1"></i>ADMIN</span>
            <a href="logout.php" class="btn-logout"><i class="bi bi-box-arrow-right"></i> Keluar</a>
        </div>
    </nav>

    <div class="content">

        <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
            <div>
                <h1 class="page-title">Dashboard <span>Admin</span></h1>
                <p style="color:rgba(255,255,255,.45);font-size:.88rem;">Selamat datang, <?= htmlspecialchars($_COOKIE['nama'] ?? 'Admin') ?></p>
            </div>
        </div>

        <?php if (isset($_GET['ok'])): ?>
            <div class="alert-ok">
                <i class="bi bi-check-circle-fill me-2"></i>
                <?= $_GET['ok']==='update' ? 'Status berhasil diperbarui!' : 'Data berhasil dihapus!' ?>
            </div>
        <?php endif; ?>

        <!-- STAT -->
        <div class="row g-3 mb-4">
            <div class="col-6 col-md-3">
                <div class="stat-card">
                    <i class="bi bi-people-fill stat-icon"></i>
                    <div class="stat-num"><?= number_format($total_pengunjung) ?></div>
                    <div class="stat-lbl">Total Pengunjung</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stat-card">
                    <i class="bi bi-person-check stat-icon"></i>
                    <div class="stat-num"><?= $total_hari_ini ?></div>
                    <div class="stat-lbl">Hari Ini</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stat-card">
                    <i class="bi bi-ticket-perforated stat-icon"></i>
                    <div class="stat-num"><?= $total_tiket ?></div>
                    <div class="stat-lbl">Total Tiket</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stat-card">
                    <i class="bi bi-person-fill stat-icon"></i>
                    <div class="stat-num"><?= $total_users ?></div>
                    <div class="stat-lbl">User Terdaftar</div>
                </div>
            </div>
        </div>

        <!-- GRAFIK LOKAL -->
        <div class="chart-card">
            <div class="chart-title">
                <i class="bi bi-bar-chart-fill" style="color:var(--orange)"></i>
                Grafik Tiket Terjual — 6 Bulan Terakhir
            </div>
            <div class="chart-sub">Berdasarkan data pemesanan sistem BromoTrack</div>
            <canvas id="chartLokal" height="80"></canvas>
        </div>

        <!-- GRAFIK BPS -->
        <div class="chart-card">
            <div class="chart-title">
                <i class="bi bi-graph-up-arrow" style="color:#3498db"></i>
                Grafik Wisatawan Kab. Probolinggo
                <?php if($bps_status === 'live'): ?>
                    <span class="badge-live">LIVE BPS</span>
                <?php else: ?>
                    <span class="badge-fallback">DATA ESTIMASI</span>
                <?php endif; ?>
            </div>
            <div class="chart-sub">
                Sumber: Badan Pusat Statistik Kabupaten Probolinggo (probolinggokab.bps.go.id)
            </div>
            <canvas id="chartBPS" height="80"></canvas>
        </div>

        <!-- BACKUP -->
        <a href="backup_tiket.php" class="btn btn-warning mb-4">
            <i class="bi bi-download me-2"></i> Backup Tiket (CSV)
        </a>

        <!-- TABLE -->
        <div class="sec-title">
            <i class="bi bi-table" style="color:var(--orange)"></i> Semua Data Kunjungan
        </div>
        <div class="table-card">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nama</th>
                            <th>WhatsApp</th>
                            <th>Tanggal</th>
                            <th>Paket</th>
                            <th>Jumlah</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if (empty($semua)): ?>
                        <tr><td colspan="8" style="text-align:center;color:rgba(255,255,255,.35);padding:3rem;">Belum ada data.</td></tr>
                    <?php else: ?>
                        <?php foreach ($semua as $i => $row): ?>
                        <tr>
                            <td><?= $i + 1 ?></td>
                            <td><?= htmlspecialchars($row['nama'] ?? '-') ?></td>
                            <td style="font-size:.82rem;color:rgba(255,255,255,.5)"><?= htmlspecialchars($row['whatsapp'] ?? '-') ?></td>
                            <td><?= isset($row['tanggal']) ? date('d M Y', strtotime($row['tanggal'])) : '-' ?></td>
                            <td><?= htmlspecialchars($row['paket'] ?? '-') ?></td>
                            <td><?= $row['jumlah'] ?? 0 ?> org</td>
                            <td>
                                <form method="POST" style="display:inline">
                                    <input type="hidden" name="aksi" value="update_status">
                                    <input type="hidden" name="tiket_id" value="<?= $row['id'] ?>">
                                    <select name="status" class="status-select" onchange="this.form.submit()">
                                        <option value="Lunas"   <?= ($row['status'] ?? '') === 'Lunas'   ? 'selected' : '' ?>>Lunas</option>
                                        <option value="Pending" <?= ($row['status'] ?? 'Pending') === 'Pending' ? 'selected' : '' ?>>Pending</option>
                                    </select>
                                </form>
                            </td>
                            <td>
                                <form method="POST" style="display:inline"
                                    onsubmit="return confirm('Yakin hapus data ini?')">
                                    <input type="hidden" name="aksi" value="hapus">
                                    <input type="hidden" name="hapus_id" value="<?= $row['id'] ?>">
                                    <button type="submit" class="btn-hapus">
                                        <i class="bi bi-trash3"></i> Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>

<script>
// ─── GRAFIK LOKAL ───
new Chart(document.getElementById('chartLokal').getContext('2d'), {
    type: 'line',
    data: {
        labels: <?= $g_labels ?>,
        datasets: [{
            label: 'Tiket Terjual',
            data: <?= $g_data ?>,
            borderColor: '#E8621A',
            backgroundColor: 'rgba(232,98,26,0.15)',
            borderWidth: 2.5,
            fill: true,
            tension: 0.4,
            pointBackgroundColor: '#E8621A',
            pointBorderColor: '#fff',
            pointRadius: 5,
            pointHoverRadius: 8
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { labels: { color: 'rgba(255,255,255,0.6)' } },
            tooltip: {
                callbacks: {
                    label: ctx => ' ' + ctx.parsed.y + ' tiket'
                }
            }
        },
        scales: {
            x: { ticks: { color: 'rgba(255,255,255,0.5)' }, grid: { color: 'rgba(255,255,255,0.05)' } },
            y: { ticks: { color: 'rgba(255,255,255,0.5)' }, grid: { color: 'rgba(255,255,255,0.05)' }, beginAtZero: true }
        }
    }
});

// ─── GRAFIK BPS ───
new Chart(document.getElementById('chartBPS').getContext('2d'), {
    type: 'bar',
    data: {
        labels: <?= json_encode($bps_labels) ?>,
        datasets: [{
            label: 'Wisatawan Kab. Probolinggo (BPS)',
            data: <?= json_encode($bps_values) ?>,
            backgroundColor: [
                'rgba(52,152,219,0.7)',
                'rgba(52,152,219,0.7)',
                'rgba(231,76,60,0.7)',   // merah = tahun covid
                'rgba(231,76,60,0.7)',   // merah = tahun covid
                'rgba(46,204,113,0.7)',  // hijau = pemulihan
                'rgba(46,204,113,0.7)',
                'rgba(52,152,219,0.7)'
            ],
            borderColor: 'rgba(255,255,255,0.2)',
            borderWidth: 1,
            borderRadius: 6
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { labels: { color: 'rgba(255,255,255,0.6)' } },
            tooltip: {
                callbacks: {
                    label: ctx => ' ' + ctx.parsed.y.toLocaleString('id-ID') + ' wisatawan'
                }
            }
        },
        scales: {
            x: { ticks: { color: 'rgba(255,255,255,0.5)' }, grid: { color: 'rgba(255,255,255,0.05)' } },
            y: {
                ticks: {
                    color: 'rgba(255,255,255,0.5)',
                    callback: val => val >= 1000 ? (val/1000).toFixed(0) + ' rb' : val
                },
                grid: { color: 'rgba(255,255,255,0.05)' },
                beginAtZero: true
            }
        }
    }
});
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
