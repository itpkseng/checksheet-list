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
$untuk = query("SELECT id,username FROM user WHERE id != $id");
$hariIni = date('Y-m-d');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Make Checklist Sheet</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="../css/editcheck.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="shortcut icon" href="./img/logo.ico" type="image/x-icon">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
</head>
<body>
    <nav class="navbar bg-primary" data-bs-theme="dark" style="background-color: #635B3C !important;">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="#">
                <img src="../img/logo.png" alt="Logo" width="50" height="50" class="d-inline-block align-text-center">
            CHECKSHEET LIST
            </a>
            <a class="navbar-brand text-capitalize" href="#">
                <?=$_SESSION["username"]?>
            <img src="../img/account-circle.png" alt="Logo" width="40" height="40" class="d-inline-block align-text-center">
            </a>
        </div>
    </nav>
    <!-- real -->
    <section class="container mt-3">
        <a class="btn btn-danger ms-3" href="./logout.php" role="button">LOGOUT</a>
        <section class="container mt-3">
            <div class="card">
                <div class="card-body">
                    <h2 class="card-title fw-bold">EDIT CHECKLIST</h2>
                    <p class="card-text"><?= $hariIni?></p>
                    <form action="" method="post" enctype="multipart/form-data">
                            <?php
                            $id = intval($check["untuk"]);
             
                            $name = query("SELECT username from user WHERE id = $id");
                
                            ?>
                            <input type="hidden" name="id" value="<?= $check["id"]?>">
                            <input type="hidden" name="tanggalbuat" value="<?= $check["tanggalbuat"]?>">
                            <input type="hidden" name="untuk" value="<?= $check["untuk"]?>">
                            <input type="hidden" name="kondisi" value="<?= $check["kondisi"]?>">
                            <input type="hidden" name="gambar" value="<?= $check["gambar"]?>">
                            <input type="hidden" name="wktupload" value="<?= $check["wktupload"] ?>">
                            <input type="hidden" name="wktvalidasi" value="<?= $check["wktvalidasi"]?>">
                            <div class="mb-3">
                                <input type="text" class="form-control" name="nama" id="nama" placeholder="Nama Checklist" value="<?= $check["nama"]?>">
                            </div>
                            <div class="mb-3">
                            <input type="date" id="start" class="form-control" name="tanggal" value="<?= $check["tanggal"]?>" min="2023-09-18" max="2025-12-31" required />
                            </div>
                            <div class="mb-3">
                            <p>Sebelumnya Ditunjukkan Ke : <?= $name[0]["username"]?></p>
                            </div>
                            <div class="mb-3">
                                <label for="checkfor">Ditunjukkan Ke</label>
                                <?php foreach($untuk as $u) :?>
                                <input type="radio" name="radio" id="<?= $u["username"]?>" value="<?= intval($u["id"])?>" required>
                                <label for="<?= $u["username"]?>"><?= $u["username"]?></label>
                                <?php endforeach;?>
                            </div>
                            <button type="submit" name="submit" class="btn btn-warning">
                            EDIT
                            </button>
                            <button type="submit" class="btn btn-danger">
                            DELETE
                            </button>
                    </form>
                </div>
            </div>
        </section>
    </section>
</body>
</html>