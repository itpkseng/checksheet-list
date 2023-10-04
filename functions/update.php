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
    //query data
    mysqli_query($conn,"UPDATE checklist SET
     nama = '$nama',
     pembuat = '$pembuat',
     tanggal = '$tanggal',
     tanggalbuat = '$tanggalbuat',
     untuk = '$untuk',
     kondisi = '$kondisi',
     gambar = '$gambar'
     WHERE id = $id");
    return mysqli_affected_rows($conn);
}

?>