<?php
include ("connect.php");
// Session creation
if (isset($_SESSION["user_name"])) {
  header("location:client_dashboard.php");
}
?>
<?php
$invalid = false;
$email = "";
$subject = "";
$body = "";
function cleanlogout_input($fields)
{
  $fields = trim($fields);
  $fields = stripslashes($fields);
  $fields = str_replace("'", "", $fields);
  $fields = htmlspecialchars($fields);
  return $fields;
}

if (isset($_POST["submit"])) {
  $email = cleanlogout_input($_POST['email']);
  $sql = "SELECT * FROM `users` WHERE user_email = '$email'";
  $result = mysqli_query($conn, $sql);

  if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_array($result);
    $user_id = $row['user_id'];

    // Fetching Email Subject & Content...
    // $sql_content = "SELECT temp_subject, temp_content FROM email_templates WHERE temp_slug = 'forgot_password'";
    // $result_content = mysqli_query($conn, $sql_content);
    // $row = mysqli_fetch_array($result_content);

    // $subject = $row["temp_subject"];
    // $body = $row["temp_content"];

    $_SESSION['flash_message'] = "Change Password link sent to your mail.";

    // Random String Generator
    $randomString = uniqid(); // Generate a random string
    $randomHash = md5($randomString); // Generate MD5 hash of the random string
    // $randomHash;

    // TOKEN Expiry Time...
    $sql_setting = "SELECT * FROM `settings` WHERE setting_id = 1";
    $result_setting = mysqli_query($conn, $sql_setting);
    if ($result_setting) {
      $row = mysqli_fetch_assoc($result_setting);
    $link_exp_time = $row['setting_token_expiry_time'];
    }

    $sql_token = "INSERT INTO `security_token` (`token_user_id`, `token_type`, `token_value`, `token_expiry_time`, `token_created_at`, `token_updated_at`) VALUES ('$user_id', 'Reset Password', '$randomHash',  DATE_ADD(CURRENT_TIMESTAMP, INTERVAL $link_exp_time MINUTE), CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)";
    $result_token = mysqli_query($conn, $sql_token);


    // Calling the function for mailing...
    $temp_slug = 'forgot_password';
    // mailer($temp_slug, $email);
    $command = "php -r 'require_once(\"connect.php\"); mailer(\"$temp_slug\", \"$email\", \"\" , \"$randomHash\");'> /dev/null 2>&1 &";
    // var_dump($command);
    // Execute the command
    exec($command);
    echo '<meta http-equiv="refresh" content="0;url=emp_login.php">';

  } else {
    $invalid = true;
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
    <div style="height: 300px;" class="box_login">
      <div class="outer_div">

        <h2>Forgot <span>Password</span></h2>
        <?php
        if ($invalid) {
          echo '<div class="error-message-div error-msg"><img src="images/unsucess-msg.png"><strong>Invalid!</strong> Username
              or Password </div>';
        }
        ?>
        <!-- <div class="error-message-div error-msg"><img src="images/unsucess-msg.png"><strong>Invalid!</strong> username
            or password </div> -->
        <form class="margin_bottom" onsubmit="return validateForm()" role="form" action="emp_forgot.php" method="POST">
          <div class="form-group">
            <label for="exampleInputEmail1">User Name</label>
            <input id="email_input" type="email" class="form-control" name="email" autocomplete="off"
              onblur="validateEmail()" placeholder="Email" />
            <span class='text_error' id="email_err"></span>
          </div>

          <button type="submit" class="btn_login" name="submit">Submit</button>
        </form>
        <div class="login">
          <p>Back To Login? <a href="emp_login.php">Login</a></p>
        </div>
      </div>
    </div>
  </div>

  <script>
    function validateForm() {
      console.log('validateEmail', validateEmail());
      if (!validateEmail()) {
        // console.log("here I am")
        return false;
      }
      // alert("asdfghjk");
      return true;
    }
   
    function validateEmail(){
      var email = document.getElementById("email_input").value.trim();
        var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        if (email === "" || !emailRegex.test(email)) {
          document.getElementById("email_err").innerHTML = "Invalid Username";
          email_input.style.borderColor = "black";
          return false;
        }
        document.getElementById("email_err").innerHTML = "";
        email_input.style.borderColor = "green";
        return true;
    }
  </script>
</body>

</html>