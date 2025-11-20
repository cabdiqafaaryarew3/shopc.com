<?php
session_start();
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $conn->real_escape_string($_POST['email']);
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email='$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {
            // Login success
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['name'] = $user['name'];
            header("Location: home.php"); // Redirect to home page
            exit();
        } else {
            $error = "❌ Wrong password!";
        }
    } else {
        $error = "❌ No account found with that email!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login - ShopC</title>
<style>
body { display:flex; justify-content:center; align-items:center; height:100vh; background:linear-gradient(135deg,#6a85b6,#bac8e0); font-family:sans-serif; }
.login-box { background:#eee; padding:30px; border-radius:12px; box-shadow:0 4px 20px rgba(0,0,0,0.2); width:300px; text-align:center; }
.login-box input { width:100%; padding:10px; margin:10px 0; border-radius:20px; border:1px solid #ccc; }
.login-box button { width:100%; padding:10px; border:none; border-radius:20px; background:#4c75af; color:white; font-size:16px; cursor:pointer; }
.login-box button:hover { background:#345c8c; }
.error { color:red; font-weight:bold; }
</style>
</head>
<body>
<div class="login-box">
    <h2>Login</h2>
    <?php if(isset($error)) echo "<p class='error'>$error</p>"; ?>
    <form action="" method="post">
        <input type="email" name="email" placeholder="Emailkaaga" required>
        <input type="password" name="password" placeholder="Passwordkaaga" required>
        <button type="submit">Login</button>
    </form>
    <p>Don't have an account? <a href="sign.html">Sign up here</a></p>
</div>
</body>
</html>
