<?php

require "../functions/connection.php";
require "../functions/sign-up.php";

if( isset($_POST["sign-btn"])) {

    if (sign($_POST) > 0) {
        echo "<script>alert('user baru berhasil ditambahkan')</script>";
    } else {
        echo mysqli_error($conn);
    }

}







?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checksheet List Sign</title>
    <link rel="stylesheet" href="../css/sign.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
</head>
<body>
    <section class="container">
        <header>
            <img src="../img//logo.png" alt="logo">
            <h2>CHECKSHEET LIST</h2>
        </header>
        <article>
            <h3>SIGN UP</h3>
            <form action="" method="post">
                <section class="username">
                    <label for="username">Username </label>
                    <input type="text" name="username" id="username">
                </section>
                <section class="password">
                    <label for="password">Password </label>
                    <input type="password" name="password" id="password">
                </section>
                <section class="confirm">
                    <label for="confirm">Confirm Password </label>
                    <input type="password" name="confirm" id="confirm">
                </section>
                <button type="submit" name="sign-btn">SIGN UP</button>
            </form>
        </article>
    </section>
</body>
</html>