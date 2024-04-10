<?php 
include("connect.php");
// Session creation
if (isset($_SESSION["user_name"])) {
  header("location:client_dashboard.php");
}
?>
<?php 
function cleanpass_input($fields)
{
    $fields = trim($fields);
    $fields = stripslashes($fields);
    $fields = str_replace("'", "", $fields);
    $fields = htmlspecialchars($fields);
    return $fields;
}
if (isset($_GET["token"])) {
  // echo $_GET["token"];
  $token = $_GET["token"];

  $_SESSION['token_value'] = $token;

  $sql = "SELECT * FROM `security_token` WHERE token_value = '$token'";
  $result = mysqli_query($conn, $sql);
  $row = mysqli_fetch_array($result);
  $token_expiry_time = $row['token_expiry_time'];
  $token_id = $row['token_id'];
  // Convert MySQL date string to DateTime object
  $token_expiry_time = new DateTime($token_expiry_time);

// Get current date and time
  $current_date_time = new DateTime();
  if($current_date_time > $token_expiry_time){
    $_SESSION['flash_message'] = "Session Time Expired";
    header("location:emp_login.php");
    exit();
  }
  if (mysqli_num_rows($result) != 1) {
      // echo"Here I am! hello";
      // die();
      header("location:emp_login.php");
      exit();
  }
}
// echo"Here I am";

$password = "";
$confirm_pass = "";
$error = false;
// $passworderr = false;
// $confirm_pass_err = false;
if (isset($_POST["submit"])) {
  $password = cleanpass_input($_POST["password"]);
  $confirm_password = cleanpass_input($_POST["confirm_password"]);

  if ($password == "" || !preg_match("#^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[^\da-zA-Z\s]).{8,}$#", $password) || strcmp($password, $confirm_password) !== 0) {
    $error = true;
} 
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);
  if (!$error) {
      // echo $token;
      // die();
      $token = $_SESSION['token_value'];
      unset($_SESSION['token_value']);
      
      $sql_1 = "UPDATE `users` SET user_password = '$hashed_password' WHERE user_id = ( SELECT token_user_id FROM `security_token` WHERE `security_token`.`token_value` = '$token');";
      $result_1 = mysqli_query($conn, $sql_1);
      // echo $token;
      $sql_2 = "DELETE FROM `security_token` WHERE `token_value` = '$token'";
      $result_2 = mysqli_query($conn, $sql_2);
      if ($result_1 ) {
			    $_SESSION['flash_message'] = "Password Changed Successfully.";
          header("location:emp_login.php");
          exit();
      } else {
          die(mysqli_error($conn));
      }
  }
}
?>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Employee Login</title>
  <link rel="icon" type="image/x-icon" href="images/arcs_logo.png">

  <link href="css/client_dashboard.css" rel="stylesheet">
</head>

<body>
  <div class="login_section">
    <!-- <div class="wrapper relative"> -->
    <!-- <div style="display:none" class="meassage_successful_login">You have Successfull Edit </div> -->
    <div class="heading-top">
      <div class="logo-cebter"><a href="#"><img src="images/at your service_banner.png"></a></div>
    </div>
    <div class="box_login">
      <div class="outer_div">

        <h2>Change <span>Password</span></h2>
        <?php
            if ($error) {
              echo '<div class="error-message-div error-msg"><img src="images/unsucess-msg.png"><strong>Invalid!</strong> Password </div>';
            }
            ?>
        <!-- <div class="error-message-div error-msg"><img src="images/unsucess-msg.png"><strong>Invalid!</strong> username
            or password </div> -->
        <form class="margin_bottom" onsubmit="return validateForm()" role="form" action="emp_changeforgotpassword.php" method="POST">
          <div class="form-group">
            <label for="exampleInputEmail1">Password</label>
            <input id="password_input" type="password" class="form-control" name="password" autocomplete="off"
              onblur="validatePassword()" placeholder="Password" />
            <span class='text_error' id="passworderr"></span>
          </div>
          <div class="form-group">
            <label for="exampleInputPassword1">Confirm Password</label>
            <input id="confirm_password_input" type="password" class="form-control" name="confirm_password" autocomplete="off" onblur="validateConfirmPassword()" placeholder="Confirm Password" />
            <span class='text_error' id="confirm_password_err"></span>

           
          </div>
          <button type="submit" class="btn_login" name="submit">Submit</button>
        </form>
        <div class="login">
        <p>Back To Login? <a href="emp_login.php">Login</a></p>
        </div>
      </div>
    </div>
  </div>
  <!-- <script src="js/emp_login.js"></script> -->
  <script>
        function validateForm() {
      console.log('validatePassword', validatePassword());
      console.log('validateConfirmPassword', validateConfirmPassword());
      if (!validateEmail() || !validateConfirmPassword()) {
        // console.log("here I am")
        return false;
      }
      // alert("asdfghjk");
      return true;
    }

    function validatePassword() {
    var password = document.getElementById('password_input').value;
    var passwordRegex = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[^\da-zA-Z\s]).{8,}$/;
    if (!passwordRegex.test(password)) {
        document.getElementById("passworderr").innerHTML = "Enter the combination of at least 8 numbers, letters, and punctuation marks.";
        password_input.style.borderColor = "black";
        return false;
    } else {
        document.getElementById("passworderr").innerHTML = "";
        password_input.style.borderColor = "green";
        return true;
    }
}
function validateConfirmPassword() {
    var password = document.getElementById('password_input').value;
    var confirm_password = document.getElementById('confirm_password_input').value;

    if (password !== confirm_password) {
        document.getElementById("confirm_password_err").innerHTML = "Password Missmatched.";
        confirm_password_input.style.borderColor = "black";
        return false;
    } else {
        document.getElementById("confirm_password_err").innerHTML = "";
        confirm_password_input.style.borderColor = "green";
        return true;
    }
}
  </script>
</body>

</html>