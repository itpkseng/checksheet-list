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
    mysqli_query($conn,"INSERT INTO checklist VALUES ('','$nama','asep','$tanggal','$tanggalbuat','$untuk','$kondisi','$gambar');");
    return mysqli_affected_rows($conn);
}

?>