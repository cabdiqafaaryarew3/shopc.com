<?php
session_start();
include 'config.php'; // make sure this connects to your database

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Collect and escape form data
    $name   = $conn->real_escape_string($_POST['name']);
    $email  = $conn->real_escape_string($_POST['email']);
    $password = $_POST['password']; // password will be hashed
    $number = $conn->real_escape_string($_POST['number']);
    $street = $conn->real_escape_string($_POST['street']);
    $home   = $conn->real_escape_string($_POST['home']);
    $zone   = $conn->real_escape_string($_POST['zone']);

    // Hash password
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    // Handle image upload
    if(isset($_FILES['file']) && $_FILES['file']['error'] == 0){
        $file_name = time().'_'.basename($_FILES['file']['name']);
        $target_dir = "uploads/";
        if(!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        move_uploaded_file($_FILES['file']['tmp_name'], $target_dir.$file_name);
    } else {
        $file_name = '';
    }

    // Check if email already exists
    $check = $conn->query("SELECT id FROM users WHERE email='$email'");
    if($check->num_rows > 0){
        echo "❌ Email already registered. Try logging in.";
        exit();
    }

    // Insert into database using the exact column names
    $sql = "INSERT INTO users (NAME, email, PASSWORD, NUMBER, street, home, zone, FILE)
            VALUES ('$name', '$email', '$password_hash', '$number', '$street', '$home', '$zone', '$file_name')";

    if($conn->query($sql)){
        // Get inserted user id
        $user_id = $conn->insert_id;
        $_SESSION['user_id'] = $user_id;
        $_SESSION['name'] = $name;

        // Redirect to home page
        header("Location:  index.php");
        exit();
    } else {
        echo "❌ Error: " . $conn->error;
    }
}
?>
