<?php
/**
 * File: data_bps.php
 * Deskripsi: Mengambil data statistik pengunjung dari API BPS Probolinggo
 */

$bps_labels = [];
$bps_values = [];
$bps_status = 'fallback'; // Default status jika API gagal
$api_key    = 'c4752a971021db39a254799794cedd5b';

// URL API Statictable BPS Probolinggo
$bps_url = "https://webapi.bps.go.id/v1/api/view/domain/3513/model/statictable/lang/ind/id/1540/key/{$api_key}";

// Menambahkan timeout 5 detik agar server tidak hang jika API BPS lambat
$options = [
    "http" => [
        "method" => "GET",
        "header" => "User-Agent: PHP\r\n",
        "timeout" => 5 
    ]
];
$context = stream_context_create($options);

// Mengambil data mentah dari API
$bps_raw = @file_get_contents($bps_url, false, $context);

if ($bps_raw) {
    $bps_json = json_decode($bps_raw, true);
    
    // Validasi struktur JSON dari BPS
    if (isset($bps_json['data'][1]) && !empty($bps_json['data'][1])) {
        foreach ($bps_json['data'][1] as $row) {
            if (isset($row['label'])) {
                $bps_labels[] = $row['label'];
                
                // Mengambil nilai variabel, bersihkan dari karakter non-angka
                $vals = array_values($row['var'] ?? [0]);
                $clean_val = preg_replace('/[^0-9]/', '', $vals[0] ?? 0);
                $bps_values[] = (int) $clean_val;
            }
        }
        
        // Jika data berhasil diisi, ubah status ke live
        if (!empty($bps_values)) {
            $bps_status = 'live';
        }
    }
}

// JIKA API GAGAL atau KOSONG (Gunakan Data Cadangan/Fallback)
if (empty($bps_values)) {
    $bps_labels = ['2018', '2019', '2020', '2021', '2022', '2023', '2024'];
    $bps_values = [389000, 421000, 89000, 47000, 201000, 278000, 315000];
    $bps_status = 'fallback';
}