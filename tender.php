<?php
// tender.php
include("connect.php");
    include("functions.php");
    $title = $_SESSION['u_email'];

    $result = mysqli_query($con, "SELECT u_id, u_first_name FROM tbl_user WHERE u_email='$title'");
// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $tenderName = $_POST['tender_name'];
    $tenderType = $_POST['tender_type'];
    $basePrice = $_POST['base_price'];
    $strictDeadline = $_POST['strict_deadline'];
    $location = $_POST['location'];
    $description = $_POST['description'];

    // Process the data (e.g., store in a database)
    // Code for database insertion goes here

    echo "Tender successfully launched!";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Tenders</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 20px;
        }
        form {
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        input, select, textarea {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        button {
            background-color: #5cb85c;
            color: #fff;
            padding: 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #4cae4c;
        }
    </style>
</head>
<body>

    <h2>Add New Tenders</h2>

    <form method="POST">
        <label for="tender_name">Tender Name:</label>
        <input type="text" id="tender_name" name="tender_name" required>

        <label for="tender_type">Tender Type:</label>
        <select id="tender_type" name="tender_type">
            <option value="Construction">Construction</option>
            <option value="Consultation">Consultation</option>
            <option value="Supply">Supply</option>
            <!-- Add more options as necessary -->
        </select>

        <label for="base_price">Base Price:</label>
        <input type="number" id="base_price" name="base_price" required>

        <label for="strict_deadline">Strict Deadline:</label>
        <input type="date" id="strict_deadline" name="strict_deadline" required>

        <label for="location">Location:</label>
        <input type="text" id="location" name="location" required>

        <label for="description">Description:</label>
        <textarea id="description" name="description" rows="4" required></textarea>

        <button type="submit">Launch This Tender</button>
    </form>

</body>
</html>