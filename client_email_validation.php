<?php
include "connect.php";
function clean_email_input($fields)
{
    $fields = trim($fields);
    $fields = stripslashes($fields);
    $fields = str_replace("'", "", $fields);
    $fields = htmlspecialchars($fields);
    return $fields;
}
$email_exist_err = false;
if(isset($_POST['email'])){
    // Check if the email exists in the database
    $email =clean_email_input($_POST['email']);
    $sql_em = "SELECT * FROM `users_list` WHERE Email = '$email'";
    $result_em = mysqli_query($conn, $sql_em);
    $email_exist_err = mysqli_num_rows($result_em) > 0 ? true : false; 

    // Send JSON response indicating whether the email exists or not
    $response = array('exists' => $email_exist_err);
    echo json_encode($response);
}

?>