<?php
// Start session and include necessary files
session_start();
include("connect.php");
include("functions.php");

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if user is logged in
if(!isset($_SESSION['u_email'])) {
    header("location: login.php");
    exit();
}

// Check if form was submitted
if($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("location: edit_tender.php?error=Invalid request method");
    exit();
}

// Get and validate tender ID
$tender_id = isset($_POST['tender_id']) ? intval($_POST['tender_id']) : 0;
if($tender_id <= 0) {
    header("location: edit_tender.php?error=Invalid tender ID");
    exit();
}

// Sanitize and validate inputs
$tender_types = $_POST['tender_types'] ?? [];
$construction = in_array('construction', $tender_types) ? 1 : 0;
$supply = in_array('supply', $tender_types) ? 1 : 0;
$service = in_array('service', $tender_types) ? 1 : 0;

// $construction = isset($_POST['construction']) ? 1 : 0;
// $supply = isset($_POST['supply']) ? 1 : 0;
// $service = isset($_POST['service']) ? 1 : 0;
$budget = floatval($_POST['budget'] ?? 0);
$location = mysqli_real_escape_string($con, $_POST['location'] ?? '');
$contactperson = mysqli_real_escape_string($con, $_POST['contactperson'] ?? '');
$contactnumber = mysqli_real_escape_string($con, $_POST['contactnumber'] ?? '');
$description = mysqli_real_escape_string($con, $_POST['tenderdetails'] ?? '');
$status = intval($_POST['status'] ?? 1);
$awardedvendor = isset($_POST['awardedvendor']) ? intval($_POST['awardedvendor']) : NULL;

// Prepare update query
$query = "UPDATE tbl_tender SET 
    t_type_construction = ?,
    t_type_supply = ?,
    t_type_service = ?,
    t_budget = ?,
    t_location = ?,
    t_contact_person = ?,
    t_contact_person_phone_number = ?,
    t_description = ?,
    t_status = ?,
    t_awarded_vendor = ?
    WHERE t_id = ?";

$stmt = mysqli_prepare($con, $query);
if(!$stmt) {
    header("location: edit_tender.php?tender_id=$tender_id&error=" . urlencode(mysqli_error($con)));
    exit();
}

mysqli_stmt_bind_param($stmt, "iiidssssiii", 
    $construction,
    $supply,
    $service,
    $budget,
    $location,
    $contactperson,
    $contactnumber,
    $description,
    $status,
    $awardedvendor,
    $tender_id
);

if(mysqli_stmt_execute($stmt)) {
    header("location: update.php?tender_id=$tender_id&success=Tender updated successfully");
} else {
    header("location: update.php?tender_id=$tender_id&error=" . urlencode(mysqli_error($con)));
}

mysqli_stmt_close($stmt);
mysqli_close($con);
?>