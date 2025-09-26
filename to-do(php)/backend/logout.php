<?php
// logout.php - User logout

session_start();
require 'functions.php';

session_destroy();
redirect('../login.php');
?>