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
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <title>E-Tendering | Vendor List</title>
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
            
            .vendor-card {
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
            
            .vendor-logo {
                width: 80px;
                height: 80px;
                object-fit: contain;
                border-radius: 8px;
                border: 1px solid #eee;
                padding: 5px;
                background-color: white;
            }
            
            .award-count {
                display: inline-block;
                width: 30px;
                height: 30px;
                line-height: 30px;
                text-align: center;
                border-radius: 50%;
                background-color: var(--success-color);
                color: white;
                font-weight: bold;
            }
            
            .business-type {
                padding: 0.35em 0.65em;
                font-size: 0.75em;
                font-weight: 600;
                border-radius: 0.25rem;
                background-color: rgba(13, 110, 253, 0.1);
                color: var(--primary-color);
                display: inline-block;
            }
            
            .company-name {
                font-weight: 600;
                color: var(--dark-color);
            }
            
            .contact-email {
                color: var(--primary-color);
                word-break: break-all;
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
                
                .vendor-logo {
                    width: 60px;
                    height: 60px;
                }
            }
            
            @media (max-width: 768px) {
                .table td, .table th {
                    padding: 0.5rem;
                }
                
                .vendor-logo {
                    width: 50px;
                    height: 50px;
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
                        <li class="nav-item"><a class="nav-link" href="admin_tenders.php"><i class="fas fa-list me-1"></i> Tenders</a></li>
                        <li class="nav-item"><a class="nav-link active" href="vendors.php"><i class="fas fa-users me-1"></i> Vendors</a></li>
                        <li class="nav-item"><a class="nav-link" href="logout.php"><i class="fas fa-sign-out-alt me-1"></i> Logout</a></li>
                        <li class="nav-item"><a class="nav-link" href="contact.php"><i class="fas fa-envelope me-1"></i> Contact</a></li>
                    </ul>
                </div>
            </div>
        </nav>

        <section class="py-5">
            <div class="container px-5 mb-5">
                <div class="text-center mb-5 page-title">
                    <h1 class="display-5 fw-bolder mb-0"><span class="text-gradient d-inline">Registered Vendors</span></h1>
                    <p class="lead mt-3">All approved vendors in the system</p>
                </div>
                
                <div class="vendor-card shadow-sm">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Company</th>
                                    <th>Contact</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Business Type</th>
                                    <th>Logo</th>
                                    <th>Awarded Tenders</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $results = mysqli_query($con, "SELECT * FROM tbl_user WHERE u_role = 2");
                                    $counter = 1;
                                    while($row = mysqli_fetch_row($results)) {
                                ?>
                                <tr>
                                    <td><?= $counter ?></td>
                                    <td><span class="company-name"><?= $row[11] ?></span></td>
                                    <td><?= $row[1]." ".$row[2] ?></td>
                                    <td><a href="mailto:<?= $row[3] ?>" class="contact-email"><?= $row[3] ?></a></td>
                                    <td><a href="tel:<?= $row[4] ?>"><?= $row[4] ?></a></td>
                                    <td><span class="business-type"><?= $row[12] ?></span></td>
                                    <td><img src="images/<?= $row[10] ?>" class="vendor-logo" alt="<?= $row[11] ?> logo"></td>
                                    <td>
                                        <?php
                                            $tenders = mysqli_query($con, "SELECT COUNT(t_id) FROM tbl_tender WHERE t_awarded_vendor = '$row[0]'");
                                            $tender_count = mysqli_fetch_row($tenders);
                                            echo '<span class="award-count">'.$tender_count[0].'</span>';
                                        ?>
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