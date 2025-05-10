<?php
include("connect.php");
include("functions.php");


// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in and is admin
if (!isset($_SESSION['u_email'])) {
    header("Location: login.php");
    exit();
}

// Get user info with prepared statement
$stmt = mysqli_prepare($con, "SELECT u_id, u_first_name, u_role FROM tbl_user WHERE u_email = ?");
mysqli_stmt_bind_param($stmt, "s", $_SESSION['u_email']);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) != 1) {
    header("Location: login.php");
    exit();
}

$user = mysqli_fetch_assoc($result);
$id = $user['u_id'];
$name = $user['u_first_name'];

// Verify admin role (assuming 1 is admin)
if ($user['u_role'] != 1) {
    header("Location: homepage.php");
    exit();
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    // Sanitize and validate inputs
    $construction = isset($_POST['construction']) ? 1 : 0;
    $supply = isset($_POST['supply']) ? 1 : 0;
    $service = isset($_POST['service']) ? 1 : 0;
    
    $budget = (float) filter_var($_POST['budget'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $location = mysqli_real_escape_string($con, trim($_POST['location']));
    $contactperson = mysqli_real_escape_string($con, trim($_POST['contactperson']));
    $contactnumber = mysqli_real_escape_string($con, trim($_POST['contactnumber']));
    $description = mysqli_real_escape_string($con, trim($_POST['description']));
    $deadline = $_POST['deadline'];
    $status = 1; // Open

    // Validate inputs
    if ($budget <= 0) {
        $error = "Budget must be a positive number";
    } elseif (empty($location) || empty($contactperson) || empty($contactnumber) || empty($description) || empty($deadline)) {
        $error = "All required fields must be filled";
    } elseif (!preg_match('/^\+?[0-9]{10,15}$/', $contactnumber)) {
        $error = "Invalid contact number format";
    } elseif (strtotime($deadline) < time()) {
        $error = "Deadline must be in the future";
    } else {
        // Use prepared statement for insertion
        $insertQuery = "INSERT INTO tbl_tender (
            t_type_construction,
            t_type_supply,
            t_type_service,
            t_budget,
            t_location,
            t_contact_person,
            t_contact_person_phone_number,
            t_description,
            t_deadline,
            t_status
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = mysqli_prepare($con, $insertQuery);
        mysqli_stmt_bind_param($stmt, "iiidsssssi", 
            $construction,
            $supply,
            $service,
            $budget,
            $location,
            $contactperson,
            $contactnumber,
            $description,
            $deadline,
            $status
        );
        
        if (mysqli_stmt_execute($stmt)) {
            $_SESSION['success'] = "Tender created successfully!";
            header("Location: admin_tenders.php");
            exit();
        } else {
            $error = "Error creating tender: " . mysqli_error($con);
            error_log("Database error: " . mysqli_error($con));
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>E-Tendering | Add New Tender</title>
        <!-- Favicon-->
        <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
        <!-- Custom Google font-->
        <link rel="preconnect" href="https://fonts.googleapis.com" />
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@100;200;300;400;500;600;700;800;900&amp;display=swap" rel="stylesheet" />
        <!-- Bootstrap icons-->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css" rel="stylesheet" />
        <!-- Core theme CSS (includes Bootstrap)-->
        <link href="css/styles.css" rel="stylesheet" />
        <link rel="stylesheet" type="text/css" href="css/customstyle.css">
    </head>
    <body class="d-flex flex-column h-100 bg-light">
        <div id="error" style="<?php if($error !=""){ ?> display: block; <?php } ?> "><?php echo $error ?></div>
        <main class="flex-shrink-0">
            <!-- Navigation-->
            <nav class="navbar navbar-expand-lg navbar-light bg-white py-3">
                <div class="container px-5">
                    <a class="navbar-brand" href="index.html"><span class="fw-bolder text-primary">E-Tendering System</span></a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav ms-auto mb-2 mb-lg-0 small fw-bolder">
                            <li class="nav-item"><a class="nav-link" href="add_tender.php">Add Tender</a></li>
                            <li class="nav-item"><a class="nav-link" href="admin_tenders.php">Tenders</a></li>
                            <li class="nav-item"><a class="nav-link" href="vendors.php">Vendors</a></li>
                            <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
                            <li class="nav-item"><a class="nav-link" href="contacts.php">Contact</a></li>
                        </ul>
                    </div>
                </div>
            </nav>
            <!-- Projects Section-->
            <section class="py-5">
                <div class="container px-5 mb-5">
                    <div class="text-center mb-5">
                        <h1 class="display-5 fw-bolder mb-0"><span class="text-gradient d-inline">Create New Tender</span></h1>
                    </div>
                    
                    <div id="formDiv">
                        <form method="POST" action="add_tender.php">
                            <label><strong>Tender Type:</strong></label><br/>
                            <label><input type="checkbox" name="construction"/> Construction</label>&nbsp;
                            <label><input type="checkbox" name="supply"/> Supply</label>&nbsp;
                            <label><input type="checkbox" name="service"/> Service</label><br/><br/>
                
                            <label>Budget (USD)*</label><br/>
                            <input type="number" name="budget" class="inputFieldsTask" style="width: 500px;" step="0.01" min="0" required /><br/><br/>
                
                            <label>Location*</label><br/>
                            <input type="text" name="location" class="inputFieldsTask" style="width: 500px;" required /><br/><br/>

                            <label>Contact Person*</label><br/>
                            <input type="text" name="contactperson" class="inputFieldsTask" style="width: 500px;" required /><br/><br/>

                            <label>Contact Number*</label><br/>
                            <input type="text" name="contactnumber" class="inputFieldsTask" style="width: 500px;" required /><br/><br/>
                
                            <label>Tender Description*</label><br/>
                            <textarea name="description" class="inputFieldsTask" style="width: 500px; height: 150px;" required></textarea><br/><br/>

                            <label>Submission Deadline*</label><br/>
                            <input type="date" name="deadline" class="inputFieldsTask" style="width: 500px;" required /><br/><br/>

                            <input type="submit" class="theButtons" style="width: 200px" name="submit" value="Create Tender"/>
                        </form>
                    </div>
                </div>
            </section>
        </main>
        <!-- Footer-->
        <footer class="bg-white py-1 mt-auto">
            <div class="container px-5">
                <div class="row align-items-center justify-content-between flex-column flex-sm-row">
                    <div class="col-auto"><div class="small m-0">Copyright &copy; E-Tendering System 2024</div></div>
                </div>
            </div>
        </footer>
        <!-- Bootstrap core JS-->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
        <!-- Core theme JS-->
        <script src="js/scripts.js"></script>
    </body>
</html>