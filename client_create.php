<?php
include "connect.php";
// Starting the session
if (!isset($_SESSION["user_name"])) {
	header("location:emp_login.php");
	exit();
}
if ($_SESSION["User_role_id"] != 1 && $_SESSION["User_role_id"] != 2) {
	header("location:emp_login.php");
	exit();
}
?>
<?php
function clean_input($fields)
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
$role = "";
$password = "";
$confirm_pass = "";
$terms_cond = "";
// $error = false;
$nameerr = false;
$emailerr = false;
$mobilerr = false;
$gender_error = false;
$passworderr = false;
$confirm_pass_err = false;

if (isset($_POST["name"]) && isset($_POST["email"]) && isset($_POST["mobile"]) && isset($_POST["password"]) && isset($_POST["confirm_pass"]) && isset($_POST["gender"]) && isset($_POST['User_type'])) {
	#Getting data from request

	$name = clean_input($_POST['name']);
	$email = clean_input($_POST['email']);
	$mobile = clean_input($_POST['mobile']);
	$password = clean_input($_POST["password"]);
	$confirm_pass = clean_input($_POST["confirm_pass"]);
	$gender = $_POST["gender"];
	// $country = $_POST["country"];
	// $state = $_POST["state"];
	$position = $_POST["User_type"];
	$role = $_POST["role_type"];
	print_r($_POST);
	// die();
	if (isset($_POST['terms_cond'])) {
		$terms_cond = "yes";
	}
	if (!preg_match("/^[a-zA-Z\s'-]+$/", $name) || !(isset($name)) || $name == "") {
		$nameerr = true;
	}
	if (!preg_match("/^[0-9]{10}$/", $mobile) || !(isset($mobile)) || $mobile == "") {
		$mobilerr = true;
	}

	# Email check
	$sql_em = "SELECT * FROM `users` WHERE user_email = '$email'";
	$result_em = mysqli_query($conn, $sql_em);
	if (!filter_var($email, FILTER_VALIDATE_EMAIL) || !(isset($email)) || $email == "" || mysqli_num_rows($result_em) > 0) {
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
		$sql = "INSERT INTO `users` (user_name, user_mobile, user_email, user_gender,  user_type, user_role_id, user_password, user_terms_cond, user_created_at, user_updated_at) VALUES ('$name', '$mobile', '$email', '$gender', '$position', '$role', '$hashed_password', '$terms_cond', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)";
		$result = mysqli_query($conn, $sql);
		// echo "here";
		if ($result) {

			// $sql_content = "SELECT temp_subject, temp_content FROM email_templates WHERE temp_slug = 'user_added'";
			// $result_content = mysqli_query($conn, $sql_content);
			// $row = mysqli_fetch_array($result_content);

			// $subject = $row["temp_subject"];
			// $body = $row["temp_content"];

			// Calling the function for mailing...
			// mailer($email, $subject, $body, $name);  // present in connect.php

			// Construct the command to execute the script in the background
			// php -r tells the php to directly run the code from the command line.
			// $command = "php -r 'require_once(\"connect.php\"); mailer(\"$email\", \"$subject\", \"$body\", \"$name\");'> /dev/null 2>&1 &";
			$temp_slug = 'user_added';
			$command = "php -r 'require_once(\"connect.php\"); mailer(\"$temp_slug\", \"$email\", \"$name\" , \"\");'> /dev/null 2>&1 &";


			// Execute the command
			exec($command);
			
			// header("location:client_dashboard.php");
			$_SESSION['flash_message'] = "Sucessfully Added";
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
	<title>Admin Add Data</title>
	<link rel="icon" type="image/x-icon" href="images/arcs_logo.png">

	<!-- Bootstrap -->
	<link href="css/client_dashboard.css" rel="stylesheet">
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
				<h1>Add Users</h1>
				<div class="list-contet">
					<?php
					if ($nameerr) {
						echo '<div class="error-message-div error-msg"><img src="images/unsucess-msg.png"><strong>UnSucess!</strong> Name Error</div>';
					} else if ($mobilerr) {
						echo '<div class="error-message-div error-msg"><img src="images/unsucess-msg.png"><strong>UnSucess!</strong> Mobile Error</div>';
					} else if ($emailerr) {
						echo '<div class="error-message-div error-msg"><img src="images/unsucess-msg.png"><strong>UnSucess!</strong> Email Already Exist</div>';
					} else if ($passworderr || $confirm_pass_err) {
						echo '<div class="error-message-div error-msg"><img src="images/unsucess-msg.png"><strong>UnSucess!</strong> Invalid Password Type</div>';
					}
					?>
					<form id="main" class="form-edit" onsubmit="validateForm(); return false;"
						action="client_create.php" method="POST">
						<!-- First Name -->
						<div class="form-row">
							<div class="form-label">
								<label>Name: <span>*</span></label>
							</div>
							<div class="input-field">
								<input id="name_input" type="text" name="name" class="search-box" placeholder="Name"
									oninput="validateName()" />
								<span class='text_error' id="name_err"></span>
							</div>
						</div>

						<!-- Mobile -->
						<div class="form-row">
							<div class="form-label">
								<label>Mobile Number: <span>*</span></label>
							</div>
							<div class="input-field">
								<input id="mobile_input" type="number" name="mobile" class="search-box"
									placeholder="Mobile Number" oninput="validateMobileNumber()" />
								<span class='text_error' id="mobile_error"></span>
							</div>
						</div>

						<!-- Email -->
						<div class="form-row">
							<div class="form-label">
								<label>Email: <span>*</span></label>
							</div>
							<div class="input-field">
								<input id="email_input" type="text" Name="email" class="search-box" placeholder="Email"
									oninput="validateEmail()">
								<span class='text_error' id="email_err"></span>
							</div>
						</div>

						<!-- Gender -->
						<div class="form-row radio-row">
							<div class="form-label">
								<label>Gender: <span>*</span> </label>
							</div>
							<div class="input-field">
								<label><input id="gender_male" type="radio" name="gender" value="Male" checked
										onblur="vaildategender()"> <span>Male</span></label><label>
									<input id="gender_female" type="radio" name="gender" value="Female"
										onblur="vaildategender()"> <span>Female</span> </label>
								<span class='text_error' id="gender_error"></span>
							</div>
						</div>

						<!-- Location -->
						<!-- <div class="form-row">
							<div class="form-label">
								<label>Country And State: <span>*</span></label>
							</div>
							<div class="input-field">
								<select style="width: 120px; height: 20px" id="country_select"
									class="form-select country" aria-label="Default select example" name="country"
									onchange="loadStates()">
									<option>Select Country</option>
								</select>
								<select style="width: 120px; height: 20px" id="state_select" class="form-select state"
									aria-label="Default select example" name="state" onblur="validatelocation()">
									<option>Select State</option>
								</select>
								<br><span class='text_error' id="location_error"></span>
							</div>
						</div> -->

						<!-- User Type -->
						<div class="form-row">
							<div class="form-label">
								<label>Position: <span>*</span></label>
							</div>
							<div class="input-field">
								<select style="margin-top: 7px;" id="User_type_input" class="form-select"
									name="User_type" autocomplete="off" onblur="validatePosition()">
									<option>Select Position</option>
									<?php
									$sql_position = "Select * from `position`";
									$result_position = mysqli_query($conn, $sql_position);
									while ($row = mysqli_fetch_array($result_position)) {
										$position_name = $row['position_name'];
										echo "<option value='$position_name'>" . $position_name . "</option>";

									}
									?>
									<!-- <option>AIML</option>
									<option>Backend</option>
									<option>Cyber Security</option>
									<option>Data Scientist</option>
									<option>Devops</option>
									<option>Frontend</option>
									<option>Full Stack</option> -->
								</select>
								<span class='text_error' id="position_err"></span>
							</div>
						</div>


						<!-- Role Type -->
						<div class="form-row">
							<div class="form-label">
								<label>Role: <span>*</span> </label>
							</div>
							<div class="input-field">
								<select style="margin-top: 7px;" id="role_input" class="form-select" name="role_type"
									autocomplete="off" onblur="validateRole()">
									<option>Select Role</option>
									<?php if ($_SESSION["User_role_id"] == 1) { ?>
										<option value="2">Admin</option>
									<?php } ?>
									<?php
									$sql_roles = "Select * from `roles` WHERE role_id != 1 AND role_id !=2";
									$result_roles = mysqli_query($conn, $sql_roles);
									while ($row = mysqli_fetch_array($result_roles)) {
										$role_name = $row['role_name'];
										$role_id = $row['role_id'];
										echo "<option value=" . $role_id . ">" . $role_name . "</option>";
									}
									?>
									<!-- <option value="5">Employee</option>
									<option value="3">Manager</option>
									<option value="4">Team Lead</option> -->
								</select>
								<span class='text_error' id="role_error"></span>
							</div>
						</div>

						<!-- Password -->
						<div class="form-row">
							<div class="form-label">
								<label>Password: <span>*</span> </label>
							</div>
							<div class="input-field">
								<input id="password_input" type="Password" name="password" class="search-box"
									placeholder="Password" oninput="validatePassword()" />
								<span class='text_error' id="passworderr"></span>

							</div>
						</div>

						<!-- Confirm Password -->
						<div class="form-row">
							<div class="form-label">
								<label>Confirm Password: <span>*</span> </label>
							</div>
							<div class="input-field">
								<input id="confirm_password_input" type="Password" name="confirm_pass"
									class="search-box" placeholder="Password" oninput="validateConfirmPassword()" />
								<span class='text_error' id="confirm_password_err"></span>
							</div>
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
	<script src="js/client_create.js"></script>
</body>

</html>