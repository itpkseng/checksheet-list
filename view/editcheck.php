<?php
session_start();
if ( !isset($_SESSION["login"])) {
    header("Location: login.php");
    exit;
}

if ($_SESSION["level"] = "staff") {
    header("Location: login.php");
    exit;
}
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
            <form action="" method="post">
                <section class="lists">
                    <input type="text" name="" id="" placeholder="Nama Checklist">
                    <input type="date" id="start" name="tanggalchecklist" value="2023-10-01" min="2023-10-01" max="2025-12-31" />
                    <section class="check">
                        <label for="checkfor">Ditunjukkan Ke</label>
                        <input type="checkbox" name="agus" id="agus" value="agus">
                        <label for="agus">Agus</label>
                        <input type="checkbox" name="agum" id="agum" value="agum">
                        <label for="agum">Agum</label>
                    </section>
                </section>
                <section class="action">
                    <button type="submit">
                        EDIT
                    </button>
                    <button type="submit" class="delete-btn">
                        DELETE
                    </button>
                    <a href="delete.php?id=1">DELETE</a>
                </section>
            </form>
        </section>
    </section>
</body>
</html>