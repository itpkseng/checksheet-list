<?php
    session_start();
    if ( isset($_SESSION["login"])) {
        header("Location: ../index.php");
        exit;
    }
    require '../functions/connection.php';

    if ( isset($_POST["submit-btn"])) {
        $username = $_POST["username"];
        $password = $_POST["password"];

        //cek apakah username ada atau tidak

        $hasil = mysqli_query($conn,"SELECT * from user WHERE username='$username'");
    
        //cek apakah hasil ada

        if(mysqli_num_rows($hasil) === 1) {

            //cek password
            $row = mysqli_fetch_assoc($hasil);
            if(password_verify($password,$row["password"])) {
                //set session
                $_SESSION["login"] = true;
                $_SESSION["level"] = $row["level"];
                $_SESSION["username"] = $row["username"];
                $_SESSION["id"] = (int) $row["id"];
                // var_dump($_SESSION);
                //redirect ke halaman utama
                header("Location: ../index.php");
                exit;
            }
        }
        $error = true;
    }
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Checksheet List Login</title>
        <link rel="stylesheet" href="../css/login.css">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link rel="shortcut icon" href="./img/logo.ico" type="image/x-icon">
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    </head>
    <body>
        <section class="container">
            <header>
                <img src="../img//logo.png" alt="logo">
                <h2>CHECKSHEET LIST</h2>
                <?php if(isset($error)) :?>
                    <p style="color:white;font-style:italic;">username/password salah</p>
                <?php endif;?>
            </header>
            <article>
                <h3>LOGIN</h3>
                <form action="" method="post">
                    <section class="username">
                        <label for="username">Username </label>
                        <input type="text" name="username" id="username">
                    </section>
                    <section class="password">
                        <label for="password">Password </label>
                        <input type="password" name="password" id="password">
                    </section>
                    <button type="submit" name="submit-btn">LOGIN</button>
                </form>
            </article>
        </section>
    </body>
</html>