<?php
require('dbconn.php');
?>

<?php
include 'private/validity1.php'; //  for user validation
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <title>Library Management System</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="keywords" content="Library Member Login Form Widget Responsive, Login Form Web Template, Flat Pricing Tables, Flat Drop-Downs, Sign-Up Web Templates, Flat Web Templates, Login Sign-up Responsive Web Template, Smartphone Compatible Web Template, Free Web Designs for Nokia, Samsung, LG, Sony Ericsson, Motorola Web Design" />
    <script type="application/x-javascript"> 
        addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); 
        function hideURLbar() { window.scrollTo(0,1); }
    </script>
	<link rel="stylesheet" href="css/style.css" type="text/css" media="all">
    <link href="//fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900" rel="stylesheet">


</head>

<body>

<h1 class="title">LIBRARY MANAGEMENT SYSTEM</h1>

    <div class="container">

        <!-- Login Form -->
        <div class="login">
            <h2>Sign In</h2>
            <form action="index.php" method="post">
                <input type="text" Name="RollNo" placeholder="ID number" required=""/>
                <input type="password" Name="Password" placeholder="Password" required=""/>
                <div class="send-button">
                    <input type="submit" name="signin" value="Sign In">
                </div>
            </form>
        </div>

        <!-- Sign Up Form -->
        <div class="register">
            <h2>Sign Up</h2>
            <form action="index.php" method="post">
                <input type="text" Name="Name" placeholder="Name" required>
                <input type="text" Name="Email" placeholder="Email" required>
                <input type="password" Name="Password" placeholder="Password" required>
                <input type="text" Name="PhoneNumber" placeholder="Phone Number" required>
                <input type="text" Name="RollNo" placeholder="ID Number" required=""/>

                <select name="Category" id="Category" style="background-color: dimgray;">
                    <option value="Student">Student</option>
                </select>

                <select name="Department" id="Department" style="background-color: dimgray;">
                    <option value="Science">Science</option>
                    <option value="Management">Management</option>
                    <option value="Political Science">Political Science</option>
                    <option value="Arts and Culture">Arts and Culture</option>
                    <option value="History">History</option>
                    <option value="Education">Education</option>
                    <option value="Agriculture">Agriculture</option>
                </select>
                
                <br><br>
                <div class="send-button">
                    <input type="submit" name="signup" value="Sign Up">
                </div>
            </form>
            <p>By creating an account, you agree to our terms.</p>
        </div>

        <div class="clear"></div>

    </div>

    <div class="footer w3layouts agileits">
        <p>&copy; 2025 Library Management System. All Rights Reserved</p>
    </div>

    <!-- PHP code for login and registration -->
    <?php
    // Sign In Logic
    if (isset($_POST['signin'])) {
        $u = $_POST['RollNo'];
        $p = $_POST['Password'];

        $sql = "SELECT * FROM LMS.user WHERE RollNo='$u'";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        $x = $row['Password'];
        $y = $row['Type'];

        // Check if the password matches
        if (strcasecmp($x, $p) == 0 && !empty($u) && !empty($p)) {
            $_SESSION['RollNo'] = $u; // Store session data

            // Redirect user based on their role
            if ($y == 'Admin') {
                header("Location: admin/index.php");
                exit;
            } elseif ($y == 'Student') {
                header("Location: student/index.php");
                exit;
            } else {
                echo "<script type='text/javascript'>alert('Invalid user role')</script>";
            }
        } else {
            echo "<script type='text/javascript'>alert('Failed to Login! Incorrect ID No or Password')</script>";
        }
    }

    // Sign Up Logic
    if (isset($_POST['signup'])) {
        $name = $_POST['Name'];
        $email = $_POST['Email'];
        $password = $_POST['Password'];
        $mobno = $_POST['PhoneNumber'];
        $rollno = $_POST['RollNo'];
        $category = $_POST['Category'];
        $department = $_POST['Department'];
        $type = 'Student'; // Default type is Student

        // Check if user already exists
        $check_user_sql = "SELECT * FROM LMS.user WHERE RollNo='$rollno' OR EmailId='$email'";
        $check_result = $conn->query($check_user_sql);
        
        if ($check_result->num_rows > 0) {
            echo "<script type='text/javascript'>alert('User already exists')</script>";
        } else {
            // Insert user data into the database
            $sql = "INSERT INTO LMS.user (Name, Type, Category, Department, RollNo, EmailId, MobNo, Password) 
                    VALUES ('$name', '$type', '$category', '$department', '$rollno', '$email', '$mobno', '$password')";

            if ($conn->query($sql) === TRUE) {
                echo "<script type='text/javascript'>alert('Registration Successful')</script>";
            } else {
                echo "<script type='text/javascript'>alert('Error during registration. Please try again later.')</script>";
            }
        }
    }
    ?>

</body>

</html>
