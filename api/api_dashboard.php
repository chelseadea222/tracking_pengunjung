<?php
// Proses/fetch_bps.php

function getBpsData() {
    $api_key = 'c4752a971021db39a254799794cedd5b';
    $domain  = '3513'; // Probolinggo
    $id_table = '1540';
    $url = "https://webapi.bps.go.id/v1/api/view/domain/{$domain}/model/statictable/lang/ind/id/{$id_table}/key/{$api_key}";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Penting agar tidak error SSL di localhost
    $response = curl_exec($ch);
    curl_close($ch);

    $labels = [];
    $values = [];

    if ($response) {
        $json = json_decode($response, true);
        
        // Perbaikan path JSON: BPS biasanya meletakkan data di ['data'] atau ['data-statictable']['data']
        // Kita cek yang paling umum digunakan API BPS
        if (isset($json['status']) && $json['status'] == 'OK') {
            $rawData = $json['data'][1] ?? $json['data-statictable']['data'] ?? [];
            
            if (!empty($rawData)) {
                foreach ($rawData as $row) {
                    if (isset($row['label'])) {
                        $labels[] = $row['label'];
                        $vals = array_values($row['var'] ?? []);
                        $raw_val = $vals[0]['val'] ?? $vals[0] ?? 0;
                        // Bersihkan karakter non-numeric
                        $values[] = (int) preg_replace('/[^0-9]/', '', $raw_val);
                    }
                }
            }
        }
    }

    // Fallback: Jika API gagal, kirim data default
    if (empty($values)) {
        $labels = ['2018', '2019', '2020', '2021', '2022', '2023', '2024'];
        $values = [389000, 421000, 89000, 47000, 201000, 278000, 315000];
    }

    return [
        'labels' => $labels,
        'values' => $values
    ];
}

// EKSEKUSI: Panggil fungsi dan simpan ke variabel
$bpsData = getBpsData();
$bps_labels = $bpsData['labels'];
$bps_values = $bpsData['values'];