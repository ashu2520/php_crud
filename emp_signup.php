<?php
include "connect.php";
if (isset($_SESSION["user_name"])) {
  header("location:client_dashboard.php");
  exit();
}
?>
<?php
function cleansignup_input($fields)
{
  $fields = trim($fields);
  $fields = stripslashes($fields);
  $fields = htmlspecialchars($fields);
  $fields = str_replace("'", "", $fields);
  return $fields;
}
$name = "";
$email = "";
$mobile = "";
$gender = "";
// $country = "";
// $state = "";
$position = "";
$password = "";
$confirm_pass = "";
$terms_cond = "";
$error = false;
$nameerr = false;
$emailerr = false;
$mobilerr = false;
$gender_error = false;
$passworderr = false;
$confirm_pass_err = false;
// print_r($_POST);

if (isset($_POST["name"]) && isset($_POST["email"]) && isset($_POST["mobile"]) && isset($_POST["password"]) && isset($_POST["confirm_pass"]) && isset($_POST["gender"]) && isset($_POST['User_type'])) {
  #Getting data from request
  // print_r($_POST);

  $name = cleansignup_input($_POST['name']);
  $email = cleansignup_input($_POST['email']);
  $mobile = cleansignup_input($_POST['mobile']);
  $password = cleansignup_input($_POST["password"]);
  $confirm_pass = cleansignup_input($_POST["confirm_pass"]);
  $gender = $_POST["gender"];
  // $country = $_POST["country"];
  // $state = $_POST["state"];
  $position = $_POST["User_type"];
  if (isset($_POST['terms_cond'])) {
    $terms_cond = "yes";
  }

  if (!preg_match("/^[a-zA-Z\s'-]+$/", $name) || $name == "") {
    $nameerr = true;
  }
  if (!preg_match("/^[0-9]{10}$/", $mobile) || $mobile == "") {
    $mobilerr = true;
  }

  # Email check
  $sql_em = "SELECT * FROM `users` WHERE user_email = '$email'";
  $result_em = mysqli_query($conn, $sql_em);
  if (!filter_var($email, FILTER_VALIDATE_EMAIL) || !(isset($email)) || $email == "" || mysqli_num_rows($result_em) > 0 || !preg_match("#^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$#", $email)) {
    $emailerr = true;
  }
  if ($password == "" || !preg_match("#^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[^\da-zA-Z\s]).{8,}$#", $password)) {

    $passworderr = true;
  }
  if (strcmp($password, $confirm_pass) !== 0 || $confirm_pass == "") {
    $confirm_pass_err = true;
  }
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);
  if (!$nameerr && !$emailerr && !$mobilerr && !$passworderr && !$confirm_pass_err) {
    $sql = "INSERT INTO `users` (user_name, user_mobile, user_email, user_gender, user_type, user_password, user_terms_cond, user_created_at, user_updated_at) VALUES ('$name', '$mobile', '$email', '$gender', '$position', '$hashed_password', '$terms_cond', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)";
    $result = mysqli_query($conn, $sql);
    // echo "here";
    if ($result) {
      $sql_email = "SELECT temp_subject, temp_content FROM email_templates WHERE temp_slug = 'sign_up'";
      $result_email = mysqli_query($conn, $sql_email);
      $row = mysqli_fetch_array($result_email);

      $subject = $row["temp_subject"];
      $body = $row["temp_content"];
      // Calling the function for mailing...
      mailer($email, $subject, $body, $name);  // present in connect.php
      // if($mail_send){
        // header("location:emp_login.php");
        echo '<meta http-equiv="refresh" content="0;url=emp_login.php">';
        exit();
      // }
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
  <link href="css/client_dashboard.css" rel="stylesheet">
</head>

<body>
  <div class="login_section">
    <!-- <div class="wrapper relative"> -->
    <!-- <div style="display:none" class="meassage_successful_login">You have Successfull Edit </div> -->
    <div class="heading-top">
      <div class="logo-cebter"><a href="#"><img src="images/at your service_banner.png"></a></div>
    </div>
    <div class="box_signup">
      <div class="outer_div">

        <h2>Admin <span>Registration</span></h2>
        <?php
        if ($error) {
          echo '<div class="error-message-div error-msg"><img src="images/unsucess-msg.png"><strong>UnSucess!</strong> Your Message has not been Send </div>';
        } else if ($nameerr) {
          echo '<div class="error-message-div error-msg"><img src="images/unsucess-msg.png"><strong>UnSucess!</strong> Name Error</div>';
        } else if ($mobilerr) {
          echo '<div class="error-message-div error-msg"><img src="images/unsucess-msg.png"><strong>UnSucess!</strong> Mobile Error</div>';
        } else if ($emailerr) {
          echo '<div class="error-message-div error-msg"><img src="images/unsucess-msg.png"><strong>UnSucess!</strong> Email Already Exist</div>';
        } else if ($passworderr || $confirm_pass_err) {
          echo '<div class="error-message-div error-msg"><img src="images/unsucess-msg.png"><strong>UnSucess!</strong> Invalid Password Type</div>';
        }
        ?>
        <form id="main" class="margin_bottom" role="form" onsubmit="validateForm(); return false;"
          action="emp_signup.php" method="POST">
          <div class="form-group">
            <label for="Name" class="labels">Name</label>
            <input id="name_input" type="text" class="form-control" name="name" placeholder="Name" autocomplete="off"
              oninput="validateName()">
            <span class='text_error' id="name_err"></span>
          </div>

          <div class="form-group">
            <label for="Mobile" class="labels">Mobile Number</label>
            <input id="mobile_input" type="number" class="form-control" name="mobile" placeholder="Mobile Number"
              autocomplete="off" oninput="validateMobileNumber()">
            <span class='text_error' id="mobile_error"></span>
          </div>

          <div class="form-group">
            <label for="exampleInputEmail1">Email</label>
            <input id="email_input" type="email" class="form-control" name="email" autocomplete="off"
              placeholder="Email" onblur="validateEmail()" />
            <span class='text_error' id="email_err"></span>
          </div>

          <div class="form-group">
            <label class="labels">Select Gender</label>
            <input id="gender_male" class="rad_opt" checked type="radio" name="gender" oninput="vaildategender()"
              value="Male">
            <span class="rad_text"> Male</span>
            <input id="gender_female" class="rad_opt" type="radio" name="gender" oninput="vaildategender()"
              value="female">
            <span class="rad_text"> Female</span>
            <span class='text_error' id="gender_error"></span>
          </div>

          <!-- 
          <div class="form-group">
            <div class="select_option">

              <label class="labels">Country And State</label>
              <select id="country_select" class="form-select country" aria-label="Default select example" name="country"
                onchange="loadStates()">
                <option>Select Country</option>
              </select>

              <select id="state_select" class="form-select state" aria-label="Default select example" name="state"
                onblur="validatelocation()">
                <option>Select State</option>
              </select>
              <span class='text_error' id="location_error" ></span>

            </div>
          </div> -->

          <div class="form-group">
            <label for="Position">Position</label>
            <select id="User_type_input" class="form-select" name="User_type" autocomplete="off"
              onblur="validatePosition()">
              <option>Select Position</option>              
              <option>AIML</option>
              <option>Backend</option>
              <option>Cyber Security</option>
              <option>Data Scientist</option>
              <option>Devops</option>
              <option>Frontend</option>
              <option>Full Stack</option>
            </select>
            <span class='text_error' id="position_err"></span>
          </div>


          <div class="form-group">
            <label for="password" class="labels">Password</label>
            <input type="password" id="password_input" class="form-control" name="password" placeholder="Password"
              autocomplete="off" oninput="validatePassword()">
            <span class='text_error' id="passworderr"></span>

          </div>

          <div class="form-group">
            <label for="Password" class="labels">Confirm Password</label>
            <input type="password" class="form-control" id="confirm_password_input" name="confirm_pass"
              placeholder="Confirm Password" autocomplete="off" oninput="validateConfirmPassword()">
            <span class='text_error' id="confirm_password_err"></span>
          </div>

          <div class="form-group">
            <div class="check_box">
              <input type="checkbox" name="terms_cond" value="yes">
              <label for="terms">I agree on the terms and conditions.</label>
            </div>
          </div>

          <button type="submit" class="btn_login" name="submitasd">Sign Up</button>
        </form>
        <div class="login">
          <p>Already have an account? <a href="emp_login.php"> Login</a></p>
        </div>
      </div>
    </div>
  </div>
  <script src="js/countrydata.js"></script>
  <script src="js/emp_signup.js"></script>

</body>

</html>