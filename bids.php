<?php
include("connect.php");
include("functions.php");

$title = $_SESSION['u_email'];
$tender_id = $_GET['tender_id'];

// Check if user is logged in and is admin
$user = mysqli_query($con, "SELECT u_role FROM tbl_user WHERE u_email='$title'");
$user_data = mysqli_fetch_assoc($user);

if($title == NULL || $user_data['u_role'] != 1) {
    header("location: login.php");
    exit();
}

// Get tender details
$tender = mysqli_query($con, "SELECT * FROM tbl_tender WHERE t_id = '$tender_id'");
$tender_data = mysqli_fetch_assoc($tender);

// Process award bid if form submitted
if(isset($_POST['award_bid'])) {
    $bid_id = $_POST['bid_id'];
    
    // Update tender with awarded vendor
    $bid = mysqli_query($con, "SELECT b_vendor_id FROM tbl_bid WHERE b_id = '$bid_id'");
    $bid_data = mysqli_fetch_assoc($bid);
    
    $update = "UPDATE tbl_tender SET 
               t_awarded_vendor = '{$bid_data['b_vendor_id']}', 
               t_status = 3 
               WHERE t_id = '$tender_id'";
    
    if(mysqli_query($con, $update)) {
        // Update bid status to accepted
        mysqli_query($con, "UPDATE tbl_bid SET b_status = 3 WHERE b_id = '$bid_id'");
        // Update other bids to rejected
        mysqli_query($con, "UPDATE tbl_bid SET b_status = 4 WHERE b_tender_id = '$tender_id' AND b_id != '$bid_id'");
        
        $success = "Tender awarded successfully!";
    } else {
        $error = "Error awarding tender: " . mysqli_error($con);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>E-Tendering | View Bids</title>
    <!-- Head elements remain the same -->
    <style>
    /* Base Styles */
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        line-height: 1.6;
        color: #333;
        background-color: #f8f9fa;
    }

    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 15px;
    }

    /* Header Styles */
    .text-gradient {
        background: linear-gradient(to right, #4e54c8, #8f94fb);
        -webkit-background-clip: text;
        background-clip: text;
        color: transparent;
    }

    .display-5 {
        font-size: 2.5rem;
        font-weight: 300;
        line-height: 1.2;
    }

    .mb-0 {
        margin-bottom: 0 !important;
    }

    .text-center {
        text-align: center !important;
    }

    /* Alert Styles */
    .alert {
        position: relative;
        padding: 1rem 1.25rem;
        margin-bottom: 1rem;
        border: 1px solid transparent;
        border-radius: 0.25rem;
    }

    .alert-danger {
        color: #721c24;
        background-color: #f8d7da;
        border-color: #f5c6cb;
    }

    .alert-success {
        color: #155724;
        background-color: #d4edda;
        border-color: #c3e6cb;
    }

    /* Card Styles */
    .card {
        position: relative;
        display: flex;
        flex-direction: column;
        min-width: 0;
        word-wrap: break-word;
        background-color: #fff;
        background-clip: border-box;
        border: 1px solid rgba(0, 0, 0, 0.125);
        border-radius: 0.25rem;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    }

    .card-body {
        flex: 1 1 auto;
        padding: 1.25rem;
    }

    /* Table Styles */
    .table {
        width: 100%;
        margin-bottom: 1rem;
        color: #212529;
        border-collapse: collapse;
    }

    .table th,
    .table td {
        padding: 0.75rem;
        vertical-align: top;
        border-top: 1px solid #dee2e6;
    }

    .table thead th {
        vertical-align: bottom;
        border-bottom: 2px solid #dee2e6;
        background-color: #f8f9fa;
        font-weight: 600;
    }

    .table tbody tr:nth-of-type(odd) {
        background-color: rgba(0, 0, 0, 0.02);
    }

    .table tbody tr:hover {
        background-color: rgba(0, 0, 0, 0.05);
    }

    /* Button Styles */
    .btn {
        display: inline-block;
        font-weight: 400;
        text-align: center;
        white-space: nowrap;
        vertical-align: middle;
        user-select: none;
        border: 1px solid transparent;
        padding: 0.375rem 0.75rem;
        font-size: 0.875rem;
        line-height: 1.5;
        border-radius: 0.25rem;
        transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out,
            border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    }

    .btn-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
        line-height: 1.5;
        border-radius: 0.2rem;
    }

    .btn-success {
        color: #fff;
        background-color: #28a745;
        border-color: #28a745;
    }

    .btn-success:hover {
        background-color: #218838;
        border-color: #1e7e34;
    }

    .btn-info {
        color: #fff;
        background-color: #17a2b8;
        border-color: #17a2b8;
    }

    .btn-info:hover {
        background-color: #138496;
        border-color: #117a8b;
    }

    /* Spacing Utilities */
    .py-5 {
        padding-top: 3rem !important;
        padding-bottom: 3rem !important;
    }

    .px-5 {
        padding-right: 3rem !important;
        padding-left: 3rem !important;
    }

    .mb-5 {
        margin-bottom: 3rem !important;
    }

    /* Responsive Table */
    @media (max-width: 768px) {
        .table-responsive {
            display: block;
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
        
        .table {
            width: 100%;
            max-width: 100%;
            margin-bottom: 1rem;
            background-color: transparent;
        }
        
        .table thead {
            display: none;
        }
        
        .table tbody tr {
            display: block;
            margin-bottom: 1rem;
            border: 1px solid #dee2e6;
            border-radius: 0.25rem;
        }
        
        .table tbody td {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem;
            border-top: none;
            border-bottom: 1px solid #dee2e6;
        }
        
        .table tbody td::before {
            content: attr(data-label);
            font-weight: 600;
            padding-right: 1rem;
            text-align: left;
            flex: 0 0 50%;
        }
        
        .table tbody td:last-child {
            border-bottom: none;
        }
    }

    /* Status Badges */
    .badge {
        display: inline-block;
        padding: 0.25em 0.4em;
        font-size: 75%;
        font-weight: 700;
        line-height: 1;
        text-align: center;
        white-space: nowrap;
        vertical-align: baseline;
        border-radius: 0.25rem;
    }

    .badge-success {
        color: #fff;
        background-color: #28a745;
    }

    .badge-warning {
        color: #212529;
        background-color: #ffc107;
    }

    .badge-danger {
        color: #fff;
        background-color: #dc3545;
    }

    .badge-info {
        color: #fff;
        background-color: #17a2b8;
    }
</style>
</head>
<body class="d-flex flex-column h-100 bg-light">
    <!-- Navigation (similar to admin_tenders.php) -->
    
    <section class="py-5">
        <div class="container px-5 mb-5">
            <div class="text-center mb-5">
                <h1 class="display-5 fw-bolder mb-0"><span class="text-gradient d-inline">Bids for Tender</span></h1>
                <h3><?= $tender_data['t_description'] ?></h3>
            </div>
            
            <?php if(isset($error)): ?>
                <div class="alert alert-danger"><?= $error ?></div>
            <?php endif; ?>
            
            <?php if(isset($success)): ?>
                <div class="alert alert-success"><?= $success ?></div>
            <?php endif; ?>
            
            <div class="card">
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Vendor</th>
                                <th>Bid Amount</th>
                                <th>Proposal</th>
                                <th>Submitted</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $bids = mysqli_query($con, "SELECT b.*, u.u_company 
                                                           FROM tbl_bid b
                                                           JOIN tbl_user u ON b.b_vendor_id = u.u_id
                                                           WHERE b.b_tender_id = '$tender_id'");
                                while($bid = mysqli_fetch_assoc($bids)) {
                                    $status = ['Submitted', 'Under Review', 'Accepted', 'Rejected'][$bid['b_status']-1];
                            ?>
                            <tr>
                                <td><?= $bid['u_company'] ?></td>
                                <td>$<?= number_format($bid['b_amount'], 2) ?></td>
                                <td><?= substr($bid['b_proposal'], 0, 50) ?>...</td>
                                <td><?= date('d M Y', strtotime($bid['b_submission_date'])) ?></td>
                                <td><?= $status ?></td>
                                <td>
                                    <?php if($tender_data['t_status'] < 3): ?>
                                        <form method="POST" style="display:inline;">
                                            <input type="hidden" name="bid_id" value="<?= $bid['b_id'] ?>">
                                            <button type="submit" name="award_bid" class="btn btn-success btn-sm">Award</button>
                                        </form>
                                    <?php endif; ?>
                                    <a href="bid_details.php?bid_id=<?= $bid['b_id'] ?>" class="btn btn-info btn-sm">View</a>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
    <!-- Footer remains the same -->
</body>
</html>