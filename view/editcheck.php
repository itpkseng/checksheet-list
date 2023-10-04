<?php
session_start();
if ( !isset($_SESSION["login"])) {
    header("Location: login.php");
    exit;
}

// if ($_SESSION["level"] = "staff") {
//     header("Location: login.php");
//     exit;
// }

require '../functions/semua.php';

date_default_timezone_set('Asia/Jakarta');

require '../functions/connection.php';
require '../functions/update.php';

//ambil data id dari URL

$id_get = $_GET["id"];

//query data checklist

$check = query("SELECT * FROM checklist where id = $id_get")[0];



if(isset($_POST["submit"])) {
    $_POST["untuk"] = $_POST["radio"];
    if(update($_POST) > 0) {
        echo "
        <script>
            alert('checklist berhasil di update');
            document.location.href = '../index.php';
        </script>
        ";
    }
    else {
        echo "
        <script>
            alert('checklist gagal di update');
            document.location.href = '../index.php';
        </script>
        ";
    }
}

$id = $_SESSION["id"];
$untuk = query("SELECT username FROM user WHERE id != $id");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Make Checklist Sheet</title>
    <link rel="stylesheet" href="../css/editcheck.css">
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
            <p>User</p>
            <img src="../img//account-circle.png" alt="">
        </section>
    </nav>
    <section class="make-checksheet">
        <button>LOGOUT</button>
        <section class="container">
            <section class="header">
                <h2>EDIT CHECKLIST</h2>
                <p>24 Agustus 2023</p>
            </section>
            <form action="" method="post" enctype="multipart/form-data">
            <section class="lists">
                <input type="hidden" name="id" value="<?= $check["id"]?>">
                <input type="hidden" name="tanggalbuat" value="<?= $check["tanggalbuat"]?>">
                <input type="hidden" name="untuk" value="<?= $check["untuk"]?>">
                <input type="hidden" name="kondisi" value="<?= $check["kondisi"]?>">
                <input type="hidden" name="gambar" value="<?= $check["gambar"]?>">
                <input type="text" name="nama" id="nama" placeholder="Nama Checklist" value="<?= $check["nama"]?>">
                <input type="date" id="start" name="tanggal" value="<?= $check["tanggal"]?>" min="2023-09-18" max="2025-12-31" required />
                <p>Sebelumnya Ditunjukkan Ke : <?= $check["untuk"]?></p>
                <section class="check">
                    <label for="checkfor">Ditunjukkan Ke</label>
                    <?php foreach($untuk as $u) :?>
                        <input type="radio" name="radio" id="<?= $u["username"]?>" value="<?= $u["username"]?>">
                        <label for="<?= $u["username"]?>"><?= $u["username"]?></label>
                    <?php endforeach;?>
                </section>
            </section>
                <section class="action">
                    <button type="submit" name="submit">
                        EDIT
                    </button>
                    <button type="submit" class="delete-btn">
                        DELETE
                    </button>
                </section>
            </form>
        </section>
    </section>
</body>
</html>