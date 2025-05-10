<?php
include("connect.php");
include("functions.php");

$title = $_SESSION['u_email'];

$result = mysqli_query($con, "SELECT u_id, u_first_name FROM tbl_user WHERE u_email='$title'");

while ($row=mysqli_fetch_row($result)) {
    $id = $row[0];
    $name = $row[1];
}

if($title == NULL)
    header("location: login.php");

$error = "";


if(isset($_GET['delete_success'])) {
    echo '<div class="alert alert-success alert-dismissible fade show text-center" role="alert">
            Tender deleted successfully!
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>';
}

if(isset($_GET['delete_error'])) {
    echo '<div class="alert alert-danger alert-dismissible fade show text-center" role="alert">
            Error deleting tender. Please try again.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>';
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>E-Tendering | Tenders List</title>
        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        <style>
            :root {
                --primary-color: #0d6efd;
                --secondary-color: #6c757d;
                --light-color: #f8f9fa;
                --dark-color: #212529;
                --success-color: #198754;
                --warning-color: #ffc107;
                --danger-color: #dc3545;
                --info-color: #0dcaf0;
            }
            
            body {
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                background-color: #f8f9fa;
            }
            
            .navbar {
                box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            }
            
            .navbar-brand {
                font-weight: 700;
                font-size: 1.5rem;
            }
            
            .nav-link {
                font-weight: 600;
                padding: 0.5rem 1rem;
                transition: all 0.3s;
                color: var(--dark-color);
            }
            
            .nav-link:hover {
                color: var(--primary-color) !important;
            }
            
            .text-gradient {
                background: linear-gradient(to right, var(--primary-color), var(--info-color));
                -webkit-background-clip: text;
                background-clip: text;
                -webkit-text-fill-color: transparent;
            }
            
            .table-container {
                background-color: white;
                border-radius: 10px;
                box-shadow: 0 0 20px rgba(0, 0, 0, 0.05);
                overflow: hidden;
                padding: 0;
            }
            
            .table {
                margin-bottom: 0;
            }
            
            .table thead {
                background-color: var(--primary-color);
                color: white;
            }
            
            .table th {
                font-weight: 600;
                padding: 1rem;
                vertical-align: middle;
                white-space: nowrap;
            }
            
            .table td {
                padding: 1rem;
                vertical-align: middle;
            }
            
            .table tbody tr {
                transition: all 0.2s;
            }
            
            .table tbody tr:hover {
                background-color: rgba(13, 110, 253, 0.05);
            }
            
            .status-open {
                color: var(--success-color);
                font-weight: 600;
            }
            
            .status-closed {
                color: var(--danger-color);
                font-weight: 600;
            }
            
            .status-awarded {
                color: var(--warning-color);
                font-weight: 600;
            }
            
            .status-completed {
                color: var(--info-color);
                font-weight: 600;
            }
            
            .badge-type {
                padding: 0.35em 0.65em;
                font-size: 0.75em;
                font-weight: 600;
                border-radius: 0.25rem;
                margin-right: 0.5rem;
                margin-bottom: 0.3rem;
                display: inline-block;
            }
            
            .construction {
                background-color: rgba(13, 110, 253, 0.1);
                color: var(--primary-color);
            }
            
            .supply {
                background-color: rgba(25, 135, 84, 0.1);
                color: var(--success-color);
            }
            
            .service {
                background-color: rgba(13, 202, 240, 0.1);
                color: var(--info-color);
            }
            
            .action-btn {
                padding: 0.375rem 0.75rem;
                font-size: 0.875rem;
                margin: 0.1rem;
                white-space: nowrap;
            }
            
            section {
                padding: 3rem 0;
            }
            
            .page-title {
                position: relative;
                margin-bottom: 3rem;
            }
            
            .page-title:after {
                content: '';
                position: absolute;
                bottom: -10px;
                left: 50%;
                transform: translateX(-50%);
                width: 80px;
                height: 4px;
                background: linear-gradient(to right, var(--primary-color), var(--info-color));
                border-radius: 2px;
            }
            
            .table-responsive {
                overflow-x: auto;
            }
            
            @media (max-width: 992px) {
                .table td, .table th {
                    padding: 0.75rem;
                    font-size: 0.9rem;
                }
                
                .action-btn {
                    padding: 0.25rem 0.5rem;
                    font-size: 0.8rem;
                }
            }
        </style>
    </head>
    <body class="d-flex flex-column h-100 bg-light">
        <!-- Navigation -->
        <nav class="navbar navbar-expand-lg navbar-light bg-white py-3">
            <div class="container px-5">
                <a class="navbar-brand" href="index.html"><span class="fw-bolder text-primary">E-Tendering System</span></a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav ms-auto mb-2 mb-lg-0 small fw-bolder">
                        <li class="nav-item"><a class="nav-link" href="add_tender.php"><i class="fas fa-plus-circle me-1"></i> Add Tender</a></li>
                        <li class="nav-item"><a class="nav-link active" href="admin_tenders.php"><i class="fas fa-list me-1"></i> Tenders</a></li>
                        <li class="nav-item"><a class="nav-link" href="vendors.php"><i class="fas fa-users me-1"></i> Vendors</a></li>
                        <li class="nav-item"><a class="nav-link" href="logout.php"><i class="fas fa-sign-out-alt me-1"></i> Logout</a></li>
                        <li class="nav-item"><a class="nav-link" href="contacts.php"><i class="fas fa-envelope me-1"></i> Contact</a></li>
                    </ul>
                </div>
            </div>
        </nav>

        <section class="py-5">
            <div class="container px-5 mb-5">
                <div class="text-center mb-5 page-title">
                    <h1 class="display-5 fw-bolder mb-0"><span class="text-gradient d-inline">Tender Management</span></h1>
                    <p class="lead mt-3">Manage all tenders in the system</p>
                </div>
                
                <div class="table-container shadow-sm">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Tender Type</th>
                                    <th scope="col">Budget</th>
                                    <th scope="col">Location</th>
                                    <th scope="col">Contact Person</th>
                                    <th scope="col">Contact Number</th>
                                    <th scope="col">Description</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Awarded Vendor</th>
                                    <th scope="col">Actions</th>                            
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $results = mysqli_query($con, "SELECT * FROM tbl_tender WHERE t_status != 4");
                                    $counter = 1;
                                    while($row = mysqli_fetch_row($results)) {
                                ?>
                                <tr>
                                    <th scope="row"><?= $counter ?></th>
                                    <td>
                                        <?php
                                            if($row[1]=='1') echo "<span class='badge-type construction'><i class='fas fa-building me-1'></i>Construction</span><br>";
                                            if($row[2] == '1') echo "<span class='badge-type supply'><i class='fas fa-truck me-1'></i>Supply</span><br>";
                                            if($row[3] == '1') echo "<span class='badge-type service'><i class='fas fa-concierge-bell me-1'></i>Service</span>";
                                        ?>
                                    </td>
                                    <td>$<?= number_format($row[4], 2) ?></td>
                                    <td><?= $row[5] ?></td>
                                    <td><?= $row[6] ?></td>
                                    <td><?= $row[7] ?></td>
                                    <td><?= substr($row[8], 0, 50) ?>...</td>
                                    <td>
                                        <?php
                                            if($row[10] == '1') echo "<span class='status-open'><i class='fas fa-lock-open me-1'></i>Open</span>";
                                            else if($row[10] == '2') echo "<span class='status-closed'><i class='fas fa-lock me-1'></i>Closed</span>";
                                            else if($row[10] == '3') echo "<span class='status-awarded'><i class='fas fa-award me-1'></i>Awarded</span>";
                                            else if($row[10] == '4') echo "<span class='status-completed'><i class='fas fa-check-circle me-1'></i>Completed</span>";
                                        ?>    
                                    </td>
                                    <td>
                                        <?php
                                            if ($row[9] == 0) {
                                                echo "<span class='text-secondary'>Not Awarded</span>";
                                            } else {
                                                $vendor = mysqli_query($con, "SELECT u_company FROM tbl_user WHERE u_id = '$row[9]'");
                                                $vendor_row = mysqli_fetch_row($vendor);
                                                echo "<span class='fw-bold'>" . $vendor_row[0] . "</span>";
                                            }
                                        ?>
                                    </td>
                                    <td>
                                       
                                        <div class="d-flex flex-wrap">
                                            <a href="update.php?tender_id=<?= $row[0] ?>" class="btn btn-sm btn-dark action-btn"><i class="fas fa-edit me-1"></i>Update</a>
                                            <a href="bids.php?tender_id=<?= $row[0] ?>" class="btn btn-sm btn-primary action-btn"><i class="fas fa-eye me-1"></i>Bids</a>
                                            <a href="delete_tender.php?tender_id=<?= $row[0] ?>" class="btn btn-sm btn-danger action-btn" onclick="return confirm('Are you sure you want to delete this tender?')"><i class="fas fa-trash-alt me-1"></i>Delete</a>
                                        </div>

                                    </td>
                                </tr>
                                <?php $counter++; } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>

        <!-- Bootstrap JS Bundle with Popper -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>