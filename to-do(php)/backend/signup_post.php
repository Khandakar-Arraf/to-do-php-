<?php
// register.php - User registration

session_start();
//session_destroy();
require 'config.php';
require 'functions.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = validateInput($_POST['username']);
    $email = validateInput($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
 

    if (empty($username) || empty($password) || empty($confirm_password)) {
        $_SESSION['Signup_error'] = "All fields are required.";
        //header('loaction:../signup.php');
        redirect('../signup.php');
    } 
    elseif ($password !== $confirm_password) {
        $_SESSION['Signup_error'] = "Passwords do not match.";
        //header('loaction:../signup.php');
        redirect('../signup.php');
    } 
    elseif (strlen($password) < 6 && !preg_match('/[A-Z]/', $password)&& !preg_match('/[a-z]/', $password)) {
        $_SESSION['Signup_error'] = "Password must be at least 6 characters and a upper case and a lower case.";
        redirect('../signup.php');

    }
    else {
        // Check if username exists
        $stmt = $pdo->prepare("SELECT email FROM users WHERE name = :name");
        $stmt->bindParam(':name', $username);
        $stmt->execute();
        $records = $stmt->rowCount();
        if ($records>0) {
          
            $_SESSION['Signup_error'] = "email already taken.";
            redirect('../signup.php');
        } else {

            print_r($records);
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (name,email, password) VALUES (?, ?,?)");
            $stmt->execute([$username,$email, $hashed_password]);
            redirect('../login.php');
        }
    }
}
?>

