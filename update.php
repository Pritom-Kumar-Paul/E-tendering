<?php
// Start session and include necessary files
session_start();
include("connect.php");
include("functions.php");

// Check if user is logged in and has proper permissions
if(!isset($_SESSION['u_email'])) {
    header("location: login.php");
    exit();
}

// Get tender ID from URL
$tender_id = isset($_GET['tender_id']) ? intval($_GET['tender_id']) : 0;

// Fetch tender details
$query = "SELECT * FROM tbl_tender WHERE t_id = ?";
$stmt = mysqli_prepare($con, $query);
mysqli_stmt_bind_param($stmt, "i", $tender_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_row($result);

if(!$row) {
    die("Tender not found");
}

// Get user role
$user_role = $_SESSION['u_role'] ?? 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Tender</title>
    <style>
        /* Form container styling */
        .form-container {
            max-width: 800px;
            margin: 20px auto;
            padding: 25px;
            background-color: #f9f9f9;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            font-family: Arial, sans-serif;
        }

        /* Label styling */
        .form-container label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #333;
        }

        .form-container label[for="status"] {
            margin-top: 15px;
        }

        /* Input field styling */
        .form-container input[type="text"],
        .form-container textarea,
        .form-container select {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
            font-size: 14px;
        }

        .form-container textarea {
            height: 100px;
            resize: vertical;
        }

        /* Checkbox and radio button styling */
        .checkbox-group, .radio-group {
            margin-bottom: 15px;
        }

        .checkbox-group label, .radio-group label {
            display: inline-block;
            margin-right: 15px;
            font-weight: normal;
            cursor: pointer;
        }

        /* Button styling */
        .submit-btn {
            background-color: #4CAF50;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
        }

        .submit-btn:hover {
            background-color: #45a049;
        }

        /* Responsive adjustments */
        @media (max-width: 600px) {
            .form-container {
                padding: 15px;
            }
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Update Tender</h2>
        
        <?php if(isset($_GET['success'])): ?>
            <div style="color: green; margin-bottom: 15px;">Tender updated successfully!</div>
        <?php elseif(isset($_GET['error'])): ?>
            <div style="color: red; margin-bottom: 15px;">Error: <?= htmlspecialchars($_GET['error']) ?></div>
        <?php endif; ?>

        <form method="POST" action="Updatetender.php" enctype="multipart/form-data">
            <input type="hidden" name="tender_id" value="<?= $tender_id ?>">
            
            <label><strong>Tender Type:</strong></label>
            <div class="checkbox-group">
                <label>
                    <input type="checkbox" name="tender_types[]" value="construction" <?= $row[1] == '1' ? 'checked' : '' ?>> Construction
                </label>
                <label>
                    <input type="checkbox" name="tender_types[]" value="supply" <?= $row[2] == '1' ? 'checked' : '' ?>> Supply
                </label>
                <label>
                    <input type="checkbox" name="tender_types[]" value="service" <?= $row[3] == '1' ? 'checked' : '' ?>> Service
                </label>
            </div>

            <label><strong>Budget:</strong></label>
            <input type="text" name="budget" value="<?= htmlspecialchars($row[4]) ?>">

            <label><strong>Location:</strong></label>
            <textarea name="location"><?= htmlspecialchars($row[5]) ?></textarea>

            <label><strong>Contact Person:</strong></label>
            <input type="text" name="contactperson" value="<?= htmlspecialchars($row[6]) ?>">

            <label><strong>Contact Number:</strong></label>
            <input type="text" name="contactnumber" value="<?= htmlspecialchars($row[7]) ?>">

            <label><strong>Tender Description:</strong></label>
            <textarea name="tenderdetails"><?= htmlspecialchars($row[8]) ?></textarea>

            <label><strong>Tender Status:</strong></label>
            <div class="radio-group">
                <label>
                    <input type="radio" name="status" value="1" <?= $row[10] == '1' ? 'checked' : '' ?>> Pending
                </label>
                <label>
                    <input type="radio" name="status" value="2" <?= $row[10] == '2' ? 'checked' : '' ?>> Bids Received
                </label>
                <label>
                    <input type="radio" name="status" value="3" <?= $row[10] == '3' ? 'checked' : '' ?>> Awarded
                </label>
                <label>
                    <input type="radio" name="status" value="4" <?= $row[10] == '4' ? 'checked' : '' ?>> Completed
                </label>
            </div>

            <?php if($user_role == 1): ?>
                <label><strong>Awarded Vendor:</strong></label>
                <select name="awardedvendor">
                    <option value="">-- Select Vendor --</option>
                    <?php
                    $vendor_list = mysqli_query($con, "SELECT * FROM tbl_user WHERE u_role = '2'");
                    while($vendor = mysqli_fetch_assoc($vendor_list)): ?>
                        <option value="<?= $vendor['u_id'] ?>" <?= $row[9] == $vendor['u_id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($vendor['u_company']) ?> (<?= htmlspecialchars($vendor['u_email']) ?>)
                        </option>
                    <?php endwhile; ?>
                </select>
            <?php endif; ?>

            <input type="submit" class="submit-btn" name="submit" value="Update Tender">
        </form>
    </div>
</body>
</html>