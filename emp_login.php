<?php
include("connect.php");
// Session creation
if (isset($_SESSION["user_name"])) {
  header("location:client_dashboard.php");
}
?>

<?php
function cleanlogin_input($fields)
{
  $fields = trim($fields);
  $fields = stripslashes($fields);
  $fields = str_replace("'", "", $fields);
  $fields = htmlspecialchars($fields);
  return $fields;
}
// $sno = "";
$email = "";
$password = "";
$invalid = false;
// $emailerr = false;
// $passworderr = false;
if (isset($_POST["submit"])) {
  // print_r($_POST);
  // print_r($_POST);
  // echo "I am here";
  $email = cleanlogin_input($_POST['email']);
  $password = $_POST["password"];

  // Fetch the hashed password from the database based on the provided email
  $sql = "SELECT * FROM `login_credentials` WHERE Email = '$email'";
  $result = mysqli_query($conn, $sql);

  if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    // $sno = $row["sno"];
    $hashed_password = $row['Password'];
    $_SESSION['sno'] = $row["sno"];
    if (password_verify($password, $hashed_password)) {
      $_SESSION['user_name'] = $email;    // session create kar lo...
      header("location:client_dashboard.php");
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
        <!-- <div class="error-message-div error-msg"><img src="images/unsucess-msg.png"><strong>Invalid!</strong> username
            or password </div> -->
        <form class="margin_bottom" role="form" action="emp_login.php" method="POST">
          <div class="form-group">
            <label for="exampleInputEmail1">User Name</label>
            <input id="email_input" type="email" class="form-control" name="email" autocomplete="off"
              onblur="validateEmail()" />
            <span class='text_error' id="email_err"></span>
          </div>
          <div class="form-group">
            <label for="exampleInputPassword1">Password<a href="emp_forgot.php" class="forg_pass">Forgot
                Password?</a></label>
            <input id="password_input" type="password" class="form-control" name="password" autocomplete="off" />
            <!-- <p class='text_error'>Invalid Username and Password. </p>  -->
            <?php
            if ($invalid) {
              echo "<p class='text_error'>Invalid Username and Password.</p>";
            }
            ?>
          </div>
          <button type="submit" class="btn_login" name="submit">Login</button>
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