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
    if (event.data == "LOGIN") {
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
$user_status_error = false;
if (isset($_POST["submit"])) {

  $email = cleanlogin_input($_POST['email']);
  $password = cleanlogin_input($_POST["password"]);
  $email = filter_var($email, FILTER_SANITIZE_EMAIL);

  // Email is valid or not
  $sql = "SELECT * FROM `users` WHERE user_email = ?";
  $stmt = mysqli_prepare($conn, $sql);
  mysqli_stmt_bind_param($stmt, "s", $email);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);

  if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    // $Id = $row["Id"];
    $hashed_password = $row['user_password'];
    $user_status = $row['user_status'];

    if (password_verify($password, $hashed_password)) {
      if ($user_status == 'Active') {
        $_SESSION['user_name'] = $email;      // session create kar lo...
        $_SESSION['Id'] = $row["user_id"];    // user_id stored in session...
        $User_role_id = $row["user_role_id"]; //user_role_id stored in session...
        $_SESSION['User_role_id'] = $User_role_id;

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
        if (!empty($_POST['remember_me'])) {
          $remember_me = $_POST['remember_me'];
          //  $sql_login = "INSERT INTO `login_records` (login_email, login_password, login_created_at, login_deleted_at) VALUES ('$email', '$hashed_password', CURRENT_TIMESTAMP, DATE_ADD(CURRENT_TIMESTAMP, INTERVAL 24 HOUR))";
          //  $result_role = mysqli_query($conn, $sql_login);
          // set Cookies
          setcookie('email', $email, time() + 3600, '/');
          setcookie('password', $password, time() + 3600);
          setcookie('remember_me', $remember_me, time() + 3600);
        }
        // header("location:client_dashboard.php");
        echo "<script>bc.postMessage('LOGIN'); window.location.href ='client_dashboard.php'; </script>";
        exit();
      } else {
        $user_status_error = true;
      }
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
  <title>Admin Login</title>
  <link rel="icon" type="image/x-icon" href="images/arcs_logo.png">
  <!-- <i class="fa fa-camera-retro fa-lg"></i> fa-lg -->
  <link href="css/client_dashboard.css" rel="stylesheet">
  <style>
    /* Cusom checkbox CSS */
    .container {
      display: block;
      position: relative;
      padding-left: 20px;
      margin-top: -5px;
      cursor: pointer;
      -moz-user-select: none;
      -ms-user-select: none;
      user-select: none;
    }

    .container input {
      border: 1px solid green;
      position: absolute;
      padding-top: 15px;
      opacity: 0;
      cursor: pointer;
      height: 0;
      width: 0;
    }

    .checkmark {
      border-radius: 3px;
      position: absolute;
      top: 3px;
      left: 0.5px;
      height: 13.5px;
      width: 13.5px;
      background-color: #eee;
    }

    .container input:checked~.checkmark {
      background-color: #FF651B;
    }

    .checkmark:after {
      content: "";
      position: absolute;
      display: none;
    }

    .container input:checked~.checkmark:after {
      font-weight: 100;
      display: block;
    }

    .container .checkmark:after {
      left: 3.9px;
      /* top: 1px; */
      width: 4px;
      height: 8px;
      border: solid white;
      border-width: 0 2.5px 2.5px 0;
      -webkit-transform: rotate(45deg);
      -ms-transform: rotate(45deg);
      transform: rotate(45deg);
    }
  </style>
</head>

<body>
  <div class="login_section">
    <!-- <div class="wrapper relative"> -->
    <!-- <div style="display:none" class="meassage_successful_login">You have Successfull Edit </div> -->
    <div class="heading-top">
      <div class="logo-cebter"><a href="#"><img src="images/at your service_banner.png"></a></div>
    </div>
    <div style="height: fit-content;" class="box_login">
      <div class="outer_div">

        <h2>Admin <span>Login</span></h2>
        <!-- Backend Error Message -->
        <?php
        if ($invalid) {
          echo '<div id="error-message" class="error-message-div error-msg"><img src="images/unsucess-msg.png"><strong>Invalid!</strong> Username
              or Password </div>';
        } else if ($user_status_error) {
          echo '<div id="error-message" class="error-message-div error-msg"><img src="images/unsucess-msg.png"><strong>Invalid!</strong> Your account is not activated. </div>';
        }
        ?>
        <!-- Flash Messages -->
        <?php
        if (isset($_SESSION['flash_message'])) {
          $message = $_SESSION['flash_message'];
          unset($_SESSION['flash_message']);
          echo "<span id='flash-message' class='login-flash-message'> $message</span>";
        }
        // echo "<span id='flash-message' class='login-flash-message'> Sign Up Successful! Please verify your account </span>";
        
        ?>

        <!-- <div class="error-message-div error-msg"><img src="images/unsucess-msg.png"><strong>Invalid!</strong> username
            or password </div> -->
        <form class="margin_bottom" role="form" action="emp_login.php" method="POST">

          <!-- Email -->
          <?php
          $email_1 = isset($_COOKIE['email']) ? $_COOKIE['email'] : (isset($_POST['email']) ? $_POST['email'] : '');
          ?>
          <div class="form-group">
            <label for="exampleInputEmail1">User Name</label>
            <input id="email_input" type="email" class="form-control" name="email" autocomplete="off"
              onblur="validateEmail()" value="<?php echo $email_1 ?>" />
            <span class='text_error' id="email_err"></span>
          </div>

          <!-- Password -->
          <?php
          $password_1 = isset($_COOKIE['password']) ? $_COOKIE['password'] : (isset($_POST['password']) ? $_POST['password'] : '');
          ?>
          <div class="form-group">
            <label for="exampleInputPassword1">Password<a href="emp_forgot.php" class="forg_pass">Forgot
                Password?</a></label>
            <input id="password_input" type="password" class="form-control" name="password" autocomplete="off"
              value="<?php echo $password_1 ?>" />
            <!-- <p class='text_error'>Invalid Username and Password. </p>  -->
          </div>

          <!-- CHECKBOX -->
          <div style="display: flex;  margin-top: -5px;" class="form-group">
            <label class="container"> Remember me
              <input type="checkbox" value="remember_me" id="remember_me" name="remember_me" <?php if (isset($_COOKIE['remember_me'])) {
                echo 'checked';
              } ?>>
              <span class="checkmark"></span>
            </label>
          </div>

          <button style="margin-top: -3px;" type="submit" class="btn_login" name="submit">Login</button>
        </form>
        <div class="login">
          <p>Doesn't have an account yet? <a href="emp_signup.php"> Sign Up</a></p>

        </div>
      </div>
    </div>
  </div>
  <script src="js/emp_login.js"></script>
</body>

</html>