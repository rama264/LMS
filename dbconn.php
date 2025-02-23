<?php
session_start();
$dbservername = "localhost:3307";
$dbusername = "root";
$dbpassword = "";
$dbdata = "lms";
// Create connection
$conn = mysqli_connect($dbservername, $dbusername, $dbpassword,$dbdata);
// Check connection
if (!$conn) {
    echo "Connected unsuccessfully";
    die("Connection failed: " . mysqli_connect_error());
}
?>