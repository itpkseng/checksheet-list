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


$dataTampilHariIni = query("SELECT * FROM checklist WHERE tanggal = '$hariIni'");
$dataTampilBesok = query("SELECT * FROM checklist WHERE tanggal = '$besok'");


$kondisiHariIni = [];
$kondisiBesok = [];

//mengambil kondisi checklist hari ini
foreach($dataTampilHariIni as $data) {
    // var_dump($data["kondisi"]);
    // echo"<br>";
    $kondisiHariIni[] = $data["kondisi"];
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

// var_dump($jumlahChecklistHariIni);
// var_dump($validHariIni);
// var_dump($belumValidHariIni);
// var_dump($nungguValidHariIni);




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
            $compressedImage = compressImage($imageTemp, $imageUploadPath, 10);
             
            if($compressedImage){ 
                $status = 'success'; 
                $_POST["kondisi"] = "menunggu validasi";
                $_POST["gambar"] = $compressedImage;
                var_dump($_POST);
                function uploadGambar($data) {
                    global $conn;
                    $id = $data["id"];
                    $kondisi = $data["kondisi"];
                    $gambar = $data["gambar"];
                    $wktupload = date("Y-m-d H:i:s");
                    var_dump($data["catatandaribawahan"]);
                    $catatandaribawahan = $data["catatandaribawahan"];
                    var_dump($catatandaribawahan);
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

// daily list agus






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
    <link rel="shortcut icon" href="./img/logo.ico" type="image/x-icon">
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

    <!-- view untuk yang bukan staff -->

    <?php if($_SESSION["level"] !== "staff") :?>

        <section class="checksheets">
            <section class="buttons">
                <a href="./view/logout.php">LOGOUT</a>
                <a href="./view/addcheck.php">CREATE CHECKLIST </a>
                <a href="./functions/export.php">EXPORT TO EXCEL</a>
                <form action="" method="post">
                    <button type="submit" name="agum">DAILY LIST AGUM</button>
                </form>
                <form action="" method="post">
                    <button type="submit" name="agus">DAILY LIST AGUS</button>
                </form>
            </section>
        <section class="checksheet">
            <section class="header">
                <h2>YOUR CHECKLIST</h2>
                <p><?= $hariIni ?></p>
            </section>
            <section class="main"  style="overflow-x:auto;overflow-y:auto">
            <table style="border: 1px solid black; border-collapse:collapse;text-align:center;width:90%;">
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

                    <!-- Jika Tanggal Hari ini + pembuat sama dengan nama user + untuk sama dengan nama user 
                        maka tampilkan -->

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
                                <td style="border:1px solid black;"><?= $data["catatandaribawahan"]?></td>
                                <?php if($userReal) :?>
                                    <?php if($belumValidasi === "belum validasi"):?>
                                        <td style="border:1px solid black;"><p><?=$data["catatandariatasan"]?></p></td>
                                    <?php endif;?>
                                    <?php if($tungguValidasi === "menunggu validasi") :?>
                                        <td style="border:1px solid black;"><input type="text" name="catatandariatasan" id="" placeholder="Tulis Catatan Disini"></td>
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
                                                <a href="./view/editcheck.php?id=<?= $data["id"]?>"id="edit-tombol"><img src="./img/edit-icon.svg" alt=""></a>
                                                <a href="./view//delete.php?id=<?= $data["id"]?>"id="delete-tombol"><img src="./img/delete-icon.svg" alt=""></a>
                                            </section> 
                                        <?php endif;?>
                                        <?php if($tungguValidasi === "menunggu validasi"):?>  
                                            <button type="submit" name="novalid" id="tidakvalid-tombol">TDK VALID</button>
                                            <button type="submit" name="valid" id="valid-tombol">VALID</button>
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
                    <tr>
                        <td><p>Progress : <?=$persentaseHariIni?>%</p></td>
                    </tr>
                </tfoot>
            </table>
            </section>

    
        </section>
    </section>

    <?php endif;?>






















    <!-- selesai view untuk yang bukan staff -->

    <?php if($_SESSION["level"] === "staff") :?>
        <section class="checksheets">
        <section class="buttons">
                <a href="./view/logout.php">LOGOUT</a>
                <a href="./functions/export.php">EXPORT TO EXCEL</a>
            </section>
        <section class="checksheet">
            <section class="header">
                <h2>YOUR CHECKLIST</h2>
                <p><?= $hariIni ?></p>
            </section>
            <section class="main">
            <table style="border: 1px solid black; border-collapse:collapse;text-align:center;width:90%;">
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

                    <!-- Jika Tanggal Hari ini + pembuat sama dengan nama user + untuk sama dengan nama user 
                        maka tampilkan -->

                    <?php if($tanggalReal == $hariIni && $untukReal) :?>


                    

                    <tr>                            
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
                                            <input type="file" id="image"name="image">
                                            <label for="image" id="belum-label"><?=$data["nama"]?></label>
                                           
                                        <?php endif;?>
                                        <?php if($tungguValidasi === "menunggu validasi"):?>
                                            <input type="file" id="image"name="image">
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
                                <td style="border:1px solid black;">
                                    <img src="./<?=$data["gambar"]?>" alt="" height="30" width="30">
                                </td>
                                <td style="border:1px solid black;">
                                    <p><?=$data["kondisi"]?></p>
                                </td>
                                <td style="border:1px solid black">
                                    <?php if($belumValidasi === "belum validasi"):?>
                                    <input type="text" name="catatandaribawahan" id="" placeholder="Tulis Catatan Disini">
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
                                                <button type="submit" name="submit" id="submit-tombol"><img src="./img/ok-icon.svg" alt=""></button>
                                                <button class="cancel-btn" name="cancel" id="reset-tombol":><img src="./img/cancel-icon.svg" alt=""></button>
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
                    <tr>
                        <td><p>Progress : <?=$persentaseHariIni?>%</p></td>
                    </tr>
                </tfoot>
            </table>
            </section>
        </section>
    </section>

    <?php endif;?>

</body>
</html>