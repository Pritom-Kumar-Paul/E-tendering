<?php
session_start();
include("connect.php");

// Check if form was submitted
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) {
    // Get form data
    $tender_id = intval($_POST['tender_id']);
    $budget = mysqli_real_escape_string($con, $_POST['budget']);
    $location = mysqli_real_escape_string($con, $_POST['location']);
    $contactperson = mysqli_real_escape_string($con, $_POST['contactperson']);
    $contactnumber = mysqli_real_escape_string($con, $_POST['contactnumber']);
    $tenderdetails = mysqli_real_escape_string($con, $_POST['tenderdetails']);
    $status = intval($_POST['status']);
    
    // Process tender types (checkboxes)
    $construction = 0;
    $supply = 0;
    $service = 0;
    
    if(isset($_POST['tender_types'])) {
        foreach($_POST['tender_types'] as $type) {
            if($type == 'construction') $construction = 1;
            if($type == 'supply') $supply = 1;
            if($type == 'service') $service = 1;
        }
    }
    
    // Process awarded vendor (if admin)
    $awardedvendor = null;
    if(isset($_SESSION['u_role']) && $_SESSION['u_role'] == 1 && isset($_POST['awardedvendor'])) {
        $awardedvendor = intval($_POST['awardedvendor']);
    }
    
    // Update query
    $query = "UPDATE tbl_tender SET 
              t_construction = ?,
              t_supply = ?,
              t_service = ?,
              t_budget = ?,
              t_location = ?,
              t_contact_person = ?,
              t_contact_number = ?,
              t_description = ?,
              t_status = ?";
    
    // Add awarded vendor if set
    if($awardedvendor !== null) {
        $query .= ", t_awarded_vendor = ?";
    }
    
    $query .= " WHERE t_id = ?";
    
    // Prepare statement
    $stmt = mysqli_prepare($con, $query);
    
    // Bind parameters
    if($awardedvendor !== null) {
        mysqli_stmt_bind_param($stmt, "iiisssssiii", 
            $construction, $supply, $service, $budget, $location, 
            $contactperson, $contactnumber, $tenderdetails, $status, 
            $awardedvendor, $tender_id);
    } else {
        mysqli_stmt_bind_param($stmt, "iiisssssii", 
            $construction, $supply, $service, $budget, $location, 
            $contactperson, $contactnumber, $tenderdetails, $status, 
            $tender_id);
    }
    
    // Execute and redirect
    if(mysqli_stmt_execute($stmt)) {
        header("Location: update.php?tender_id=$tender_id&success=1");
    } else {
        header("Location: update.php?tender_id=$tender_id&error=" . urlencode(mysqli_error($con)));
    }
    exit();
} else {
    header("Location: update.php?tender_id=$tender_id&error=Invalid request");
    exit();
}
?>