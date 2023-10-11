<?php
    require '../functions/connection.php';

    function sign($data) {
        global $conn;

        $username = strtolower(stripslashes($data["username"]));
        $password = mysqli_real_escape_string($conn,$data["password"]);
        $confirm = mysqli_real_escape_string($conn,$data["confirm"]);

        //cek username sudah ada atau belum

        $hasil = mysqli_query($conn,"SELECT username FROM user WHERE username='$username'");
        if (mysqli_fetch_assoc($hasil)) {
            echo "<script>alert('username sudah terdaftar')</script>";
            return false;
        }
        //cek konfirmasi password
        if ($password !== $confirm) {
            echo "<script>alert('password tidak sesuai dengan confirm password')</script>";
            return false;
        }
        //enkripsi password
        $password = password_hash($password,PASSWORD_DEFAULT);
        //tambahkan user baru ke database
        mysqli_query($conn,"INSERT INTO user VALUES('','$username','$password','')");
        return mysqli_affected_rows($conn);
    }
?>