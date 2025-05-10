<?php
    $con = mysqli_connect("localhost", "root", "", "etendering");
    if(mysqli_connect_errno()) {
        echo "Error occurred while connecting with database".mysqli_connect_errno();
    }
    session_start();
?>