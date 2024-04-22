<?php
include("connect.php");
// session_unset();
// session_destroy();
unset($_SESSION['user_name']);
header("location:emp_login.php");
?>