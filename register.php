<?php
// Database configuration
$db_host = 'localhost';
$db_name = 'etendering';
$db_user = 'root'; // Change this to your database username
$db_pass = '';     // Change this to your database password

// Initialize variables
$errors = [];
$success = '';
$first_name = $last_name = $email = $phone = $occupation = $blood_group = $know_swimming = $present_area = $company = $business_type = $tax_id = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and validate inputs
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $occupation = trim($_POST['occupation']);
    $blood_group = trim($_POST['blood_group']);
    $know_swimming = isset($_POST['know_swimming']) ? '1' : '0';
    $present_area = trim($_POST['present_area']);
    $company = trim($_POST['company']);
    $business_type = trim($_POST['business_type']);
    $tax_id = trim($_POST['tax_id']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);
    
    // Basic validation
    if (empty($first_name)) {
        $errors['first_name'] = 'First name is required';
    }
    
    if (empty($last_name)) {
        $errors['last_name'] = 'Last name is required';
    }
    
    if (empty($email)) {
        $errors['email'] = 'Email is required';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Invalid email format';
    }
    
    if (empty($phone)) {
        $errors['phone'] = 'Phone number is required';
    }
    
    if (empty($password)) {
        $errors['password'] = 'Password is required';
    } elseif (strlen($password) < 6) {
        $errors['password'] = 'Password must be at least 6 characters';
    } elseif ($password !== $confirm_password) {
        $errors['confirm_password'] = 'Passwords do not match';
    }
    
    // If no errors, proceed with registration
    if (empty($errors)) {
        try {
            // Connect to database
            $conn = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_pass);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            // Check if email already exists
            $stmt = $conn->prepare("SELECT u_id FROM tbl_user WHERE u_email = :email");
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            
            if ($stmt->rowCount() > 0) {
                $errors['email'] = 'Email already registered';
            } else {
                // Handle file upload
                $image_path = '';
                if (isset($_FILES['user_image']) && $_FILES['user_image']['error'] === UPLOAD_ERR_OK) {
                    $upload_dir = 'uploads/';
                    if (!is_dir($upload_dir)) {
                        mkdir($upload_dir, 0755, true);
                    }
                    
                    $file_ext = pathinfo($_FILES['user_image']['name'], PATHINFO_EXTENSION);
                    $file_name = uniqid() . '.' . $file_ext;
                    $target_path = $upload_dir . $file_name;
                    
                    if (move_uploaded_file($_FILES['user_image']['tmp_name'], $target_path)) {
                        $image_path = $target_path;
                    }
                }
                
                // Hash password
                $hashed_password = md5($password); // Note: In production, use password_hash() instead
                
                // Insert new user (default role is 2 for regular user)
                $stmt = $conn->prepare("INSERT INTO tbl_user (u_first_name, u_last_name, u_email, u_phone, u_occupation, u_blood_group, u_know_swimming, u_present_area, u_company, u_business_type, u_tax_id, u_password, u_image, u_role) 
                                        VALUES (:first_name, :last_name, :email, :phone, :occupation, :blood_group, :know_swimming, :present_area, :company, :business_type, :tax_id, :password, :image, 2)");
                
                $stmt->bindParam(':first_name', $first_name);
                $stmt->bindParam(':last_name', $last_name);
                $stmt->bindParam(':email', $email);
                $stmt->bindParam(':phone', $phone);
                $stmt->bindParam(':occupation', $occupation);
                $stmt->bindParam(':blood_group', $blood_group);
                $stmt->bindParam(':know_swimming', $know_swimming);
                $stmt->bindParam(':present_area', $present_area);
                $stmt->bindParam(':company', $company);
                $stmt->bindParam(':business_type', $business_type);
                $stmt->bindParam(':tax_id', $tax_id);
                $stmt->bindParam(':password', $hashed_password);
                $stmt->bindParam(':image', $image_path);
                
                if ($stmt->execute()) {
                    $success = 'Registration successful!';
                    // Clear form
                    $first_name = $last_name = $email = $phone = $occupation = $blood_group = $know_swimming = $present_area = $company = $business_type = $tax_id = '';
                }
            }
        } catch(PDOException $e) {
            $errors['database'] = 'Database error: ' . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Registration - eTendering System</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background-color: #f5f5f5;
            color: #333;
            line-height: 1.6;
        }
        
        .container {
            max-width: 800px;
            margin: 30px auto;
            padding: 20px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        
        h1 {
            text-align: center;
            margin-bottom: 20px;
            color: #2c3e50;
        }
        
        .form-group {
            margin-bottom: 15px;
        }
        
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: 600;
        }
        
        input[type="text"],
        input[type="email"],
        input[type="tel"],
        input[type="password"],
        select,
        textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }
        
        input[type="checkbox"] {
            margin-right: 10px;
        }
        
        .checkbox-label {
            display: flex;
            align-items: center;
        }
        
        .btn {
            display: inline-block;
            background: #3498db;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            transition: background 0.3s;
        }
        
        .btn:hover {
            background: #2980b9;
        }
        
        .error {
            color: #e74c3c;
            font-size: 14px;
            margin-top: 5px;
        }
        
        .success {
            color: #27ae60;
            text-align: center;
            margin-bottom: 20px;
            font-weight: 600;
        }
        
        .row {
            display: flex;
            flex-wrap: wrap;
            margin: 0 -10px;
        }
        
        .col {
            flex: 1;
            padding: 0 10px;
            min-width: 200px;
        }
        
        @media (max-width: 600px) {
            .col {
                flex: 100%;
                margin-bottom: 15px;
            }
        }
        
        .required:after {
            content: " *";
            color: #e74c3c;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>User Registration</h1>
        
        <?php if ($success): ?>
            <div class="success"><?php echo $success; ?></div>
        <?php endif; ?>
        
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" enctype="multipart/form-data">
            <div class="row">
                <div class="col">
                    <div class="form-group">
                        <label for="first_name" class="required">First Name</label>
                        <input type="text" id="first_name" name="first_name" value="<?php echo htmlspecialchars($first_name); ?>" required>
                        <?php if (isset($errors['first_name'])): ?>
                            <div class="error"><?php echo $errors['first_name']; ?></div>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="col">
                    <div class="form-group">
                        <label for="last_name" class="required">Last Name</label>
                        <input type="text" id="last_name" name="last_name" value="<?php echo htmlspecialchars($last_name); ?>" required>
                        <?php if (isset($errors['last_name'])): ?>
                            <div class="error"><?php echo $errors['last_name']; ?></div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col">
                    <div class="form-group">
                        <label for="email" class="required">Email</label>
                        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
                        <?php if (isset($errors['email'])): ?>
                            <div class="error"><?php echo $errors['email']; ?></div>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="col">
                    <div class="form-group">
                        <label for="phone" class="required">Phone Number</label>
                        <input type="tel" id="phone" name="phone" value="<?php echo htmlspecialchars($phone); ?>" required>
                        <?php if (isset($errors['phone'])): ?>
                            <div class="error"><?php echo $errors['phone']; ?></div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col">
                    <div class="form-group">
                        <label for="occupation" class="required">Occupation</label>
                        <select id="occupation" name="occupation" required>
                            <option value="">Select Occupation</option>
                            <option value="Student" <?php echo ($occupation === 'Student') ? 'selected' : ''; ?>>Student</option>
                            <option value="Job Holder" <?php echo ($occupation === 'Job Holder') ? 'selected' : ''; ?>>Job Holder</option>
                            <option value="Business" <?php echo ($occupation === 'Business') ? 'selected' : ''; ?>>Business</option>
                            <option value="Other" <?php echo ($occupation === 'Other') ? 'selected' : ''; ?>>Other</option>
                        </select>
                        <?php if (isset($errors['occupation'])): ?>
                            <div class="error"><?php echo $errors['occupation']; ?></div>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="col">
                    <div class="form-group">
                        <label for="blood_group" class="required">Blood Group</label>
                        <select id="blood_group" name="blood_group" required>
                            <option value="">Select Blood Group</option>
                            <option value="A+" <?php echo ($blood_group === 'A+') ? 'selected' : ''; ?>>A+</option>
                            <option value="A-" <?php echo ($blood_group === 'A-') ? 'selected' : ''; ?>>A-</option>
                            <option value="B+" <?php echo ($blood_group === 'B+') ? 'selected' : ''; ?>>B+</option>
                            <option value="B-" <?php echo ($blood_group === 'B-') ? 'selected' : ''; ?>>B-</option>
                            <option value="AB+" <?php echo ($blood_group === 'AB+') ? 'selected' : ''; ?>>AB+</option>
                            <option value="AB-" <?php echo ($blood_group === 'AB-') ? 'selected' : ''; ?>>AB-</option>
                            <option value="O+" <?php echo ($blood_group === 'O+') ? 'selected' : ''; ?>>O+</option>
                            <option value="O-" <?php echo ($blood_group === 'O-') ? 'selected' : ''; ?>>O-</option>
                        </select>
                        <?php if (isset($errors['blood_group'])): ?>
                            <div class="error"><?php echo $errors['blood_group']; ?></div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <div class="form-group">
                <label class="checkbox-label">
                    <input type="checkbox" id="know_swimming" name="know_swimming" <?php echo ($know_swimming === '1') ? 'checked' : ''; ?>>
                    Do you know swimming?
                </label>
            </div>
            
            <div class="form-group">
                <label for="present_area" class="required">Present Area</label>
                <textarea id="present_area" name="present_area" rows="2" required><?php echo htmlspecialchars($present_area); ?></textarea>
                <?php if (isset($errors['present_area'])): ?>
                    <div class="error"><?php echo $errors['present_area']; ?></div>
                <?php endif; ?>
            </div>
            
            <div class="row">
                <div class="col">
                    <div class="form-group">
                        <label for="company">Company (if applicable)</label>
                        <input type="text" id="company" name="company" value="<?php echo htmlspecialchars($company); ?>">
                    </div>
                </div>
                <div class="col">
                    <div class="form-group">
                        <label for="business_type">Business Type (if applicable)</label>
                        <input type="text" id="business_type" name="business_type" value="<?php echo htmlspecialchars($business_type); ?>">
                    </div>
                </div>
            </div>
            
            <div class="form-group">
                <label for="tax_id">Tax ID (if applicable)</label>
                <input type="text" id="tax_id" name="tax_id" value="<?php echo htmlspecialchars($tax_id); ?>">
            </div>
            
            <div class="row">
                <div class="col">
                    <div class="form-group">
                        <label for="password" class="required">Password</label>
                        <input type="password" id="password" name="password" required>
                        <?php if (isset($errors['password'])): ?>
                            <div class="error"><?php echo $errors['password']; ?></div>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="col">
                    <div class="form-group">
                        <label for="confirm_password" class="required">Confirm Password</label>
                        <input type="password" id="confirm_password" name="confirm_password" required>
                        <?php if (isset($errors['confirm_password'])): ?>
                            <div class="error"><?php echo $errors['confirm_password']; ?></div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <div class="form-group">
                <label for="user_image">Profile Image</label>
                <input type="file" id="user_image" name="user_image" accept="image/*">
            </div>
            
            <div class="form-group">
                <button type="submit" class="btn">Register</button>
            </div>
        </form>
    </div>
</body>
</html>