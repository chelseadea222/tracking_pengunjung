<?php
// ─── DATA BPS PROBOLINGGO LOGIC ───

$bps_labels = [];
$bps_values = [];
$bps_status = 'fallback';
$api_key    = 'c4752a971021db39a254799794cedd5b';

// URL API BPS Probolinggo
$bps_url = "https://webapi.bps.go.id/v1/api/view/domain/3513/model/statictable/lang/ind/id/1540/key/{$api_key}";

// Mengambil data dengan timeout agar web tidak lemot jika API BPS down
$ctx = stream_context_create(['http' => ['timeout' => 5]]); 
$bps_raw = @file_get_contents($bps_url, false, $ctx);

if ($bps_raw) {
    $bps_json = json_decode($bps_raw, true);
    
    // Pastikan struktur data API sesuai (biasanya di data[1])
    if (!empty($bps_json['data'][1])) {
        foreach ($bps_json['data'][1] as $row) {
            if (isset($row['label'])) {
                $bps_labels[] = $row['label'];
                
                // Ambil nilai variabel pertama, hapus karakter non-angka (seperti titik/koma)
                $vals = array_values($row['var'] ?? [0]);
                $clean_val = preg_replace('/[^0-9]/', '', $vals[0] ?? 0);
                $bps_values[] = (int) $clean_val;
            }
        }
        
        if (!empty($bps_values)) {
            $bps_status = 'live';
        }
    }
}

// JIKA API GAGAL (Fallback Data)
if (empty($bps_values)) {
    $bps_labels = ['2018', '2019', '2020', '2021', '2022', '2023', '2024'];
    $bps_values = [389000, 421000, 89000, 47000, 201000, 278000, 315000];
    $bps_status = 'fallback';
}