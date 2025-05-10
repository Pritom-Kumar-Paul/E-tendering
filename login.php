<?php
    
    include("connect.php");
    include("functions.php");
    //include("logout.php");

    if(logged_in())
    {
        header("location: homepage.php");
        exit();
    }

    $error = "";

    if(isset($_POST['submit']))
    {
        $u_email = $_POST['email'];
        $u_password = $_POST['password'];
        $conditions = isset($_POST['conditions']);
        

        //  PLEASE READ AND UNDERSTAND THE CONDITIONS VERY CAREFULLY.
        if (email_exists($u_email, $con)) 
        {
            $passFromDB = mysqli_query ($con, "select u_password from tbl_user where u_email = '$u_email'");
            $role = mysqli_query($con, "select u_role from tbl_user where u_email = '$u_email'");
            $row_role = mysqli_fetch_row($role);
            $act_role = $row_role[0];
            $retrivePass = mysqli_fetch_assoc($passFromDB);
            
            if(md5($u_password) !== $retrivePass['u_password'])
            {
                $error = 'Password is incorrect';
            }
            else
            {
                $_SESSION ['u_email'] = $u_email;
                if($conditions == 'on')
                {
                    setcookie("u_email", $u_email, time() + 3600);
                }
                if($act_role == 1)
                    header("location: admin_tenders.php");
                else if ($act_role == 2)
                    header("location: vendor_dashboard.php");
                else
                    header("location: homepage.php");
            }
        }
        else
        {
            $error = "Email doesn't exist!";
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
        <title>E Tendering</title>
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
                    <a class="navbar-brand" href="homepage.php"><span class="fw-bolder text-primary">Easy Tendering</span></a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav ms-auto mb-2 mb-lg-0 small fw-bolder">
                            <li class="nav-item"><a class="nav-link" href="homepage.php">Home</a></li>
                            <li class="nav-item"><a class="nav-link" href="register.php">Registration</a></li>
                            <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>
                        </ul>
                    </div>
                </div>
            </nav>
            <!-- Projects Section-->
            <section class="py-5">
                <div class="container px-5 mb-5">
                    <div class="text-center mb-5">
                        <h1 class="display-5 fw-bolder mb-0"><span class="text-gradient d-inline">USER LOGIN</span></h1>
                    </div>
                    
                    <div id="formDiv">
                        <form method="POST" action="login.php">

                            <label>Email</label><br/>
                            <input type="text" name="email" class="inputFields" required /><br/><br/>

                            <label>Password</label><br/>
                            <input type="password" name="password" class="inputFields" required /><br/><br/>
                
                            <label><input type="checkbox" name="conditions"/> Keep me logged in</label><br/><br/>
                
                            <input type="submit" class="theButtons" name="submit"/>
                        </form>
                    </div>

                </div>
            </section>
            <!-- Call to action section-->
        </main>
        <!-- Footer-->
        <footer class="bg-white py-1 mt-auto">
            <div class="container px-5">
                <div class="row align-items-center justify-content-between flex-column flex-sm-row">
                    <div class="col-auto"><div class="small m-0">Copyright &copy; Grpup 3 2024</div></div>
                </div>
            </div>
        </footer>
        <!-- Bootstrap core JS-->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
        <!-- Core theme JS-->
        <script src="js/scripts.js"></script>
    </body>
</html>
