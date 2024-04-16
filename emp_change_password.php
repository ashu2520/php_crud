<?php
include "connect.php";
// Starting the session
if (!isset($_SESSION["user_name"])) {
	header("location:emp_login.php");
}
?>
<?php
$Id = "";
$old_password = "";
$new_password = "";
$confirm_password = "";
$error = false;
$password_error = "";
function clean_Pass_input($fields)
{
	$fields = trim($fields);
	$fields = stripslashes($fields);
	$fields = str_replace("'", "", $fields);
	$fields = htmlspecialchars($fields);
	return $fields;
}

if (isset($_POST["Submitasd"])) {
	$old_password = clean_Pass_input($_POST["old_pass"]);
	$new_password = clean_Pass_input($_POST["new_pass"]);
	$confirm_password = clean_Pass_input($_POST["confirm_pass"]);
	if ($new_password == "" || !preg_match("#^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[^\da-zA-Z\s]).{8,}$#", $new_password) || strcmp($new_password, $confirm_password) !== 0) {
		$error = true;
	}

	$Id = $_SESSION["Id"];

	// Fetching Password from database
	$sql_em = "SELECT * FROM `users` WHERE user_id = '$Id'";
	$result_em = mysqli_query($conn, $sql_em);
	$row = mysqli_fetch_assoc($result_em);

	$old_hashed_password = $row['user_password'];
	// Old password and new password same nhi hona chasiye... agar hua to else case chala do...
	if (password_verify($old_password, $old_hashed_password)) {
		if (password_verify($new_password, $old_hashed_password)) {
			$password_error = true;
		}
	} else {
		$error = true;
	}
	if (!$error && !$password_error) {
		$new_hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
		$sql = "UPDATE `users` set  user_password = '$new_hashed_password' where user_id = $Id";
		$result = mysqli_query($conn, $sql);
		if ($result) {
			// echo"Ha bhai...";
			// $sql_content = "SELECT temp_subject, temp_content FROM email_templates WHERE temp_slug = 'change_password'";
			// $result_content = mysqli_query($conn, $sql_content);
			// $row = mysqli_fetch_array($result_content);

			// $subject = $row["temp_subject"];
			// $body = $row["temp_content"];

			$sql_email = "SELECT user_email FROM `users` WHERE user_id = $Id";
			$result_email = mysqli_query($conn, $sql_email);
			$row = mysqli_fetch_array($result_email);

			$email = $row['user_email'];

			// Calling the function for mailing...
			// mailer($email, $subject, $body);  // present in connect.php
			// $command = "php -r 'require_once(\"connect.php\"); mailer(\"$email\", \"$subject\", \"$body\");'> /dev/null 2>&1 &";
			$temp_slug = 'change_password';
			$command = "php -r 'require_once(\"connect.php\"); mailer(\"$temp_slug\", \"$email\", \"\" , \"\");'> /dev/null 2>&1 &";

			// Execute the command
			exec($command);
			// print_r(exec($command));
			// die();
			$_SESSION['flash_message'] = " Password Changed Sucessfully ";
			// header("location:emp_logout.php");
			echo '<meta http-equiv="refresh" content="0;url=client_dashboard.php">';
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
	<title>Admin Update Data</title>
	<link rel="icon" type="image/x-icon" href="images/arcs_logo.png">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

	<!-- Bootstrap -->
	<link href="css/client_dashboard.css" rel="stylesheet">
	<script type='text/javascript' src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
	<!-- Required for using jQuery input mask plugin -->
	<script type='text/javascript'
		src="https://rawgit.com/RobinHerbots/jquery.inputmask/3.x/dist/jquery.inputmask.bundle.js"></script>

</head>

<body>
	<?php include "header.php"; ?>

	<div class="clear"></div>
	<div class="clear"></div>
	<div class="content">
		<div class="wrapper">
			<div class="bedcram">
			</div>
			<?php include "left_sidebar.php"; ?>
			<div class="right_side_content">
				<h1>Change Password</h1>
				<div class="list-contet">
					<?php
					if ($error) {
						echo '<div class="error-message-div error-msg"><img src="images/unsucess-msg.png"><strong>UnSucess!</strong> Password cannot be Updated </div>';
					} else if ($password_error) {
						echo '<div class="error-message-div error-msg"><img src="images/unsucess-msg.png"><strong>UnSucess!</strong> New Password Cannot be Same as Old Password</div>';
					}
					?>
					<form id="main" class="form-edit" onsubmit="return validateForm()" action="emp_change_password.php"
						method="POST">

						<!-- Old Password -->
						<div class="form-row">
							<div class="form-label">
								<label>Old Password: <span></span></label>
							</div>
							<div class="input-field">
								<input type="Password" name="old_pass" class="search-box" placeholder="Old Password"
									autocomplete="off">
								<span class='text_error' id=""></span>
							</div>
						</div>

						<!--New Password -->
						<div class="form-row">
							<div class="form-label">
								<label>New Password: <span></span></label>
							</div>
							<div class="input-field">
								<input type="password" id="password_input" class="search-box" name="new_pass"
									placeholder="New Password" autocomplete="off" oninput="validatePassword()">
								<!-- <span class='text_error' id="passworderr"></span> -->
								<div class="tool-tip-create">
						<p id="password-check">Password must contain the following: </p>
						<div class="tool-tip-create-error">
							<!-- <i class="fa-solid fa-xmark"></i> -->
							<p id="password-lowercase"><i class="fa-solid fa-xmark"></i> A lowercase letter.</p>
							<p id="password-uppercase"><i class="fa-solid fa-xmark"></i> A capital(Uppercase) letter.
							</p>
							<p id="password-special"><i class="fa-solid fa-xmark"></i> A special character.</p>
							<p id="password-number"><i class="fa-solid fa-xmark"></i> A number.</p>
							<p id="password-length"><i class="fa-solid fa-xmark"></i> Between 8-16 characters.</p>
						</div>
						<!-- <i class="fa-solid fa-check"></i> -->
					</div>
							</div>
						</div>


						<!-- Confirm Password-->
						<div class="form-row">
							<div class="form-label">
								<label>Confirm Password: <span></span></label>
							</div>
							<div class="input-field">
								<input id="confirm_password_input" type="password" name="confirm_pass"
									class="search-box" placeholder="Confirm Password" autocomplete="off"
									oninput="validatePassword()">
								<span class='text_error' id="confirm_password_err"></span>
							</div>
							<!-- echo '<p class="error-ms">Please fill this field</p>'; -->
						</div>


						<div class="form-row">
							<div class="form-label">
								<label><span></span> </label>
							</div>
							<div class="input-field">
								<input type="submit" class="submit-btn" name="Submitasd" value="Save">
							</div>
						</div>
					</form>
					
				</div>
			</div>
		</div>
	</div>

	<script>
		function validateForm() {
			if (!validatePassword()) {
				// console.log("here I am")
				return false;
			}
			else {
				return true;
			}
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
				$('.tool-tip-create').css("border-color", "green");
				$('#password-check').css('color', 'green');
				$('.tool-tip-create').addClass('green-border');

				if (confirm_password_flag)
					return true;
				else
					return false;
			} else {
				$('#password_input').css("border-color", "black");
				$('.tool-tip-create').css("border-color", "red");
				$('#password-check').css('color', 'red');
				$('.tool-tip-create').removeClass('green-border');
				return false;
			}
		}

		<?php
		if ($error || $password_error) {
			echo 'setTimeout(function () { document.getElementsByClassName("error-msg")[0].style.display = \'none\'; }, 3000)';
		}
		?>

	</script>
</body>

</html>