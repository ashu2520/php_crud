<?php
include ("uber_connect.php");
// Session creation
if (isset($_SESSION["uber_emp_name"])) {
  header("location:uber.php");
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
$password = "";
$confirm_pass = "";
$error = false;
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
  if ($current_date_time > $token_expiry_time) {
    $_SESSION['flash_message'] = "Session Time Expired";
    header("location:uber_login.php");
    exit();
  }
  if (mysqli_num_rows($result) != 1) {
    // echo"Here I am! hello";
    // die();
    header("location:uber_login.php");
    exit();
  }
} else if (isset($_POST["submit"])) {
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

    $sql_1 = "UPDATE `employees` SET emp_password = '$hashed_password' WHERE emp_id = ( SELECT token_user_id FROM `security_token` WHERE `security_token`.`token_value` = '$token');";
    $result_1 = mysqli_query($conn, $sql_1);
    // echo $token;
    $sql_2 = "DELETE FROM `security_token` WHERE `token_value` = '$token'";
    $result_2 = mysqli_query($conn, $sql_2);
    if ($result_1) {
      $_SESSION['flash_message'] = "Password Changed Successfully.";
      header("location:uber_login.php");
      exit();
    } else {
      die(mysqli_error($conn));
    }
  }
} else {
  header("location:uber_login.php");
    exit();
}
?>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Employee Change Password</title>
  <link rel="icon" type="image/x-icon" href="images/uber_logo.png">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

  <link href="css/client_dashboard.css" rel="stylesheet">
  <script type='text/javascript' src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  <!-- Required for using jQuery input mask plugin -->
  <script type='text/javascript'
    src="https://rawgit.com/RobinHerbots/jquery.inputmask/3.x/dist/jquery.inputmask.bundle.js"></script>
    <style>
        body {
            background-color: white;
        }

        .outer_div {
            background-color: lightgray;
        }

        .btn_login {
            background-color: black;
        }

        .login a {
            color: black;
        }

        .logo-cebter {
            margin-top: -20px;
            height: 120px;
            width: 200px;
        }

        .form-group a {
            color: black;
        }

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
            background-color: black;
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
      <div class="logo-cebter"><a href="#"><img src="images/uber.png"></a></div>
    </div>
    <div class="box_login">
      <div class="outer_div">

        <h2>Change <span style="color: white;">Password</span></h2>
        <?php
        if ($error) {
          echo '<div class="error-message-div error-msg"><img src="images/unsucess-msg.png"><strong>Invalid!</strong> Password </div>';
        }
        ?>
        <!-- <div class="error-message-div error-msg"><img src="images/unsucess-msg.png"><strong>Invalid!</strong> username
            or password </div> -->
        <form class="margin_bottom" onsubmit="return validateForm()" role="form" action="uber_change_password.php"
          method="POST">

          <div class="form-group">
            <label for="exampleInputEmail1">Password</label>
            <input id="password_input" type="password" class="form-control" name="password" autocomplete="off"
            oninput="validatePassword()" placeholder="Password" />
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
            <label for="exampleInputPassword1">Confirm Password</label>
            <input id="confirm_password_input" type="password" class="form-control" name="confirm_password"
              autocomplete="off" oninput="validatePassword()" placeholder="Confirm Password" />
            <span class='text_error' id="confirm_password_err"></span>
          </div>

          <input type="submit" class="btn_login" name="submit" value="Submit">
        </form>
        <div class="login">
          <p>Back To Login? <a href="uber_login.php">Login</a></p>
        </div>
      </div>
    </div>
  </div>
  <!-- <script src="js/emp_login.js"></script> -->
  <script>

    function validateForm() {
      if (!validatePassword() ) 
        return false;
      else 
        return true;
    }
    function validatePassword() {
    var password = document.getElementById('password_input').value;
    var confirm_password = document.getElementById('confirm_password_input').value;
    var lower_regex = /[a-z]/;
    var upper_regex = /[A-Z]/;
    var num_regex = /\d/;
    var special_regex = /[^a-zA-Z0-9\s]/;
    var length_regex = /^.{8,16}$/;

    if (lower_regex.test(password)) {
        $("#password-lowercase i").removeClass("fa-xmark").addClass("fa-check").css("color", "green");
        $("#password-lowercase").css("color", "green");
    } else {
        $("#password-lowercase i").removeClass("fa-check").addClass("fa-xmark").css("color", "red");
        $("#password-lowercase").css("color", "red");
    }

    if (upper_regex.test(password)) {
        $("#password-uppercase i").removeClass("fa-xmark").addClass("fa-check").css("color", "green");
        $("#password-uppercase").css("color", "green");
    } else {
        $("#password-uppercase i").removeClass("fa-check").addClass("fa-xmark").css("color", "red");
        $("#password-uppercase").css("color", "red");
    }

    if (num_regex.test(password)) {
        $("#password-number i").removeClass("fa-xmark").addClass("fa-check").css("color", "green");
        $("#password-number").css("color", "green");
    } else {
        $("#password-number i").removeClass("fa-check").addClass("fa-xmark").css("color", "red");
        $("#password-number").css("color", "red");
    }

    if (special_regex.test(password)) {
        $("#password-special i").removeClass("fa-xmark").addClass("fa-check").css("color", "green");
        $("#password-special").css("color", "green");
    } else {
        $("#password-special i").removeClass("fa-check").addClass("fa-xmark").css("color", "red");
        $("#password-special").css("color", "red");

    }

    if (length_regex.test(password)) {
        $("#password-length i").removeClass("fa-xmark").addClass("fa-check").css("color", "green");
        $("#password-length").css("color", "green");
    } else {
        $("#password-length i").removeClass("fa-check").addClass("fa-xmark").css("color", "red");
        $("#password-length").css("color", "red");
    }

    // Confirm Password validation...
    var confirm_password_flag = false;
    if (confirm_password === "") {
        $("#confirm_password_err").html("");
        $("#confirm_password_input").css("border-color", "black");
        confirm_password_flag = false;

    } else if (password === confirm_password) {
        $("#confirm_password_err").html("");
        $("#confirm_password_input").css("border-color", "green");
        confirm_password_flag = true;
    } else {
        $("#confirm_password_err").html("Password Missmatched.");
        $("#confirm_password_input").css("border-color", "black");
        confirm_password_flag = false;
    }


    if (lower_regex.test(password) && upper_regex.test(password) && num_regex.test(password) && special_regex.test(password) && length_regex.test(password)) {
        $('#password_input').css("border-color", "green");
        $('.tool-tip-signup').css("border-color", "green");
        $('#password-check').css('color', 'green');
        $('.tool-tip-signup').addClass('green-border');

        if (confirm_password_flag)
            return true;
        else
            return false;
    } else {
        $('#password_input').css("border-color", "black");
        $('.tool-tip-signup').css("border-color", "red");
        $('#password-check').css('color', 'red');
        $('.tool-tip-signup').removeClass('green-border');
        return false;
    }
}
    <?php
    if ($error) {
      echo 'setTimeout(function () { document.getElementsByClassName("error-msg")[0].style.display = \'none\'; }, 3000)';
    }
    ?>
  </script>
</body>

</html>