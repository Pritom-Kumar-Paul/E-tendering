<?php
// Database configuration
$host = 'localhost'; // or your database host
$db = 'contact_db'; // your database name
$user = 'root'; // your database username
$pass = ''; // your database password

// Create connection
$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize variables
$name = $email = $phone = $message = "";
$successMessage = $errorMessage = "";

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $message = $_POST['message'];

    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO contacts (name, email, phone, message) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $email, $phone, $message);

    // Execute the statement
    if ($stmt->execute()) {
        $successMessage = "Form submission successful!";
    } else {
        $errorMessage = "Error sending message: " . $stmt->error;
    }

    // Close the statement
    $stmt->close();
}

// Close the connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Contact Form</title>
    <link rel="stylesheet" href="style.css" />
</head>
<body>
    <main>
        <section>
            <div>
                <h1>Get in touch</h1>
                <p>Let's work together!</p>
                <?php if ($successMessage): ?>
                    <div class="alert alert-success"><?php echo $successMessage; ?></div>
                <?php endif; ?>
                <?php if ($errorMessage): ?>
                    <div class="alert alert-danger"><?php echo $errorMessage; ?></div>
                <?php endif; ?>
                <form id="contactForm" method="POST" action="">
                    <input type="text" name="name" placeholder="Enter your name..." required />
                    <input type="email" name="email" placeholder="name@example.com" required />
                    <input type="tel" name="phone" placeholder="017********        " required />
                    <textarea name="message" placeholder="Enter your message here..." required></textarea>
                    <button type="submit">Submit</button>
                </form>
            </div>
        </section>
    </main>
</body>
</html>