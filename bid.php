<?php
include("connect.php");
include("functions.php");

$title = $_SESSION['u_email'];
$tender_id = $_GET['tender_id'];

// Check if user is logged in and is a vendor
$user = mysqli_query($con, "SELECT u_id, u_role FROM tbl_user WHERE u_email='$title'");
$user_data = mysqli_fetch_assoc($user);

if($title == NULL || $user_data['u_role'] != 2) {
    header("location: login.php");
    exit();
}

$error = "";
$success = "";

if(isset($_POST['submit'])) {
    $amount = $_POST['amount'];
    $proposal = $_POST['proposal'];
    
    // Check if vendor already submitted a bid for this tender
    $existing_bid = mysqli_query($con, "SELECT b_id FROM tbl_bid WHERE b_tender_id = '$tender_id' AND b_vendor_id = '{$user_data['u_id']}'");
    if(mysqli_num_rows($existing_bid) > 0) {
        $error = "You have already submitted a bid for this tender";
    } else {
        $insert = "INSERT INTO tbl_bid (b_tender_id, b_vendor_id, b_amount, b_proposal, b_status)
                   VALUES ('$tender_id', '{$user_data['u_id']}', '$amount', '$proposal', 1)";
        
        if(mysqli_query($con, $insert)) {
            $success = "Bid submitted successfully!";
        } else {
            $error = "Error submitting bid: " . mysqli_error($con);
        }
    }
}

// Get tender details
$tender = mysqli_query($con, "SELECT * FROM tbl_tender WHERE t_id = '$tender_id'");
$tender_data = mysqli_fetch_assoc($tender);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>E-Tendering | Submit Bid</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            background-color: #f8f9fa;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
        }
        .text-center {
            text-align: center;
        }
        .card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        .card-header {
            padding: 15px 20px;
            border-bottom: 1px solid #eee;
            background-color: #f8f9fa;
        }
        .card-body {
            padding: 20px;
        }
        .form-label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        .form-control {
            width: 100%;
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
            margin-bottom: 15px;
        }
        textarea.form-control {
            min-height: 150px;
        }
        .btn {
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }
        .btn-primary {
            background-color: #0d6efd;
            color: white;
        }
        .btn-secondary {
            background-color: #6c757d;
            color: white;
            margin-left: 10px;
        }
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
        }
        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .text-gradient {
            background: linear-gradient(to right, #0d6efd, #00b4d8);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="text-center">
            <h1 style="font-size: 2rem; font-weight: bold; margin-bottom: 20px;">
                <span class="text-gradient">Submit Bid</span>
            </h1>
        </div>
        
        <?php if($error): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>
        
        <?php if($success): ?>
            <div class="alert alert-success"><?= $success ?></div>
        <?php endif; ?>
        
        <div class="card">
            <div class="card-header">
                <h4 style="margin: 0;">Tender: <?= $tender_data['t_description'] ?></h4>
            </div>
            <div class="card-body">
                <form method="POST">
                    <div>
                        <label class="form-label">Your Bid Amount (USD)</label>
                        <input type="number" name="amount" class="form-control" step="0.01" min="0" required>
                    </div>
                    
                    <div>
                        <label class="form-label">Your Proposal</label>
                        <textarea name="proposal" class="form-control" rows="6" required></textarea>
                    </div>
                    
                    <button type="submit" name="submit" class="btn btn-primary">Submit Bid</button>
                    <a href="vendor_dashboard.php" class="btn btn-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</body>
</html>