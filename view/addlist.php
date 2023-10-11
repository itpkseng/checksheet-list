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

require '../functions/semua.php';

date_default_timezone_set('Asia/Jakarta');

require '../functions/connection.php';
require '../functions/plus.php';


if( isset($_POST["create-btn"])) {
    $_POST["untuk"] = $_POST["radio"];
    $_POST["untuk"] = intval($_POST["untuk"]);


    if(pluslist($_POST) > 0) {
        echo "<script>
        alert('checklist berhasil ditambahkan');
        document.location.href = '../index.php';
        </script>";   
    } else {
        echo "<script>alert('checklist gagal ditambahkan')</script>";
    }
}

$id = $_SESSION["id"];
$untuk = query("SELECT id, username FROM user WHERE id != $id");



?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Make Checklist Sheet</title>
    <link rel="stylesheet" href="../css/addcheck.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
</head>
<body>
    <nav>
        <section class="left">
            <img src="../img/logo.png" alt="">
            <h3>CHECKSHEET LIST</h3>
        </section>
        <section class="right">
            <p style="text-transform: capitalize;"><?= $_SESSION["username"]?></p>
            <img src="../img//account-circle.png" alt="">
        </section>
    </nav>
    <section class="make-checksheet">
    <button><a href="./logout.php">LOGOUT</a></button>
        <section class="container">
            <section class="header">
                <h2>MAKE LIST</h2>
                <p>24 Agustus 2023</p>
            </section>
            <form action="" method="post" enctype="multipart/form-data">
            <section class="lists">
                <input type="text" name="nama" id="nama" placeholder="Nama Checklist" required>
                <section class="check">
                    <label for="checkfor">Ditunjukkan Ke</label>
                    <?php foreach($untuk as $u) :?>
                        <input type="radio" name="radio" id="<?= $u["username"]?>" value="<?= $u["id"]?>">
                    <label for="<?= $u["username"]?>"><?= $u["username"]?></label>
                        <?php endforeach;?>
                </section>
            </section>
            <section class="action">
                <button type="submit" name="create-btn">
                    CREATE
                </button>
            </section>
        </form>
        </section>
    </section>
</body>
</html>