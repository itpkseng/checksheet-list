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

$hariIni = date('Y-m-d');
$hariJadiAngka = strtotime($hariIni);
$besokAngka = $hariJadiAngka + 86400;
$besok = date('Y-m-d',$besokAngka);


$dataTampilSemua = query("SELECT * FROM checklist");

//upload gambar

// if(isset($_POST["submit"])) {
//     $file = upload();
//     if (!$file) {
//         return false;
//     }
// }

//upload dan kompress gambar

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
            $compressedImage = compressImage($imageTemp, $imageUploadPath, 10);
             
            if($compressedImage){ 
                $status = 'success'; 
                $statusMsg = "Image compressed successfully."; 
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



?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="./css/index.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <title>Halaman Utama</title>
</head>
<body>
    <nav>
        <section class="left">
            <img src="./img/logo.png" alt="logo">
            <strong>CHECKSHEET LIST</strong>
        </section>
        <section class="right">
            <p style="text-transform: capitalize;"><?= $_SESSION["username"]?></p>
            <img src="./img/account-circle.png" alt="">
        </section>
    </nav>
    <section class="checksheets">
        <button><a href="./view/logout.php">LOGOUT</a></button>
        <?php if($_SESSION["level"] !== "staff") :?>
            <button><a href="./view/addcheck.php">CREATE CHECKLIST</a></button>
        <?php endif; ?>
        <button><a href="./functions/export.php">Export To Excel</a></button>
        <section class="checksheet">
            <section class="header">
                <h2>YOUR CHECKLIST</h2>
                <p><?= $hariIni ?></p>
            </section>
            <?php foreach($dataTampilSemua as $data) :?>
                <?php
                    $tanggalReal = $data["tanggal"];
                    // if(var_dump(strstr($data["untuk"],$_SESSION["username"]))) {
                    //     $userReal = true;
                    // } else {
                    //     $userReal = false;
                    // }
                    $userReal = strstr($data["pembuat"],$_SESSION["username"]);
                    $untukReal = strstr($data["untuk"],$_SESSION["username"]);
                ?>
                <?php if($_SESSION["level"] !== "staff") :?>
                    <?php if($tanggalReal == $hariIni && $userReal && $untukReal) :?>
                    <section class="lists">
                <form action="" method="post" enctype="multipart/form-data">
                    <input type="file" id="image"name="image" required>
                    <label for="image"><?=$data["nama"]?></label>
                    <section class="action">
                        <button type="submit" name="submit"><img src="./img/ok-icon.svg" alt=""></button>
                        <button class="cancel-btn"><img src="./img/cancel-icon.svg" alt=""></button>
                    </section>    
                </form>
            </section>
                        
                
                    <?php endif;?>
                <?php endif;?>
                <?php if($tanggalReal == $hariIni && $untukReal) :?>
                    <section class="lists">
                <form action="" method="post" enctype="multipart/form-data">
                    <input type="file" id="image"name="image" required>
                    <label for="image"><?=$data["nama"]?></label>
                    <section class="action">
                        <button type="submit" name="submit"><img src="./img/ok-icon.svg" alt=""></button>
                        <button class="cancel-btn"><img src="./img/cancel-icon.svg" alt=""></button>
                    </section>    
                </form>
            </section>
                    <?php endif;?>
            <?php endforeach;?>
            <section class="progress">
                <p>100%</p>
            </section>
        </section>










        <section class="checksheet">
            <section class="header">
                <h2>YOUR CHECKLIST</h2>
                <p><?= $besok ?></p>
            </section>
            <?php foreach($dataTampilSemua as $data) :?>
                <?php $dataReal = $data["tanggal"]?>
                <?php if($dataReal == $besok) :?>
                    <section class="lists">
                <form action="" method="post">
                    <input type="file" id="test"name="test">
                    <label for="test"><?=$data["nama"]?></label>
                    <section class="action">
                        <button type="submit"><img src="./img/ok-icon.svg" alt=""></button>
                        <button class="cancel-btn"><img src="./img/cancel-icon.svg" alt=""></button>
                    </section>    
                </form>
            </section>
                    <?php endif;?>
            <?php endforeach;?>
            <section class="progress">
                <p>100%</p>
            </section>
        </section>
    </section>
</body>
</html>