<?php
session_start();
if ( !isset($_SESSION["login"])) {
    header("Location: login.php");
    exit;
}

if ($_SESSION["level"] === "staff") {
    header("Location: login.php");
    exit;
}

// require './functions/connection.php';
// require './functions/semua.php';

require '../functions/connection.php';
require '../functions/semua.php';

$id = $_GET["id"];

$checklist = query("SELECT * FROM checklist WHERE id = $id")[0];

var_dump($checklist["gambar"]);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="../css/validasi.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <title>Validasi </title>
</head>
<body>
    <nav>
        <section class="left">
            <img src="../img/logo.png" alt="logo">
            <strong>CHECKSHEET LIST</strong>
        </section>
        <section class="right">
            <p>User</p>
            <img src="../img/account-circle.png" alt="">
        </section>
    </nav>
    <section class="checksheets">
        <button>LOGOUT</button>
        <section class="checksheet">
            <section class="header">
                <h2>VALIDASI DOKUMEN</h2>
                <p>24 Agustus 2023</p>
            </section>
            <section class="document">
                <img src="../<?= $checklist["gambar"]?>" alt="" width="100" height="100">
            </section>
            <form method="post" action="" class="action">
                <button type="submit">TIDAK VALID</button>
                <button type="submit">VALID</button>
            </form>
        </section>
    </section>
</body>
</html>