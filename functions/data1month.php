<?php


    require './connection.php';
    require './semua.php';
    session_start();
    if ( !isset($_SESSION["login"])) {
        header("Location: ../view/login.php");
        exit;
    }

    date_default_timezone_set('Asia/Jakarta');

    $hariIni = date('Y-m-d');
    $hariJadiAngka = strtotime($hariIni);
    $sebulanAngka = $hariJadiAngka + 2628288;
    $sebulan = date('Y-m-d',$sebulanAngka);


    //semua data
    $hasilSemua = query("SELECT * from checklist");

    $username = $_SESSION["username"];
    $id = $_SESSION["id"];

    //untuk dipecah jadi satuan
    //diambil untuk mysql
    //mengambil checklist dengan where untuk = username;
    if($_SESSION["level"] !== "staff") {
        $hasilFilter = query("SELECT * from checklist WHERE tanggal BETWEEN '$hariIni' AND '$sebulan'");
    }

    if($_SESSION["level"] === "staff") {
        $hasilFilter = query("SELECT * from checklist WHERE untuk = $id AND tanggal BETWEEN '$hariIni' AND '$sebulan'");
    }
?>
<table border="1">
    <tr>
        <th>Legends</th>
        <th>untuk 7 : asep</th>
        <th>untuk 8 : agus</th>
        <th>untuk 9 : agum</th>
    </tr>
    <tr>
        <th>No</th>
        <th>Nama</th>
        <th>Pembuat</th>
        <th>Tanggal</th>
        <th>Tanggal Buat</th>
        <th>Untuk</th>
        <th>Kondisi</th>
        <th>Waktu Validasi</th>
        <th>Gambar</th>
        <th>Waktu Upload</th>
        <th>Catatan Dari Bawahan</th>
        <th>Catatan Dari Atasan</th>
    </tr>
    <?php
        $i = 1;
        foreach($hasilFilter as $hasil):?>
            <tr>
                <td><?= $i++ ?></td>
                <td><?= $hasil["nama"]?></td>
                <td><?= $hasil["pembuat"]?></td>
                <td><?= $hasil["tanggal"]?></td>
                <td><?= $hasil["tanggalbuat"]?></td>
                <td><?= $hasil["untuk"]?></td>
                <td><?= $hasil["kondisi"]?></td>
                <td><?= $hasil["wktvalidasi"]?></td>
                <td><?= $hasil["gambar"]?></td>
                <td><?= $hasil["wktupload"]?></td>
                <td><?= $hasil["catatandaribawahan"]?></td>
                <td><?= $hasil["catatandariatasan"]?></td>
            </tr>
        <?php endforeach;?>
</table>