<!-- // To verify the signup -->
<?php
include ("uber_connect.php");
// Session creation
// if (isset($_SESSION["user_name"])) {
//   header("location:client_dashboard.php");
// }
?>
<?php

if (isset($_GET["token"])) {
    // echo $_GET["token"];
    $token = $_GET["token"];

    $_SESSION['token_value'] = $token;

    $sql = "SELECT * FROM `security_token` WHERE token_value = '$token'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_array($result);
    $token_expiry_time = $row['token_expiry_time'];
    $token_user_email = $row['token_user_email'];
    echo $token_user_email;
    $token_id = $row['token_id'];
    // Convert MySQL date string to DateTime object
    $token_expiry_time = new DateTime($token_expiry_time);

    // Get current date and time
    $current_date_time = new DateTime();

    if (mysqli_num_rows($result) <= 0) {
        // If no data is fetched from the database.
        // Token is defined by the user.
        $_SESSION['flash_message'] = "Unsucess! Invaild Access";
        header("location:uber_signup.php");
        exit();
    } else if ($current_date_time > $token_expiry_time) {
        // Check If user try to verify the account after 48hrs.
        $_SESSION['flash_message'] = "Unsucess! Verification Time Exceed";
        header("location:uber_login.php");
        exit();
    } else {
        // Right User try to access this page
        $sql_1 = "UPDATE employees SET emp_status = 'Active' WHERE emp_email = '$token_user_email'";

        $result_1 = mysqli_query($conn, $sql_1);

        if ($result_1) {
            // Delete token column
            $sql_2 = "DELETE FROM `security_token` WHERE `token_id` = '$token_id'";
            $result_2 = mysqli_query($conn, $sql_2);
            $temp_slug = 'uber_signup';
            $command = "php -r 'require_once(\"connect.php\"); mailer(\"$temp_slug\", \"$token_user_email\", \"\" , \"\");'> /dev/null 2>&1 &";
            exec($command);
            $_SESSION['flash_message'] = "Sucess! Account Verified Successfully";
            echo '<meta http-equiv="refresh" content="0;url=uber_login.php">';
            exit();
        } else {
            $_SESSION['flash_message'] = "Unsucess! Unable To Verify Account";
            header("location:uber_login.php");
            exit();
        }
    }

} else {
    // Try to access the page without verifaction token.
    $_SESSION['flash_message'] = "Unsucess! Invaild Access";
    header("location:uber_login.php");
    exit();
}

?>