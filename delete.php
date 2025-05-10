<?php
include("connect.php");
include("functions.php");

// Check if user is logged in and has proper permissions
if(!isset($_SESSION['u_email'])) {
    header("location: login.php");
    exit();
}
?>

DELETE FROM `tbl_tender` WHERE `tbl_tender`.`t_id` = 5