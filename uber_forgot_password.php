<?php
include ("uber_connect.php");
// Session creation
if (isset($_SESSION["uber_emp_name"])) {
  header("location:uber.php");
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
    $sql = "SELECT * FROM `employees` WHERE emp_email = '$email'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_array($result);
        $emp_id = $row['emp_id'];

        $_SESSION['flash_message'] = "Change Password link sent to your mail.";

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

        $sql_token = "INSERT INTO `security_token` (`token_user_id`, `token_type`, `token_value`, `token_expiry_time`, `token_created_at`, `token_updated_at`) VALUES ('$emp_id', 'Uber Reset Password', '$randomHash',  DATE_ADD(CURRENT_TIMESTAMP, INTERVAL $link_exp_time MINUTE), CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)";
        $result_token = mysqli_query($conn, $sql_token);


        // Calling the function for mailing...
        $temp_slug = 'uber_forgot_password';
        $command = "php -r 'require_once(\"connect.php\"); mailer(\"$temp_slug\", \"$email\", \"\" , \"$randomHash\");'> /dev/null 2>&1 &";
        // Execute the command
        exec($command);
        echo '<meta http-equiv="refresh" content="0;url=uber_login.php">';

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
    <title>Employee Forgot</title>
    <link rel="icon" type="image/x-icon" href="images/uber_logo.png">

    <link href="css/client_dashboard.css" rel="stylesheet">
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
        <div class="heading-top">
            <div class="logo-cebter"><a href="#"><img src="images/uber.png"></a></div>
        </div>
        <div class="box_login">
            <div class="outer_div">

                <h2>Forgot <span style="color: white;">Password</span></h2>
                <?php
                if ($invalid) {
                    echo '<div class="error-message-div error-msg"><img src="images/unsucess-msg.png"><strong>Invalid!</strong> Username
              or Password </div>';
                }
                ?>
                <form class="margin_bottom" onsubmit="return validateForm()" role="form"
                    action="uber_forgot_password.php" method="POST">
                    <div class="form-group">
                        <label for="exampleInputEmail1">User Name</label>
                        <input id="email_input" type="email" class="form-control" name="email" autocomplete="off"
                            onblur="validateEmail()" placeholder="Email" />
                        <span class='text_error' id="email_err"></span>
                    </div>
                    <button type="submit" class="btn_login" name="submit">Submit</button>
                </form>
                <div class="login">
                    <p>Back To Login? <a href="uber_login.php">Login</a></p>
                </div>
            </div>
        </div>
    </div>

    <script>
        function validateForm() {
            console.log('validateEmail', validateEmail());
            if (!validateEmail()) {
                return false;
            }
            return true;
        }

        function validateEmail() {
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