<?php
require_once 'koneksi.php';

$pesan_sukses = false;
$pesan_error  = '';

if (isset($_POST['submit_tiket'])) {
    $nama         = trim($_POST['nama'] ?? '');
    $whatsapp     = trim($_POST['whatsapp'] ?? '');
    $tanggal      = $_POST['tanggal'] ?? '';
    $titik_kumpul = $_POST['titik_kumpul'] ?? '';
    $paket        = $_POST['paket'] ?? '';
    $jumlah       = (int)($_POST['jumlah'] ?? 0);

    if ($nama && $whatsapp && $tanggal && $titik_kumpul && $paket && $jumlah > 0) {
        try {
            $stmt = $pdo->prepare("
                INSERT INTO tiket_harian (nama, whatsapp, tanggal, titik_kumpul, paket, jumlah, status)
                VALUES (:nama, :whatsapp, :tanggal, :titik_kumpul, :paket, :jumlah, 'Pending')
            ");
            $stmt->execute([
                ':nama'         => $nama,
                ':whatsapp'     => $whatsapp,
                ':tanggal'      => $tanggal,
                ':titik_kumpul' => $titik_kumpul,
                ':paket'        => $paket,
                ':jumlah'       => $jumlah
            ]);
            $pesan_sukses = true;
        } catch (PDOException $e) {
            $pesan_error = "Gagal menyimpan data: " . $e->getMessage();
        }
    } else {
        $pesan_error = "Semua field wajib diisi dengan benar.";
    }
}