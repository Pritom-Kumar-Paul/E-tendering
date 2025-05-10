<?php
	function email_exists($email, $con)
	{
		$result = mysqli_query($con, "select u_id from tbl_user where u_email = '$email'");
		if(mysqli_num_rows ($result) == 1)
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	function logged_in()
	{
		if(isset($_SESSION['email']) || isset($_COOKIE['email']))
		{
			return true;

		}
		else
		{
			return false;
		}
	}

	function auto_logout() {
    // Set timeout period in seconds (e.g., 30 minutes)
    $inactive = 1800; 
    
    // Check if timeout variable is set
    if (isset($_SESSION['timeout'])) {
        // Calculate the session's lifetime
        $session_life = time() - $_SESSION['timeout'];
        if ($session_life > $inactive) {
            session_unset();
            session_destroy();
            // Clear remember me cookie if set
            if (isset($_COOKIE['email'])) {
                setcookie('email', '', time()-3600, '/');
            }
            header("Location: login.php"); // Redirect to login page
            exit();
        }
    }
    $_SESSION['timeout'] = time();
}

?>
