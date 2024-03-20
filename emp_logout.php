<?php
include("connect.php");
// session_unset();
session_destroy();
header("location:emp_login.php");
?>