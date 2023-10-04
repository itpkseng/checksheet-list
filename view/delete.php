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

require '../functions/hapus.php';

$id = $_GET["id"];

if (hapus($id) > 0) {
    echo "<script>
        alert('checklist berhasil dihapus');
        document.location.href = '../index.php';
        </script>";  
} else {
    echo "<script>
        alert('checklist gagal dihapus');
        document.location.href = '../index.php';
        </script>";  
}

?>

<html>
    <p>test</p>
</html>