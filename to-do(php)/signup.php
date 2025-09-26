<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
    <h2>Sign Up</h2>
    <?php if (isset($_SESSION['Signup_error'])): ?>
        <div class="alert alert-danger"><?php echo $_SESSION['Signup_error']; session_unset() ?></div>
    <?php endif; ?>
    <form method="POST" action="backend/signup_post.php">
        <div class="mb-3">
            <label>Username</label>
            <input type="text" name="username" class="form-control" >
        </div>
         <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" >
        </div>
        <div class="mb-3">
            <label>Password</label>
            <input type="password" name="password" class="form-control" >
        </div>
        <div class="mb-3">
            <label>Confirm Password</label>
            <input type="password" name="confirm_password" class="form-control" >
        </div>
        <button type="submit" class="btn btn-primary">Register</button>
    </form>
    <p>Already have an account? <a href="login.php">Log in</a></p>
</body>
</html>