<?php

// Database connection parameters
$host = 'localhost'; // Change this if your database is hosted elsewhere
$username = 'root';
$password = '';
$database = 'user_db';

// TrueNAS storage parameters
$nas_username = 'mlouis';
$nas_dataset = 'dataset1';
$nas_path = '/mnt/pool/dataset1'; // Change this to match your TrueNAS setup

// Connect to the database
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get username and password from the form
    $username = $_POST['username'];
    $password = $_POST['password'];

    session_start();
    $_SESSION['username'] = $_POST['username']; 
    // Query to fetch user from database
    $sql = "SELECT * FROM users WHERE username='$username' AND password='$password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // If user is found, grant access to TrueNAS dataset
        exec("sudo -u $nas_username mkdir -p $nas_path");
        
        // File upload handling
        if(isset($_FILES['file'])) {
            $file_name = $_FILES['file']['name'];
            $file_tmp = $_FILES['file']['tmp_name'];
            $file_destination = "$nas_path/$file_name";
            if(move_uploaded_file($file_tmp, $file_destination)) {
                echo "File uploaded successfully to TrueNAS dataset: $nas_dataset";
                
                header('upload.php');
            } else {
                echo "Error uploading file.";
            }
        } else {
            echo "No file uploaded.";
        }
    } else {
        echo "Invalid username or password";
    }
}

$conn->close();
?>
