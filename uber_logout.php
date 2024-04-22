<?php
include("uber_connect.php");
// session_unset();
// session_destroy();
unset($_SESSION['uber_emp_name']);
header("location:uber_login.php");
?>