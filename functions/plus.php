<?php

    require 'connection.php';

    function plus($data) {
        global $conn;
        $nama = htmlspecialchars($data["nama"]);
        $tanggal = htmlspecialchars($data["tanggal"]);
        $tanggalbuat = htmlspecialchars($data["tanggalbuat"]);
        $untuk = htmlspecialchars($data["untuk"]);
        $kondisi = htmlspecialchars($data["kondisi"]);
        $gambar = "";
        //query data
        mysqli_query($conn,"INSERT INTO checklist VALUES ('','$nama','asep','$tanggal','$tanggalbuat','$untuk','$kondisi','','$gambar','','','');");
        return mysqli_affected_rows($conn);
    }

    function pluslist($data) {
        global $conn;

        $nama = htmlspecialchars($data["nama"]);
        $untuk = $data["untuk"];
    
        //query data
        mysqli_query($conn,"INSERT INTO lists VALUES ('','$nama','$untuk');");
        return mysqli_affected_rows($conn);
    }

    function listToCheck($id,$username,$pembuat) {
        global $conn;
        $tanggal = htmlspecialchars(date("Y-m-d"));
        $tanggalbuat = htmlspecialchars(date("Y-m-d H:i:s"));
        $kondisi = "belum validasi";
        $gambar = "";
        $catatndaribawahan = "";
        $catatndariatasan = "";
        //mengambil list lalu memasukkannya ke checklist
        mysqli_query($conn,"INSERT INTO checklist (nama,untuk) SELECT nama,untuk FROM lists WHERE untuk = $id;");
        mysqli_query($conn,"UPDATE checklist SET pembuat = '$pembuat',tanggal='$tanggal',tanggalbuat='$tanggalbuat',kondisi='$kondisi',gambar='$gambar',catatandaribawahan='$catatndaribawahan',catatandariatasan='$catatndariatasan';");
       return mysqli_affected_rows($conn);
    }
?>

