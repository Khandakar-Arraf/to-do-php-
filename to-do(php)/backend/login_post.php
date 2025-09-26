<?php
// login.php - User login

session_start();
require 'config.php';
require 'functions.php';

if (isLoggedIn()) {
    redirect('tasks.php');
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $password = $_POST['password'];

    if (empty($name) || empty($password)) {
       $_SESSION['error'] = "All fields are required.";
         redirect('../login.php');
    } else {

        $stmt = $pdo->prepare("SELECT * FROM users WHERE name = :name");
       
        $stmt->bindParam(':name', $name);
        $stmt->execute();
       
     
        $user = $stmt->fetch();
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            print_r($_SESSION['user_id']);
            header('Location: ../tasks.php');
        } else {
          
            $_SESSION['error'] = "Invalid username or password.";
            redirect('../login.php');
        }
    }
}

?>