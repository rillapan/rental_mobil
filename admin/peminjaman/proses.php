<?php

 require '../../koneksi/koneksi.php';

if($_GET['id'] == 'konfirmasi')
{
    $kode_booking = $_POST['kode_booking'];
    try {
        $data[] = $_POST['status'];
        $data[] = $_POST['id_mobil'];
        $sql = "UPDATE `mobil` SET `status`= ? WHERE id_mobil= ?";
        $row = $koneksi->prepare($sql);
        $row->execute($data);

        echo "<script>window.location='peminjaman.php?id=$kode_booking&status=update_success';</script>";
    } catch (Exception $e) {
        echo "<script>window.location='peminjaman.php?id=$kode_booking&status=update_error';</script>";
    }
}