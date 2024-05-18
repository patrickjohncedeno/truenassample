<?php
session_start();
// Check if the user is logged in
if(!isset($_SESSION['username'])) {
    // Redirect the user to the login page if not logged in
    header("Location: login.php");
    exit;
}

// TrueNAS storage parameters
$nas_username = 'mlouis';
$nas_dataset = 'dataset1';
$nas_path = '\\\\192.168.249.128\\vidoandmira_pool\\dataset1'; // UNC format with double backslashes


// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // File upload handling
    if(isset($_FILES['file'])) {
        $file_name = $_FILES['file']['name'];
        $file_tmp = $_FILES['file']['tmp_name'];
        $file_destination = "$nas_path/$file_name";
        if(move_uploaded_file($file_tmp, $file_destination)) {
            echo "File uploaded successfully to TrueNAS dataset: $nas_dataset";
        } else {
            echo "Error uploading file.";
        }
    } else {
        echo "No file uploaded.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload File</title>
</head>
<body>
    <h2>Upload File</h2>
    <?php 
    if(isset($_SESSION['username'])) {
        echo "Logged in as: " . $_SESSION['username'] . "<br>";
    } else {
        echo "Not logged in.<br>";
    }
    ?>
    <form action="upload.php" method="POST" enctype="multipart/form-data">
        <input type="file" name="file" required><br>
        <input type="submit" value="Upload">
    </form>
</body>
</html>
