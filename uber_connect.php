<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
$conn = new mysqli("localhost", "root", "root", "employee_management");
if (!$conn) {
  die(mysqli_error($conn));
}
session_start();
?>