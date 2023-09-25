<?php

function upload(){

    $namaFile = $_FILES["test"]["name"];
    $ukuranFile = $_FILES["test"]["size"];
    $error = $_FILES["test"]["error"];
    $tmpName = $_FILES["test"]["tmp_name"];

    // var_dump($_FILES);

    // //cek apakah tidak ada gambar yang di upload
    // if ($error === 4) {
    //     echo '<script>
    //     alert("foto belum terupload");
    //     </script>';
    //     return false;
    // }

    // //cek apakah file gambar atau bukan
    // $ektensiValid = ['jpg','jpeg','png'];
    // $ektensiGambar = explode('.',$namaFile);
    // $ektensiGambar = strtolower(end($ektensiGambar));
    // if(!in_array($ektensiGambar,$ektensiValid)) {
    //     echo '<script>
    //     alert("hanya boleh upload gambar");
    //     </script>';
    // }

    //kompress gambar 

    $info = getimagesize($tmpName);

        var_dump($_FILES);
        echo"<br>";
    var_dump($info);

    if(isset($info['mime'])) {
        if($info['mime']=="image/jpeg") {
            $img = imagecreatefromjpeg($tmpName);
        }elseif($info['mime']=="image/png") {
            $img = imagecreatefrompng($tmpName);
        } else {
            echo "<script>alert(Hanya Boleh Upload Gambar png dan jpeg);</script>";
        }
        if(isset($img)) {
            $output_image =time().'.jpg';
            $test = imagejpeg($img,$output_image,20);
            echo $test;
            var_dump($test);
    
        }

        //merubah $_FILES menjadi hasil kompress

        $_FILES
    }

    else {
        echo "<script>alert(Hanya Boleh Upload Gambar png dan jpeg);</script>";
    }


};

?>