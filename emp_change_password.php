<?php
include "connect.php";
// Starting the session
if (!isset($_SESSION["user_name"])) {
  header("location:emp_login.php");
}
?>
<?php
$Id ="";
$old_password ="";
$new_password = "";
$confirm_password ="";
$error= false;
$password_error ="";
function clean_Pass_input($fields)
{
    $fields = trim($fields);
    $fields = stripslashes($fields);
    $fields = str_replace("'", "", $fields);
    $fields = htmlspecialchars($fields);
    return $fields;
}

if(isset($_POST["Submitasd"]))
{
    $old_password = clean_Pass_input($_POST["old_pass"]);
    $new_password = clean_Pass_input($_POST["new_pass"]);
    $confirm_password = clean_Pass_input($_POST["confirm_pass"]);
	if ($new_password == "" || !preg_match("#^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[^\da-zA-Z\s]).{8,}$#", $new_password) || strcmp($new_password, $confirm_password) !== 0) {
        $error = true;
    } 

	$Id = $_SESSION["Id"];

// Fetching Password from database
	$sql_em = "SELECT * FROM `login_credentials` WHERE Id = '$Id'";
    $result_em = mysqli_query($conn, $sql_em);
	$row = mysqli_fetch_assoc($result_em);

    $old_hashed_password = $row['Password'];
	// Old password and new password same nhi hona chasiye...
	if(password_verify($old_password, $old_hashed_password)){
		if(password_verify($new_password, $old_hashed_password)){
			$password_error = true;
		}
	}
	else{
		$error = true;
	}
	if (!$error && !$password_error) {
			$new_hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
			$sql= "UPDATE `login_credentials` set  Password = '$new_hashed_password' where Id = $Id";
			$result = mysqli_query($conn, $sql);
			if ($result) {
				// echo"Ha bhai...";
				$sql_content = "SELECT temp_subject, temp_content FROM email_templates WHERE temp_slug = 'change_password'";
				$result_content = mysqli_query($conn, $sql_content);
				$row = mysqli_fetch_array($result_content);
				
				$subject = $row["temp_subject"];
				$body = $row["temp_content"];

				$sql_email = "SELECT Email FROM `login_credentials` WHERE Id = $Id";
				$result_email = mysqli_query($conn, $sql_email);
				$row = mysqli_fetch_array($result_email);

				$email = $row['Email'];

				// Calling the function for mailing...
				mailer($email, $subject, $body);  // present in connect.php
				$_SESSION['flash_message'] = " Password Changed Sucessfully ";
				// header("location:emp_logout.php");
				echo '<meta http-equiv="refresh" content="0;url=emp_logout.php">';
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

	<!-- Bootstrap -->
	<link href="css/client_dashboard.css" rel="stylesheet">
</head>

<body>
<?php include "header.php";?>

  <div class="clear"></div>
  <div class="clear"></div>
  <div class="content">
    <div class="wrapper">
      <div class="bedcram">
      </div>
      <?php include "left_sidebar.php";?>
			<div class="right_side_content">
				<h1>Change Password</h1>
				<div class="list-contet">
					<?php
					if ($error) {
						echo '<div class="error-message-div error-msg"><img src="images/unsucess-msg.png"><strong>UnSucess!</strong> Password cannot be Updated </div>';
					} 
					else if ($password_error) {
						echo '<div class="error-message-div error-msg"><img src="images/unsucess-msg.png"><strong>UnSucess!</strong> New Password Cannot be Same as Old Password</div>';
					}
					?>
					<form id="main" class="form-edit" onsubmit="return validateForm()" action="emp_change_password.php" method="POST">

						<!-- Old Password -->
						<div class="form-row">
							<div class="form-label">
								<label>Old Password: <span></span></label>
							</div>
							<div class="input-field">
								<input type="Password" name="old_pass" class="search-box" placeholder="Old Password" autocomplete="off">
								<span class='text_error' id=""></span>
							</div>
						</div>

						<!-- Password -->
						<div class="form-row">
							<div class="form-label">
								<label>New Password: <span></span></label>
							</div>
							<div class="input-field">
								<input type="password" id="password_input" class="search-box" name="new_pass" placeholder="New Password" autocomplete="off" oninput="validatePassword()" >
								<span class='text_error' id="passworderr" ></span>
							</div>
						</div>
						

						<!-- Confirm Password-->
						<div class="form-row">
							<div class="form-label">
								<label>Confirm Password: <span></span></label>
							</div>
							<div class="input-field">
								<input id="confirm_password_input" type="password" name="confirm_pass" class="search-box" placeholder="Confirm Password" autocomplete="off" oninput="validateConfirmPassword()">
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
	if (!validatePassword() || !validateConfirmPassword()) {
        // console.log("here I am")
        return false;
    }	
	else{
		return true;
	}
}
function validatePassword() {
    var password = document.getElementById('password_input').value;
    var passwordRegex = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[^\da-zA-Z\s]).{8,}$/;
    if (!passwordRegex.test(password)) {
        document.getElementById("passworderr").innerHTML = "Enter the combination of at least 8 numbers, letters, and punctuation marks.";
        password_input.style.borderColor = "black";
        return false;
    } else {
        document.getElementById("passworderr").innerHTML = "";
        password_input.style.borderColor = "green";
        return true;
    }
}
function validateConfirmPassword() {
    var password = document.getElementById('password_input').value;
    var confirm_password = document.getElementById('confirm_password_input').value;

    if (password !== confirm_password) {
        document.getElementById("confirm_password_err").innerHTML = "Password Missmatched.";
        confirm_password_input.style.borderColor = "black";
        return false;
    } else {
        document.getElementById("confirm_password_err").innerHTML = "";
        confirm_password_input.style.borderColor = "green";
        return true;
    }
}
</script>
</body>

</html>