<?php

    require 'connection.php';

    function update($data) {
        global $conn;
        $id = $data["id"];
        $nama = htmlspecialchars($data["nama"]);
        $pembuat = $_SESSION["username"];
        $tanggal = htmlspecialchars($data["tanggal"]);
        $tanggalbuat = htmlspecialchars($data["tanggalbuat"]);
        $untuk = htmlspecialchars($data["untuk"]);
        $kondisi = htmlspecialchars($data["kondisi"]);
        $gambar = $data["gambar"];
        $wktupload = $data["wktupload"];
        $catatandaribawahan = $data["catatandaribawahan"];
        $catatandariatasan = $data["catatandariatasan"];
        //query data
        mysqli_query($conn,"UPDATE checklist SET
        nama = '$nama',
        pembuat = '$pembuat',
        tanggal = '$tanggal',
        tanggalbuat = '$tanggalbuat',
        untuk = '$untuk',
        kondisi = '$kondisi',
        gambar = '$gambar',
        wktupload = '$wktupload',
        catatandaribawahan = '$catatandaribawahan',
        catatandariatasan = '$catatandariatasan'
        WHERE id = $id");
        return mysqli_affected_rows($conn);
    }

    function updateValidasi($data) {
        global $conn;
        $id = $data["id"];
        $kondisi = $data["kondisi"];
        $wktvalidasi = $data["wktvalidasi"];
        $gambar = $data["gambar"];
        $catatandariatasan = $data["catatandariatasan"];

        //query data
        mysqli_query($conn,"UPDATE checklist SET kondisi = '$kondisi', wktvalidasi = '$wktvalidasi' , gambar = '$gambar', catatandariatasan = '$catatandariatasan' WHERE id = $id");
        return mysqli_affected_rows($conn);
    }
?>