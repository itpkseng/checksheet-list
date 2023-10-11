<?php

    require 'connection.php';

    function change($id) {
        global $conn;
        //query data
        mysqli_query($conn,"DELETE FROM checklist WHERE id = $id");
        return mysqli_affected_rows($conn);
    }

?>