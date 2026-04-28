<?php
session_start();
require_once __DIR__ . '/../api/proses_tiket.php';
// kalau sudah login, langsung ke tiket harian
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Wisata Gunung Bromo</title>                               
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/tiket.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <style>
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg py-3">
        <div class="container">
            <a class="navbar-brand fw-bold text-light fs-4" href="#">
                <i class="bi bi-geo-alt-fill text-warning me-2"></i>Bromo<span class="text-warning">Ticket</span>
            </a>
            <div class="d-flex align-items-center">
                <span class="badge bg-warning text-dark rounded-pill px-3 py-2 me-3">
                    <i class="bi bi-radar me-1"></i>Live ML API Sync
                </span>
                <?php if(isset($_SESSION['nama'])): ?>
                    <span class="text-light small opacity-75">
                        <i class="bi bi-person-circle me-1"></i><?= htmlspecialchars($_SESSION['nama']) ?>
                    </span>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <div class="container mt-4 fade-in">

        <div class="row mb-4 text-center">
            <div class="col-12">
                <h2 class="fw-bold mb-1">Pemesanan Tiket & Jeep Bromo</h2>
                <p class="text-light opacity-75">Pantau cuaca, lihat lokasi, dan pesan tiket petualanganmu sekarang.</p>
            </div>
        </div>

        <?php if($pesan_sukses): ?>
            <div class="alert alert-success bg-success text-white border-0 rounded-4 text-center mb-4 fade-in">
                <i class="bi bi-check-circle-fill fs-4 d-block mb-2"></i>
                <strong>Pemesanan Berhasil!</strong> Tiket Jeep Bromo Anda telah dicatat di sistem.
            </div>
        <?php endif; ?>

        <?php if($pesan_error): ?>
            <div class="alert alert-danger border-0 rounded-4 text-center mb-4 fade-in">
                <i class="bi bi-x-circle-fill fs-4 d-block mb-2"></i>
                <?= htmlspecialchars($pesan_error) ?>
            </div>
        <?php endif; ?>

        <div class="row g-4">
            <div class="col-md-7 col-lg-8">
                <div class="glass-card h-100">
                    <h4 class="fw-bold mb-4 border-bottom border-secondary pb-2">
                        <i class="bi bi-ticket-perforated me-2 text-warning"></i>Form Pemesanan Tour
                    </h4>
                    
                    <form method="POST" action="">
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small text-light">Nama Pemesan</label>
                                <input type="text" name="nama" class="form-control" placeholder="Nama Lengkap"
                                    value="<?= isset($_SESSION['nama']) ? htmlspecialchars($_SESSION['nama']) : '' ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small text-light">Nomor WhatsApp</label>
                                <input type="text" name="whatsapp" class="form-control" placeholder="0812xxxxxx" required>
                            </div>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small text-light">Tanggal Keberangkatan</label>
                                <input type="date" name="tanggal" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small text-light">Titik Kumpul (Meeting Point)</label>
                                <select name="titik_kumpul" class="form-select" required>
                                    <option value="" disabled selected>-- Pilih Lokasi --</option>
                                    <option value="Malang">Stasiun/Bandara Malang</option>
                                    <option value="Probolinggo">Terminal/Stasiun Probolinggo</option>
                                    <option value="Pasuruan">Pasuruan Kota</option>
                                </select>
                            </div>
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small text-light">Paket Jeep</label>
                                <select name="paket" class="form-select" required>
                                    <option value="" disabled selected>-- Pilih Paket --</option>
                                    <option value="Sunrise Penanjakan">Paket 1: Sunrise Penanjakan + Kawah</option>
                                    <option value="Lengkap Bromo">Paket 2: Penanjakan, Kawah, Pasir Berbisik, Savana</option>
                                    <option value="Private VIP">Paket Private VIP (Eksklusif)</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small text-light">Jumlah Peserta</label>
                                <input type="number" name="jumlah" class="form-control" 
                                    placeholder="Maks. 6 orang/Jeep" min="1" max="6" required>
                            </div>
                        </div>

                        <button type="submit" name="submit_tiket" class="btn btn-bromo btn-lg w-100 rounded-pill mt-2 shadow">
                            Pesan Tiket Bromo Sekarang <i class="bi bi-arrow-right ms-2"></i>
                        </button>
                    </form>
                </div>
            </div>

            <div class="col-md-5 col-lg-4">

                <div class="glass-card mb-4 text-center">
                    <h6 class="fw-bold text-warning mb-3 text-start">
                        <i class="bi bi-cloud-sun me-2"></i>Kondisi Cuaca Bromo
                    </h6>

                    <div id="loading-weather" class="py-3">
                        <div class="spinner-border text-warning" role="status"></div>
                        <p class="mt-2 small opacity-75">Menyinkronkan data cuaca satelit...</p>
                    </div>

                    <div id="weather-data" class="d-none fade-in">
                        <div class="weather-temp" id="suhu">0&deg;</div>
                        <h5 class="fw-bold mt-2 text-light text-capitalize" id="kondisi">Memuat...</h5>
                        <div class="row mt-4 border-top border-secondary pt-3">
                            <div class="col-6 text-start">
                                <small class="text-white-50"><i class="bi bi-wind me-1"></i>Angin</small>
                                <div class="fw-bold fs-5" id="angin">0 km/h</div>
                            </div>
                            <div class="col-6 text-end">
                                <small class="text-white-50"><i class="bi bi-droplet me-1"></i>Lembap</small>
                                <div class="fw-bold fs-5" id="lembap">0%</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="glass-card">
                    <h6 class="fw-bold text-warning mb-3"><i class="bi bi-geo-fill me-2"></i>Peta Jalur Gn. Bromo</h6>
                    <div class="map-container shadow-sm">
                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d126432.06208573516!2d112.87979659357497!3d-7.930466465415273!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dd637aaab794a41%3A0xada40d36ecd2a5dd!2sGn.%20Bromo!5e0!3m2!1sid!2sid!4v1713500000000!5m2!1sid!2sid"
                            allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script>
    document.addEventListener("DOMContentLoaded", function() {
        const loading = document.getElementById('loading-weather');
        const dataContainer = document.getElementById('weather-data');

        const fallbackData = {
            temperature: 12,
            weatherCode: 2,
            windSpeed: 15,
            humidity: 78
        };

        function getWeatherLabel(code) {
            if (code === 0) return "Cerah";
            if (code >= 1 && code <= 3) return "Berawan";
            if (code >= 45 && code <= 48) return "Berkabut";
            if (code >= 51 && code <= 67) return "Gerimis / Hujan Ringan";
            if (code >= 71 && code <= 99) return "Hujan Badai";
            return "Berawan";
        }

        function renderWeather(suhu, code, angin, lembap, isFallback = false) {
            document.getElementById('suhu').innerHTML = `${suhu}&deg;`;
            document.getElementById('kondisi').innerText = getWeatherLabel(code);
            document.getElementById('angin').innerText = `${angin} km/h`;
            document.getElementById('lembap').innerText = `${lembap}%`;

            if (isFallback) {
                const badge = document.createElement('small');
                badge.className = 'text-warning opacity-75 d-block mt-2';
                badge.style.fontSize = '0.7rem';
                badge.innerHTML = '<i class="bi bi-info-circle me-1"></i>Data estimasi (koneksi terbatas)';
                dataContainer.appendChild(badge);
            }

            loading.classList.add('d-none');
            dataContainer.classList.remove('d-none');
        }

        function fetchWithTimeout(url, ms = 7000) {
            const controller = new AbortController();
            const timer = setTimeout(() => controller.abort(), ms);
            return fetch(url, { signal: controller.signal })
                .finally(() => clearTimeout(timer));
        }

        async function fetchWeather(attempt = 1) {
            const url = 'https://api.open-meteo.com/v1/forecast?latitude=-7.9425&longitude=112.9530&current=temperature_2m,relative_humidity_2m,weather_code,wind_speed_10m&timezone=Asia%2FJakarta';

            try {
                const response = await fetchWithTimeout(url, 7000);
                if (!response.ok) throw new Error('Response tidak OK');

                const data = await response.json();
                const c = data.current;

                renderWeather(
                    Math.round(c.temperature_2m),
                    c.weather_code,
                    c.wind_speed_10m,
                    c.relative_humidity_2m
                );

            } catch (err) {
                console.warn(`Percobaan ${attempt} gagal:`, err.message);
                if (attempt < 3) {
                    setTimeout(() => fetchWeather(attempt + 1), 2000);
                } else {
                    renderWeather(
                        fallbackData.temperature,
                        fallbackData.weatherCode,
                        fallbackData.windSpeed,
                        fallbackData.humidity,
                        true
                    );
                }
            }
        }

        fetchWeather();
    });
    </script>

</body>
</html>