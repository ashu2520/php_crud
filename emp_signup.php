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
$country_code = "";
$mobile = "";
$gender = "";
$country = "";
$state = "";
$position = "";
$password = "";
$confirm_pass = "";
$terms_cond = "";
$error = false;
$nameerr = false;
$emailerr = false;
$mobilerr = false;
$gender_error = false;
$location_error = false;
// $role_error = false;
$position_error = false;
$passworderr = false;
$confirm_pass_err = false;
// print_r($_POST);

if (isset($_POST["name"]) && isset($_POST["email"]) && isset($_POST["mobile"]) && isset($_POST["password"]) && isset($_POST["confirm_pass"]) && isset($_POST["gender"]) && isset($_POST['User_type']) && isset($_POST['country']) && isset($_POST['state'])) {
  #Getting data from request
  // print_r($_POST);

  $name = cleansignup_input($_POST['name']);
  $email = cleansignup_input($_POST['email']);
  $country_code = strval($_POST['country_code']);
  $mobile = cleansignup_input($_POST['mobile']);
  $password = cleansignup_input($_POST["password"]);
  $confirm_pass = cleansignup_input($_POST["confirm_pass"]);
  $gender = $_POST["gender"];
  $country = $_POST["country"];
  $state = $_POST["state"];
  $position = $_POST["User_type"];
  if (isset($_POST['terms_cond'])) {
    $terms_cond = "yes";
  }

  if (!preg_match("/^[a-zA-Z\s'-]+$/", $name) || $name == "") {
    $nameerr = true;
  }

  $mobile = str_replace("(", "", $mobile);
  $mobile = str_replace(")", "", $mobile);
  $mobile = str_replace("-", "", $mobile);
  $mobile = str_replace(" ", "", $mobile);
  $mobile = "+" . $country_code . " " . $mobile;

  if (!preg_match("/^\+\d{1,4}\s?([1-9]\d{5,11})$/", $mobile) || !(isset($mobile)) || $mobile == "") {
    $mobilerr = true;
  }
  if ($state == "" || !(isset($state)) || $state == "Select State") {
    $location_error = true;
  }
  if ($country == "" || !(isset($country)) || $country == "Select Country") {
    $location_error = true;
  }
  if ($position == "" || !(isset($position)) || $position == "Select Position") {
    $position_error = true;
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
  if (!$nameerr && !$emailerr && !$mobilerr && !$location_error && !$position_error && !$passworderr && !$confirm_pass_err) {
    $sql = "INSERT INTO `users` (user_name, user_mobile, user_email, user_gender, user_country, user_state, user_type, user_password, user_terms_cond, user_created_at, user_updated_at) VALUES ('$name', '$mobile', '$email', '$gender', '$country', '$state', '$position', '$hashed_password', '$terms_cond', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)";
    $result = mysqli_query($conn, $sql);
    // echo "here";
    if ($result) {

      // Random String Generator
      $randomString = uniqid(); // Generate a random string
      $randomHash = md5($randomString); // Generate MD5 hash of the random string
      $_SESSION['token_value'] = $randomHash;

      // TOKEN Expiry Time...
      $sql_setting = "SELECT * FROM `settings` WHERE setting_id = 1";
      $result_setting = mysqli_query($conn, $sql_setting);
      if ($result_setting) {
        $row = mysqli_fetch_assoc($result_setting);
        $link_exp_time = $row['setting_token_expiry_time'];
      }

      $sql_token = "INSERT INTO `security_token` (`token_user_email`, `token_type`, `token_value`, `token_expiry_time`, `token_created_at`, `token_updated_at`) VALUES ( '$email', 'Account Verificatioin', '$randomHash',  DATE_ADD(CURRENT_TIMESTAMP, INTERVAL 48 HOUR), CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)";
      $result_token = mysqli_query($conn, $sql_token);

      $temp_slug = 'verification_link';

      $command = "php -r 'require_once(\"connect.php\"); mailer(\"$temp_slug\", \"$email\", \"$name\" , \"$randomHash\");'> /dev/null 2>&1 &";
      exec($command);
      $_SESSION['flash_message'] = "Sign Up Successful! Please verify your account";
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
  <title>Admin SignUp</title>
  <link rel="icon" type="image/x-icon" href="images/arcs_logo.png">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

  <link href="css/client_dashboard.css" rel="stylesheet">
  <script type='text/javascript' src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  <!-- Required for using jQuery input mask plugin -->
  <script type='text/javascript'
    src="https://rawgit.com/RobinHerbots/jquery.inputmask/3.x/dist/jquery.inputmask.bundle.js"></script>
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
      border: 1px solid gray;

    }

    .container input:checked~.checkmark {
      background-color: #ff651b;
      border: none;
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
        } else if ($location_error) {
          echo '<div class="error-message-div error-msg"><img src="images/unsucess-msg.png"><strong>UnSucess!</strong> Loaction Error</div>';
        } else if ($position_error) {
          echo '<div class="error-message-div error-msg"><img src="images/unsucess-msg.png"><strong>UnSucess!</strong> Position Error</div>';
        } else if ($passworderr || $confirm_pass_err) {
          echo '<div class="error-message-div error-msg"><img src="images/unsucess-msg.png"><strong>UnSucess!</strong> Invalid Password Type</div>';
        }
        ?>
        <!-- Flash Messages -->
        <?php
        if (isset($_SESSION['flash_message'])) {
          $message = $_SESSION['flash_message'];
          unset($_SESSION['flash_message']);
          echo "<span id='flash-message' class='login-flash-message'> $message</span>";
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
            <div class="input-group">
              <!-- Country Code Select -->
              <select style="width: 86px;" id="country_code" class="form-control" name="country_code">
                <option value="91">+91</option>
                <?php
                // Fetching country phonecodes
                $sql_countries_phonecode = "SELECT * FROM `countries` WHERE country_phonecode != 91";
                $result_countries_phonecode = mysqli_query($conn, $sql_countries_phonecode);

                while ($row = mysqli_fetch_assoc($result_countries_phonecode)) {
                  $country_id = $row['country_id'];
                  // echo $country_id;
                  $country_phonecode = $row['country_phonecode'];
                  // echo $country_phonecode;
                  echo "<option value='$country_phonecode'>" . "+" . $country_phonecode . "</option>";
                }
                ?>
              </select>

              <!-- Mobile Number -->
              <input style="width: 240px" id="mobile_input" type="text" class="form-control" name="mobile"
                placeholder="####-###-###" autocomplete="off" onblur="validateMobileNumber()">
            </div>
            <!-- Error Message -->
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
            <span class="rad_text" value="Male"> Male</span>
            <input id="gender_female" class="rad_opt" type="radio" name="gender" oninput="vaildategender()"
              value="female">
            <span class="rad_text" value="Female"> Female</span>
            <span class='text_error' id="gender_error"></span>
          </div>


          <div class="form-group">
            <div class="select_option">

              <label class="labels">Country And State</label>
              <select style="width: 150px; float: left;" id="country_select" class="form-select"
                aria-label="Default select example" name="country" onchange="loadCountry()">
                <option>Select Country</option>
                <?php
                $sql_countries = "Select * from `countries`";
                $result_countries = mysqli_query($conn, $sql_countries);
                while ($row = mysqli_fetch_array($result_countries)) {
                  $country_name = $row['country_name'];
                  $country_id = $row['country_id'];
                  echo "<option value='$country_id'>" . $country_name . "</option>";
                }
                ?>
              </select>

              <select style="width: 150px; float: right;" id="state_select" disabled class="form-select"
                aria-label="Default select example" name="state" onblur="validatelocation()">
                <option>Select State</option>

              </select>

            </div>
            <span class='text_error' id="location_error"></span>
          </div>

          <div class="form-group">
            <label for="Position">Position</label>
            <select id="User_type_input" class="form-select" name="User_type" autocomplete="off"
              onblur="validatePosition()">
              <option style="color: lightgray;">Select Position</option>
              <?php
              $sql_position = "Select * from `position`";
              $result_position = mysqli_query($conn, $sql_position);
              while ($row = mysqli_fetch_array($result_position)) {
                $position_name = $row['position_name'];
                echo "<option value='$position_name'>" . $position_name . "</option>";
              }
              ?>
            </select>
            <span class='text_error' id="position_err"></span>
          </div>


          <div class="form-group">
            <label for="password" class="labels">Password</label>
            <input type="password" id="password_input" class="form-control" name="password" placeholder="Password"
              autocomplete="off" oninput="validatePassword()">
            <!-- <span class='text_error' id="passworderr"></span> -->
            <div class="tool-tip-signup">
              <p id="password-check">Password must contain the following: </p>
              <div class="tool-tip-signup-error">
                <!-- <i class="fa-solid fa-xmark"></i> -->
                <p id="password-lowercase"><i class="fa-solid fa-xmark"></i> A lowercase letter.</p>
                <p id="password-uppercase"><i class="fa-solid fa-xmark"></i> A capital (Uppercase) letter.
                </p>
                <p id="password-special"><i class="fa-solid fa-xmark"></i> A special character.</p>
                <p id="password-number"><i class="fa-solid fa-xmark"></i> A number.</p>
                <p id="password-length"><i class="fa-solid fa-xmark"></i> Between 8-16 characters.</p>
              </div>
              <!-- <i class="fa-solid fa-check"></i> -->
            </div>
          </div>

          <div class="form-group">
            <label for="Password" class="labels">Confirm Password</label>
            <input type="password" class="form-control" id="confirm_password_input" name="confirm_pass"
              placeholder="Confirm Password" autocomplete="off" oninput="validatePassword()">
            <span class='text_error' id="confirm_password_err"></span>
          </div>


          <div style="display: flex;  margin-top: -5px;" class="form-group">
            <label class="container"> I agree on the terms and conditions.
              <input type="checkbox" value="yes" id="remember_me" name="terms_cond">
              <span class="checkmark"></span>
            </label>
          </div>

          <button type="submit" class="btn_login" name="submitasd">Sign Up</button>
        </form>
        <div class="login">
          <p>Already have an account? <a href="emp_login.php"> Login</a></p>
        </div>
      </div>
    </div>
  </div>


  <script src="js/emp_signup.js"></script>
</body>

</html>