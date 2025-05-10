<?php
include("connect.php");
include("functions.php");
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <title>E-Tendering | Home</title>
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
                --gradient-start: #0d6efd;
                --gradient-end: #0dcaf0;
            }
            
            body {
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                background-color: #f5f5f5;
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
            }
            
            .nav-link:hover {
                color: var(--primary-color) !important;
            }
            
            .text-gradient {
                background: linear-gradient(to right, var(--gradient-start), var(--gradient-end));
                -webkit-background-clip: text;
                background-clip: text;
                -webkit-text-fill-color: transparent;
            }
            
            .table {
                background-color: white;
                border-radius: 8px;
                overflow: hidden;
                box-shadow: 0 0 20px rgba(0, 0, 0, 0.05);
            }
            
            .table thead {
                background-color: var(--primary-color);
                color: white;
            }
            
            .table th {
                font-weight: 600;
                padding: 1rem;
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
                transform: translateY(-2px);
            }
            
            .status-open {
                color: #198754;
                font-weight: 600;
            }
            
            .status-closed {
                color: #dc3545;
                font-weight: 600;
            }
            
            section {
                padding: 5rem 0;
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
                background: linear-gradient(to right, var(--gradient-start), var(--gradient-end));
                border-radius: 2px;
            }
        </style>
    </head>
    <body class="d-flex flex-column h-100 bg-light">
        <!-- Navigation -->
        <nav class="navbar navbar-expand-lg navbar-light bg-white py-3">
            <div class="container px-5">
                <a class="navbar-brand" href="homepage.php"><span class="fw-bolder text-primary">E-Tendering System</span></a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav ms-auto mb-2 mb-lg-0 small fw-bolder">
                        <li class="nav-item"><a class="nav-link active" href="homepage.php">Home</a></li>
                        <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
                        <li class="nav-item"><a class="nav-link" href="register.php">Register</a></li>
                        <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>
                    </ul>
                </div>
            </div>
        </nav>

        <section class="py-5">
            <div class="container px-5 mb-5">
                <div class="text-center mb-5 page-title">
                    <h1 class="display-5 fw-bolder mb-0"><span class="text-gradient d-inline">Open Tenders</span></h1>
                    <p class="lead mt-3">Browse and apply for available tenders</p>
                </div>
                
                <div class="card shadow-sm border-0">
                    <div class="card-body p-0">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Tender Type</th>
                                    <th>Budget</th>
                                    <th>Location</th>
                                    <th>Deadline</th>
                                    <th>Description</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $results = mysqli_query($con, "SELECT * FROM tbl_tender WHERE t_status = 1");
                                    $counter = 1;
                                    while($row = mysqli_fetch_row($results)) {
                                ?>
                                <tr>
                                    <td><?= $counter ?></td>
                                    <td>
                                        <?php
                                            if($row[1]=='1') echo "<span class='badge bg-primary me-1'>Construction</span><br>";
                                            if($row[2] == '1') echo "<span class='badge bg-success me-1'>Supply</span><br>";
                                            if($row[3] == '1') echo "<span class='badge bg-info'>Service</span>";
                                        ?>
                                    </td>
                                    <td>$<?= number_format($row[4], 2) ?></td>
                                    <td><?= $row[5] ?></td>
                                    <td><?= date('d M Y', strtotime($row[9])) ?></td>
                                    <td><?= substr($row[8], 0, 50) ?>...</td>
                                    <td>
                                        <span class="status-<?= $row[10] == '1' ? 'closed' : 'open' ?>">
                                            <?= $row[10] == '1' ? ' closed' : 'open' ?>
                                        </span>
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