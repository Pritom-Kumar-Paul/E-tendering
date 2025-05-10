<?php
include("connect.php");

if(isset($_GET['tender_id'])) {
    $tender_id = mysqli_real_escape_string($con, $_GET['tender_id']);
    
    // First, delete related bids
    mysqli_query($con, "DELETE FROM tbl_bid WHERE b_tender_id = '$tender_id'");
    
    // Then delete the tender
    $delete_query = mysqli_query($con, "DELETE FROM tbl_tender WHERE t_id = '$tender_id'");
    
    if($delete_query) {
        header("location: admin_tenders.php?delete_success=1");
    } else {
        header("location: admin_tenders.php?delete_error=1");
    }
} else {
    header("location: admin_tenders.php");
}
?>