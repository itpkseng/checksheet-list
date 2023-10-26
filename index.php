<?php
    session_start();
    if ( !isset($_SESSION["login"])) {
        header("Location: ./view/login.php");
        exit;
    }
    date_default_timezone_set('Asia/Jakarta');

    //cari kata yang sama dengan $_SESSION["username"] dengan strstr dan vardump dengan string untuk

    require './functions/connection.php';
    require './functions/plus.php';
    require './functions/semua.php';
    require './functions/update.php';

    $hariIni = date('Y-m-d');
    $hariJadiAngka = strtotime($hariIni);
    $besokAngka = $hariJadiAngka + 86400;
    $besok = date('Y-m-d',$besokAngka);
    $id = $_SESSION["id"];


    $dataTampilHariIni = query("SELECT * FROM checklist WHERE tanggal = '$hariIni'");
    $dataTampilBesok = query("SELECT * FROM checklist WHERE tanggal = '$besok'");
    $dataTampilHariIniPerUser = query("SELECT * FROM checklist WHERE tanggal = '$hariIni' AND untuk = $id");


    $kondisiHariIni = [];
    $kondisiBesok = [];
    $kondisiHariIniPerUser = [];

    //mengambil kondisi checklist hari ini
    foreach($dataTampilHariIni as $data) {
        // var_dump($data["kondisi"]);
        // echo"<br>";
        $kondisiHariIni[] = $data["kondisi"];
    }

    //mengambil kondisi checklist hari ini per user
    foreach($dataTampilHariIniPerUser as $data) {
        $kondisiHariIniPerUser[] = $data["kondisi"];
    }

    //mengambil kondisi checklist besok
    foreach($dataTampilBesok as $data) {
        $kondisiBesok[] = $data["kondisi"];
    }


    function sudahValid($nilai){
        return ($nilai === "tervalidasi");
    }
    function belumValid($nilai) {
        return ($nilai === "belum validasi");
    }

    function nungguValid($nilai) {
        return ($nilai === "menunggu validasi");
    }


    //mengetahui jumlah kondisi checklist hari ini

    $jumlahChecklistHariIni = count($kondisiHariIni);
    $validHariIni= count(array_filter($kondisiHariIni,"sudahValid"));
    $belumValidHariIni = count(array_filter($kondisiHariIni,"belumValid"));
    $nungguValidHariIni = count(array_filter($kondisiHariIni,"nungguValid"));

    //mengetahui jumlah kondisi checklist hari ini per user

    $jumlahChecklistHariIniPerUser = count($kondisiHariIniPerUser);
    $validHariIniPerUser = count(array_filter($kondisiHariIniPerUser,"sudahValid"));
    $belumValidHariIniPerUser = count(array_filter($kondisiHariIniPerUser,"belumValid"));
    $nungguValidHariIniPerUser = count(array_filter($kondisiHariIniPerUser,"nungguValid"));

    //mengetahui jumlah kondisi checklist besok

    $jumlahChecklistBesok = count($kondisiBesok);
    $validBesok = count(array_filter($kondisiBesok,"sudahValid"));
    $belumValidBesok = count(array_filter($kondisiBesok,"belumValid"));
    $nungguValidBesok = count(array_filter($kondisiBesok,"nungguValid"));

    if ($validHariIni === 0) {
        $persentaseHariIni = 0.0;
    } else {
        $persentaseHariIni = round(($validHariIni + $nungguValidHariIni) / $jumlahChecklistHariIni * 100);
    }

    if ($validHariIniPerUser === 0) {
        $persentaseHariIniPerUser = 0.0;
    } else {
        $persentaseHariIniPerUser = round(($validHariIniPerUser + $nungguValidHariIniPerUser) / $jumlahChecklistHariIniPerUser * 100);
    }

    if ($validBesok === 0) {
        $persentaseBesok = 0.0;
    } else {
        $persentaseBesok = round(($validBesok + $nungguValidBesok) / $jumlahChecklistBesok * 100);
    }


    function compressImage($source, $destination, $quality) { 
        // Get image info 
        $imgInfo = getimagesize($source); 
        $mime = $imgInfo['mime']; 
     
        // Create a new image from file 
        switch($mime){ 
            case 'image/jpeg': 
                $image = imagecreatefromjpeg($source); 
                imagejpeg($image, $destination, $quality);
                break; 
            case 'image/png': 
                $image = imagecreatefrompng($source); 
                imagepng($image, $destination, $quality);
                
            break; 
            case 'image/gif': 
                $image = imagecreatefromgif($source); 
                imagegif($image, $destination, $quality);
            break; 
            default: 
                $image = imagecreatefromjpeg($source); 
                imagejpeg($image, $destination, $quality);
        }    
    // Return compressed image 
        return $destination; 
    } 
 
 
    // File upload path 
    $uploadPath = "img/uploads/"; 
 
    // If file upload form is submitted 
    $status = $statusMsg = ''; 
    if(isset($_POST["submit"])){ 
        $status = 'error'; 
        if(!empty($_FILES["image"]["name"])) { 
            // File info
            $namaFile = $_FILES["image"]["name"];
            $tipeFile = explode('.',$namaFile);
            $tipeFile = strtolower(end($tipeFile));
            $fileName = uniqid();
            $fileName .= '.';
            $fileName .= $tipeFile;
            $imageUploadPath = $uploadPath . $fileName; 
            $fileType = pathinfo($imageUploadPath, PATHINFO_EXTENSION); 
         
            // Allow certain file formats 
            $allowTypes = array('jpg','png','jpeg','gif'); 
            if(in_array($fileType, $allowTypes)){ 
                // Image temp source 
                $imageTemp = $_FILES["image"]["tmp_name"]; 
             
                // Compress size and upload image 
                $compressedImage = compressImage($imageTemp, $imageUploadPath, 9);
             
                if($compressedImage){ 
                    $status = 'success'; 
                    $_POST["kondisi"] = "menunggu validasi";
                    $_POST["gambar"] = $compressedImage;
                    // var_dump($_POST);
                    function uploadGambar($data) {
                        global $conn;
                        $id = $data["id"];
                        $kondisi = $data["kondisi"];
                        $gambar = $data["gambar"];
                        $wktupload = date("Y-m-d H:i:s");
                        // var_dump($data["catatandaribawahan"]);
                        $catatandaribawahan = $data["catatandaribawahan"];
                        // var_dump($catatandaribawahan);
                        mysqli_query($conn,"UPDATE checklist SET kondisi = '$kondisi', gambar = '$gambar', wktupload = '$wktupload', catatandaribawahan = '$catatandaribawahan' WHERE id = '$id'");
                
                        return mysqli_affected_rows($conn);
                    }

                    if(uploadGambar($_POST) > 0) {
                        echo "
                        <script>
                            alert('checklist berhasil di update');
                            document.location.href = 'index.php';
                        </script>
                        ";
                    }
                    else {
                        echo "
                        <script>
                            alert('checklist gagal di update');
                            document.location.href = 'index.php';
                        </script>
                        ";
                    }
                }else{ 
                    $statusMsg = "Image compress failed!"; 
                } 
            }else{ 
                $statusMsg = 'Sorry, only JPG, JPEG, PNG, & GIF files are allowed to upload.'; 
            } 
        }else{ 
            $statusMsg = 'Please select an image file to upload.'; 
        } 
    } 

    echo $statusMsg;

    // validasi bukan untuk staff

    if( isset($_POST["novalid"])) {
        $_POST["kondisi"] = "belum validasi";
        $_POST["wktvalidasi"] = date("Y-m-d H:i:s");
        unlink('../'.$_POST["gambar"]);
        $_POST["gambar"] = "";
        if(updateValidasi($_POST) > 0) {
            echo "
            <script>
                alert('checklist berhasil di update');
                document.location.href = 'index.php';
            </script>
            ";
        } else {
            echo "
            <script>
               alert('checklist gagal di update');
                document.location.href = 'index.php';
            </script>
            ";

        }
    }

    if( isset($_POST["valid"])) {
        $_POST["kondisi"] = "tervalidasi";
        $_POST["wktvalidasi"] = date("Y-m-d H:i:s");

        if(updateValidasi($_POST) > 0 ) {
            echo "
            <script>
                alert('checklist berhasil di update');
                document.location.href = 'index.php';
            </script>
            ";
        } else {
            echo "
            <script>
                alert('checklist gagal di update');
                document.location.href = 'index.php';
            </script>
            ";
            }
        }


    // selesai validasi bukan untuk staff

    // daily list agum

        if(isset($_POST["agum"])) {
            $test = query("SELECT id,username from user WHERE id = 9");
            if(listToCheck(intval($test[0]["id"]),$test[0]["username"],$_SESSION["username"]) > 0) {
                echo "
                <script>
                    alert('daily checklist agum sudah di buat');
                    document.location.href = 'index.php';
                </script>";
            } else {
                echo "
                <script>
                    alert('daily checklist agum gagal di buat');
                    document.location.href = 'index.php';
                </script>";
            }
        }

    // selesai daily list agum

    // daily list agus

        if(isset($_POST["agus"])) {
            $test = query("SELECT id,username from user WHERE id = 8");
            if(listToCheck(intval($test[0]["id"]),$test[0]["username"],$_SESSION["username"]) > 0) {
                echo "
                <script>
                    alert('daily checklist agus sudah di buat');
                    document.location.href = 'index.php';
                </script>";
            } else {
                echo "
                <script>
                    alert('daily checklist agus gagal di buat');
                    document.location.href = 'index.php';
                </script>";
            }
        }

    // selesai daily list agus
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
        <link rel="shortcut icon" href="./img/logo.ico" type="image/x-icon">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
        <link rel="stylesheet" href="./css/index2.css">
        <title>Halaman Utama</title>
    </head>
    <body>
        <nav class="navbar bg-primary" data-bs-theme="dark" style="background-color: #635B3C !important;">
            <div class="container-fluid">
                <a class="navbar-brand fw-bold" href="#">
                    <img src="./img/logo.png" alt="Logo" width="50" height="50" class="d-inline-block align-text-center">
                    CHECKSHEET LIST
                </a>
                <a class="navbar-brand text-capitalize" href="#">
                    <?=$_SESSION["username"]?>
                <img src="./img/account-circle.png" alt="Logo" width="40" height="40" class="d-inline-block align-text-center">
                </a>
            </div>
        </nav>

        <!-- view untuk yang bukan staff -->

        <?php if($_SESSION["level"] !== "staff") :?>

            <section class="container mt-3">
                <div class="d-sm-flex justify-content-evenly mt-3">
                    <a class="btn btn-danger ms-3" href="./view/logout.php" role="button">LOGOUT</a>
                    <a class="btn btn-danger ms-3" href="./view/addcheck.php">CREATE CHECKLIST </a>
                    <a class="btn btn-danger ms-3" href="./functions/export.php">EXPORT TO EXCEL</a>
                    <form action="" method="post">
                        <button class="btn btn-danger" type="submit" name="agum">DAILY LIST AGUM</button>
                    </form>
                    <form action="" method="post">
                        <button class="btn btn-danger" type="submit" name="agus">DAILY LIST AGUS</button>
                    </form>
                </div>

                <div class="card my-3">
                    <div class="card-body">
                        <h2 class="card-title fw-bold">YOUR CHECKLIST</h2>
                        <p class="card-text"><?= $hariIni?></p>
                        <div class="table-responsive overflow-x-auto overflow-y-auto" style="height: 55vh;">
                            <table class="table table-striped align-middle text-center">
                                <thead>
                                    <tr>
                                        <th style="border: 1px solid black;">Nama</th>
                                        <th style="border: 1px solid black;">Waktu Buat</th>
                                        <th style="border: 1px solid black;">Gambar</th>
                                        <th style="border: 1px solid black;">Status</th>
                                        <th style="border: 1px solid black;">Catatan Dari Staff</th>
                                        <th style="border: 1px solid black;">Catatan Dari SPV</th>
                                        <th style="border: 1px solid black;">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($dataTampilHariIni as $data) :?>
                                        <!-- mengambil tanggal dan userReal -->
                                        <?php
                                        $tanggalReal = $data["tanggal"];
                                        $userReal = strstr($data["pembuat"],$_SESSION["username"]);
                                        $untukReal = strstr($data["untuk"],$_SESSION["username"]);
                                        $tungguValidasi = strtr($data["kondisi"],$kondisiHariIni);
                                        $belumValidasi = strtr($data["kondisi"], $kondisiHariIni);
                                        $tervalidasi = strtr($data["kondisi"],$kondisiHariIni);                    
                    
                                        ?>
                    
                                        <!-- selesai mengambil tanggal dan userReal -->

                                        <?php if($tanggalReal == $hariIni && $userReal || $untukReal) :?>

                                            <tr>
                                                <form action="" method="post" enctype="multipart/form-data">
                                                    <td style="border:1px solid black;">
                                
                                                        <input type="hidden" name="id" value="<?= $data["id"]?>">
                                                        <input type="hidden" name="tanggalbuat" value="<?= $data["tanggalbuat"]?>">
                                                        <input type="hidden" name="untuk" value="<?= $data["untuk"]?>">
                                                        <input type="hidden" name="kondisi" value="<?= $data["kondisi"]?>">
                                                        <input type="hidden" name="wktvalidasi" value="<?= $data["wktvalidasi"]?>">
                                                        <input type="hidden" name="wktupload" value="<?= $data["wktupload"]?>">
                                                        <input type="hidden" name="gambar" value="<?= $data["gambar"]?>">
                                                        <?php if($untukReal) :?>
                                                            <?php if($belumValidasi === "belum validasi"):?>
                                                                <input type="file" id="image"name="image">
                                                                <label for="image"  style="background-color: red;color:white;"><?=$data["nama"]?></label>
                                       
                                                            <?php endif;?>
                                                            <?php if($tungguValidasi === "menunggu validasi"):?>
                                                                <input type="file" id="image"name="image">
                                                                <label for="image"  style="background-color: orange;color:white;"><?=$data["nama"]?></label>
                                                            <?php endif;?>
                                                            <?php if($tervalidasi === "tervalidasi"):?>
                                                                <p><?= $data["nama"]?></p>
                                                            <?php endif;?>
                                                        <?php endif;?>

                                                        <?php if($userReal) :?>
                                                            <?php if($belumValidasi === "belum validasi"):?>
                                                                <p><?=$data["nama"]?></p>
                                       
                                                            <?php endif;?>
                                                            <?php if($tungguValidasi === "menunggu validasi"):?>  
                                                                <p><?=$data["nama"]?></p>
                                                            <?php endif;?>
                                                            <?php if($tervalidasi === "tervalidasi"):?>
                                                                <p><?= $data["nama"]?></p>
                                                            <?php endif;?>
                                                        <?php endif;?>
                                                    </td>
                                                    <td style="border:1px solid black;">
                                                        <p><?= $data["tanggalbuat"]?></p>
                                                    </td>
                                                    <td style="border:1px solid black;">
                                                        <img src="./<?=$data["gambar"]?>" alt="" height="30" width="30">
                                                    </td>
                                                    <td style="border:1px solid black;">
                                                        <p><?=$data["kondisi"]?></p>
                                                    </td>
                                                    <td style="border:1px solid black">
                                                        <p><?=$data["catatandaribawahan"]?></p>
                                                    </td>
                                                        <?php if($userReal) :?>
                                                            <?php if($belumValidasi === "belum validasi"):?>
                                                                <td style="border:1px solid black;"><p><?=$data["catatandariatasan"]?></p></td>
                                                            <?php endif;?>
                                                            <?php if($tungguValidasi === "menunggu validasi") :?>
                                                                <td style="border:1px solid black;"><textarea name="catatandariatasan" maxlength="200" id="" placeholder="Tulis Catatan Disini"></textarea></td>
                                                            <?php endif;?>
                                                            <?php if($tervalidasi === "tervalidasi") :?>
                                                                <td style="border:1px solid black;"><p><?=$data["catatandariatasan"]?></p></td>
                                                            <?php endif;?>
                                                        <?php endif;?>


                                                    <td style="border:1px solid black;">
                                                        <?php if($untukReal) :?>
                                                            <?php if($belumValidasi === "belum validasi"):?>
                                                                <section class="action">
                                                                    <button type="submit" name="submit"><img src="./img/ok-icon.svg" alt=""></button>
                                                                    <button class="cancel-btn" name="cancel"><img src="./img/cancel-icon.svg" alt=""></button>
                                                                </section> 
                                                            <?php endif;?>
                                                            <?php if($tungguValidasi === "menunggu validasi"):?>
                                                                <p>kosong</p>
                                                            <?php endif;?>
                                                            <?php if($tervalidasi === "tervalidasi"):?>
                                                                <p>kosong</p>
                                                            <?php endif;?>

                                                        <?php endif;?>
                                                        <?php if($userReal) :?>
                                                            <?php if($belumValidasi === "belum validasi"):?>
                                                                <section class="action">
                                                                    <a href="./view/editcheck.php?id=<?= $data["id"]?>"class="btn btn-warning" id="edit-tombol"><img src="./img/edit-icon.svg" alt=""></a>
                                                                    <a href="./view//delete.php?id=<?= $data["id"]?>"class="btn btn-danger" id="delete-tombol"><img src="./img/delete-icon.svg" alt=""></a>
                                                                </section> 
                                                            <?php endif;?>
                                                            <?php if($tungguValidasi === "menunggu validasi"):?>  
                                                                <button type="submit" name="novalid" class="btn btn-danger"><img src="./img/cancel-icon.svg"></button>
                                                                <button type="submit" name="valid" class="btn btn-success"><img src="./img/ok-icon.svg" alt=""></button>
                                                            <?php endif;?>
                                                            <?php if($tervalidasi === "tervalidasi"):?>
                                                                <p>sudah tervalidasi</p>
                                                            <?php endif;?>

                                                        <?php endif;?>
                                                    </td>
                                                </form>
                                            </tr>
                                        <?php endif;?>
                                    <?php endforeach;?>
                                </tbody>
                                <tfoot>
                                    <tr style="border: 1px solid black;">
                                        <td><p>Progress : <?=$persentaseHariIni?>%</p></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </section>

        <?php endif;?>

        <!-- selesai view untuk yang bukan staff -->

        <!-- view untuk staff -->

        <?php if($_SESSION["level"] === "staff") :?>

            <section class="container mt-3">
                <div class="d-sm-flex justify-content-evenly mt-3">
                    <a class="btn btn-danger ms-3" href="./view/logout.php" role="button">LOGOUT</a>
                    <a class="btn btn-danger ms-3" href="./functions/export.php">EXPORT TO EXCEL</a>
                </div>

                <div class="card my-3">
                    <div class="card-body">
                        <h2 class="card-title fw-bold">YOUR CHECKLIST</h2>
                        <p class="card-text"><?= $hariIni?></p>
                        <div class="table-responsive overflow-x-auto overflow-y-auto" style="height: 55vh;">
                            <table class="table table-striped align-middle text-center">
                                <thead>
                                    <tr>
                                        <th style="border: 1px solid black;">Nama</th>
                                        <th style="border: 1px solid black;">Waktu Buat</th>
                                        <th style="border: 1px solid black;">Gambar</th>
                                        <th style="border: 1px solid black;">Status</th>
                                        <th style="border: 1px solid black;">Catatan Dari Staff</th>
                                        <th style="border: 1px solid black;">Catatan Dari SPV</th>
                                        <th style="border: 1px solid black;">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($dataTampilHariIni as $data) :?>
                                        <!-- mengambil tanggal dan userReal -->
                                        <?php
                                            $tanggalReal = $data["tanggal"];
                                            $userReal = strstr($data["pembuat"],$_SESSION["username"]);
                                            $untukReal = strstr($data["untuk"],$_SESSION["id"]);
                                            $tungguValidasi = strtr($data["kondisi"],$kondisiHariIni);
                                            $belumValidasi = strtr($data["kondisi"], $kondisiHariIni);
                                            $tervalidasi = strtr($data["kondisi"],$kondisiHariIni);                    
                    
                                        ?>
                    
                                        <!-- selesai mengambil tanggal dan userReal -->

                                        <?php if($tanggalReal == $hariIni && $untukReal) :?>

                                            <tr class="align-middle text-center">
                                                <form action="" method="post" enctype="multipart/form-data">
                                                    <td style="border:1px solid black;">
                                
                                                        <input type="hidden" name="id" value="<?= $data["id"]?>">
                                                        <input type="hidden" name="tanggalbuat" value="<?= $data["tanggalbuat"]?>">
                                                        <input type="hidden" name="untuk" value="<?= $data["untuk"]?>">
                                                        <input type="hidden" name="kondisi" value="<?= $data["kondisi"]?>">
                                                        <input type="hidden" name="wktvalidasi" value="<?= $data["wktvalidasi"]?>">
                                                        <input type="hidden" name="wktupload" value="<?= $data["wktupload"]?>">
                                                        <input type="hidden" name="catatandaribawahan" value="<?= $data["catatandaribawahan"]?>">
                                                        <input type="hidden" name="catatandariatasan" value="<?= $data["catatandariatasan"]?>">
                                
                                                        <?php if($untukReal) :?>
                                                            <?php if($belumValidasi === "belum validasi"):?>
                                                                <label for="image" id="belum-label" class="form-label"><?=$data["nama"]?></label>
                                                                <input type="file" class="form-control form-control-sm" id="image"name="image">
                                                            <?php endif;?>
                                                            <?php if($tungguValidasi === "menunggu validasi"):?>
                                                                <label for="image"><?=$data["nama"]?></label>
                                                            <?php endif;?>
                                                            <?php if($tervalidasi === "tervalidasi"):?>
                                                                <p><?= $data["nama"]?></p>
                                                            <?php endif;?>
                                                        <?php endif;?>
                                                    </td>
                                                    <td style="border:1px solid black;">
                                                        <p><?= $data["tanggalbuat"]?></p>
                                                    </td>
                                                    <td class="align-middle" style="border:1px solid black;">
                                                        <img src="./<?=$data["gambar"]?>" alt="" height="30" width="30">
                                                    </td>
                                                    <td style="border:1px solid black;">
                                                        <p><?=$data["kondisi"]?></p>
                                                    </td>
                                                    <td style="border:1px solid black">
                                                        <?php if($belumValidasi === "belum validasi"):?>
                                                            <textarea name="catatandaribawahan" maxlength="200" id="" placeholder="Tulis Catatan Disini"></textarea>
                                                        <?php endif;?>
                                                        <?php if($tungguValidasi === "menunggu validasi"):?>
                                                            <p><?=$data["catatandaribawahan"]?> </p>
                                                        <?php endif;?>
                                                        <?php if($tervalidasi === "tervalidasi"):?>
                                                            <p><?=$data["catatandaribawahan"]?></p>
                                                        <?php endif;?>
                                                    </td>
                                                    <td style="border:1px solid black;">
                                                        <p><?=$data["catatandariatasan"]?></p>
                                                    </td>
                                                    <td style="border:1px solid black;">
                                                        <?php if($untukReal) :?>
                                                            <?php if($belumValidasi === "belum validasi"):?>
                                                                <section class="action">
                                                                    <button type="submit" name="submit" class="btn btn-success"><img src="./img/ok-icon.svg" alt=""></button>
                                                                    <button name="cancel" class="btn btn-danger"><img src="./img/cancel-icon.svg" alt=""></button>
                                                                </section> 
                                                            <?php endif;?>
                                                            <?php if($tungguValidasi === "menunggu validasi"):?>
                                                                <p>kosong</p>
                                                            <?php endif;?>
                                                            <?php if($tervalidasi === "tervalidasi"):?>
                                                                <p>kosong</p>
                                                            <?php endif;?>
                                                        <?php endif;?>
                                                    </td>
                                                </form>
                                            </tr>
                                        <?php endif;?>
                                    <?php endforeach;?>
                                </tbody>
                                <tfoot>
                                    <tr style="border: 1px solid black;">
                                        <td><p>Progress : <?=$persentaseHariIniPerUser?>%</p></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </section>
        <?php endif;?>
        <!-- selesai view untuk staff -->
    </body>
</html>