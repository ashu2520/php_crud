<?php
include ("connect.php");
// Session creation
if (isset($_SESSION["user_name"])) {
  header("location:client_dashboard.php");
}
?>
<script>
      const bc = new BroadcastChannel("test_channel");
      bc.addEventListener("message", (event) => {
        if (event.data == "LOGIN"){
          window.location.reload();
        }
      })
</script>

<?php
function cleanlogin_input($fields)
{
  $fields = trim($fields);
  $fields = stripslashes($fields);
  $fields = str_replace("'", "", $fields);
  $fields = htmlspecialchars($fields);
  return $fields;
}
// $Id = "";
$email = "";
$password = "";
$invalid = false;

if (isset($_POST["submit"])) {

  $email = cleanlogin_input($_POST['email']);
  $password = cleanlogin_input($_POST["password"]);
  $email = filter_var($email, FILTER_SANITIZE_EMAIL);
  
  // Fetch the hashed password from the database based on the provided email
  $sql = "SELECT * FROM `users` WHERE user_email = '$email'";
  $result = mysqli_query($conn, $sql);

  if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    // $Id = $row["Id"];
    $hashed_password = $row['user_password'];
   
    if (password_verify($password, $hashed_password)) {
      $_SESSION['user_name'] = $email;    // session create kar lo...
      $_SESSION['Id'] = $row["user_id"];
      $User_role_id = $row["user_role_id"];
      $_SESSION['User_role_id'] =  $User_role_id;

      // Fetching the Role Name
      $sql_role = "SELECT role_name FROM `roles` WHERE role_id = '$User_role_id'";
      $result_role = mysqli_query($conn, $sql_role);
      $row = mysqli_fetch_array($result_role);
      $_SESSION['role_name'] = $row['role_name'];

      // Fetching the Settings
      $sql_setting = "SELECT * FROM `settings` WHERE setting_id = '1'";
      $result_setting = mysqli_query($conn, $sql_setting);
      if ($result_setting) {
        $row = mysqli_fetch_array($result_setting);
        $_SESSION['num_per_page'] = $row['setting_row_per_page'];
        $_SESSION['link_exp_time'] = $row['setting_token_expiry_time'];
        $_SESSION["date_format"] = $row['setting_date_format'];
      }
      
      // Set Cookies
      if(!empty($_POST['remember_me'])){
        $remember_me = $_POST['remember_me'];
        //  $sql_login = "INSERT INTO `login_records` (login_email, login_password, login_created_at, login_deleted_at) VALUES ('$email', '$hashed_password', CURRENT_TIMESTAMP, DATE_ADD(CURRENT_TIMESTAMP, INTERVAL 24 HOUR))";
        //  $result_role = mysqli_query($conn, $sql_login);
        // set Cookies
        setcookie('email', $email, time()+3600, '/');
        setcookie('password', $password, time()+3600);
        setcookie('remember_me', $remember_me, time()+3600);
      }
      // header("location:client_dashboard.php");
      echo "<script>bc.postMessage('LOGIN'); window.location.href ='client_dashboard.php'; </script>";
      exit();
    } else {
      $invalid = true;
    }
  } else {
    // Email not found in the database
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

        <h2>Admin <span>Login</span></h2>
        <?php
        if ($invalid) {
          echo '<div id="error-message" class="error-message-div error-msg"><img src="images/unsucess-msg.png"><strong>Invalid!</strong> Username
              or Password </div>';
        }
        ?>
        <!-- <div class="error-message-div error-msg"><img src="images/unsucess-msg.png"><strong>Invalid!</strong> username
            or password </div> -->
        <form class="margin_bottom" role="form" action="emp_login.php" method="POST">
          <div class="form-group">
            <label for="exampleInputEmail1">User Name</label>
            <input id="email_input" type="email" class="form-control" name="email" autocomplete="off"
              onblur="validateEmail()" value="<?php if(isset($_COOKIE['email'])) {echo $_COOKIE['email'];}?>"/>
            <span class='text_error' id="email_err"></span>
          </div>
          <div class="form-group">
            <label for="exampleInputPassword1">Password<a href="emp_forgot.php" class="forg_pass">Forgot
                Password?</a></label>
            <input id="password_input" type="password" class="form-control" name="password" autocomplete="off" value="<?php if(isset($_COOKIE['password'])) {echo $_COOKIE['password'];}?>" />
            <!-- <p class='text_error'>Invalid Username and Password. </p>  -->

    <!-- CHECKBOX -->
          </div>
          <div style="display: flex;  margin-top: -5px;" class="form-group">
          <input type="checkbox" value="remember_me" id="remember_me" name="remember_me" <?php if(isset($_COOKIE['remember_me'])) {echo 'checked';}?>> 
          <label for="rememberMe">Remember me</label>
          </div>

          <button style="margin-top: -3px;" type="submit" class="btn_login" name="submit">Login</button>
        </form>
        <div class="login">
          <p>Doesn't have an account yet? <a href="emp_signup.php"> Sign Up</a></p>
          <?php
          if (isset($_SESSION['flash_message'])) {
            $message = $_SESSION['flash_message'];
            unset($_SESSION['flash_message']);
            echo "<span id='flash-message' class='login-flash-message'> $message</span>";
          }
          ?>
        </div>
      </div>
    </div>
  </div>
  <script src="js/emp_login.js"></script>
  <!-- <script>
      const li =document.getElementById("li");
      const bc = new BroadcastChannel("test_channel");
      bc.addEventListener("message", (event) => {
          if (event.data == "LOGOUT"){
              window.location.reload();
          }
      })
      function logoutBC(){
          bc.postMessage("LOGOUT")
          window.location.href ="emp_logout.php"
      }
    </script> -->
</body>

</html>