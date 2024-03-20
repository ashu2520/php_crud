<?php
include "connect.php";
// Starting the session
if (!isset($_SESSION["user_name"])){
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
$User_Name = "";
$First_Name = "";
$Last_Name = "";
$Email = "";
$Gender ="";
$Age="";
$User_Type = "";
$Password = "";
$error = false;
$emailerr = false;

if (isset($_POST["First_Name"]) && isset($_POST["Last_Name"]) && isset($_POST["Gender"]) && isset($_POST["Age"]) && isset($_POST["Email"]) && isset($_POST["User_Type"]) && isset($_POST["Password"])) {
	#Getting data from request
	// $User_Name = clean_input($_POST["User_Name"]);
	$First_Name = clean_input($_POST["First_Name"]);
	$Last_Name = clean_input($_POST["Last_Name"]);
	$Gender = $_POST["Gender"];
	$Age = clean_input($_POST["Age"]);
	$Email = clean_input($_POST['Email']);
	$User_Type = clean_input($_POST['User_Type']);
	$Password = clean_input($_POST["Password"]);

	// 

	if (!isset($Gender) || (isset($First_Name) && $First_Name == "") || (isset($Email) && $Email == "") || (isset($Password) && $Password == "") || (isset($User_Type) && $User_Type == "")) {
		$error = true;
	}
	if (!preg_match("/^[a-zA-Z\s'-]+$/", $First_Name) || !preg_match("/^[a-zA-Z\s'-]*$/", $Last_Name) || !preg_match("/^[1-9][0-9]*$/", $Age) || !filter_var($Email, FILTER_VALIDATE_EMAIL) || !preg_match("/^[a-zA-Z\s'-]+$/", $User_Type) || !preg_match("#^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[^\da-zA-Z\s]).{8,}$#", $Password)) {
		$error = true;

	}
	$sql_em = "SELECT * FROM `users_list` WHERE Email = '$Email'";
	$result_em = mysqli_query($conn, $sql_em);
	if (mysqli_num_rows($result_em) > 0) {
		$emailerr = true;
	}
	if (!$error && !$emailerr) {
		$sql = "INSERT INTO `users_list` (First_Name, Last_Name, Gender, Age,  Email, User_Type, Password, Createdat, Updatedat) VALUES ('$First_Name', '$Last_Name', '$Gender','$Age', '$Email', '$User_Type', '$Password', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)";
		$result = mysqli_query($conn, $sql);
		if ($result) {
			$_SESSION['flash_message'] = " User Added Sucessfully ";
			header("location:client_dashboard.php");
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
				<h1>Edit Users</h1>
				<div class="list-contet">
					<?php
					if ($error) {
						echo '<div class="error-message-div error-msg"><img src="images/unsucess-msg.png"><strong>UnSucess!</strong> Your Message has not been Send </div>';
					} else if ($emailerr) {
						echo '<div class="error-message-div error-msg"><img src="images/unsucess-msg.png"><strong>UnSucess!</strong> Email Already Exist</div>';
					}
					?>
					<form id="main" class="form-edit" onsubmit="validateForm(); return false;" action="client_create.php" method="POST">
						<!-- First Name -->
						<div class="form-row">
							<div class="form-label">
								<label>First Name: <span>*</span></label>
							</div>
							<div class="input-field">
								<input id="First_Name_input" type="text" name="First_Name" class="search-box" placeholder="First Name" oninput="validateFirst_Name()"/>
								<span class='text_error' id="First_Name_err"></span>
							</div>
						</div>

						<!-- Last Name -->
						<div class="form-row">
							<div class="form-label">
								<label>Last Name: <span></span></label>
							</div>
							<div class="input-field">
								<input id="Last_Name_input" type="text" name="Last_Name" class="search-box" placeholder="Last Name" oninput="validateLast_Name()"/>
								<span class='text_error' id="Last_Name_err"></span>
							</div>
						</div>
						<div class="form-row radio-row">
							<div class="form-label">
								<label>Gender: <span>*</span> </label>
							</div>
							<div class="input-field">
								<label><input id="gender_male" type="radio" name="Gender" value="Male" checked onblur="vaildategender()"> <span>Male</span></label><label>
									<input id="gender_female" type="radio" name="Gender" value="Female" onblur="vaildategender()"> <span>Female</span> </label>
									<span class='text_error' id="gender_error"></span>
							</div>
						</div>
						<!-- Age -->
						<div class="form-row">
							<div class="form-label">
								<label>Age: <span>*</span></label>
							</div>
							<div class="input-field">
								<input id="Age_input" type="text" name="Age" class="search-box" placeholder="Age" oninput="validateAge()">
								<span class='text_error' id="Age_error"></span>
							</div>
							<!-- echo '<p class="error-ms">Please fill this field</p>'; -->

						</div>
						<!-- Email -->
						<div class="form-row">
							<div class="form-label">
								<label>Email: <span>*</span></label>
							</div>
							<div class="input-field">
								<input id="email_input" type="text" Name="Email" class="search-box" placeholder="Email" oninput="validateEmail()">
							<span class='text_error' id="email_err"></span>
							</div>
						</div>
						<!-- <div class="form-row">
				<div class="form-label">
					<label>Security Email: <span>*</span></label> 
				</div>
				<div class="input-field">
					<input type="text" class="search-box" placeholder="TestBruck3"/>
				</div>
			</div> -->
						<!-- <div class="form-row">
				<div class="form-label">
					<label>Time Lag: <span>*</span></label> 
				</div>
				<div class="input-field">
					<input type="text" class="search-box" placeholder="9"/>
				</div>
			</div> -->

						<!-- User Type -->
						<div class="form-row">
							<div class="form-label">
								<label>User Type: <span>*</span> </label>
							</div>
							<div class="input-field">
								<input id="User_Type_input" type="text" name="User_Type" class="search-box" placeholder="User Type" oninput="validateUser_Type()"/>
							<span class='text_error' id="User_Type_err"></span>
							</div>
						</div>

						<!-- Password -->
						<div class="form-row">
							<div class="form-label">
								<label>Password: <span>*</span> </label>
							</div>
							<div class="input-field">
								<input id ="password_input" type="Password" name="Password" class="search-box" placeholder="Password" oninput="validatePassword()"/>
							<span class='text_error' id="passworderr"></span>

							</div>
						</div>

					<!-- Confirm Password -->
						<div class="form-row">
							<div class="form-label">
								<label>Confirm Password: <span>*</span> </label>
							</div>
							<div class="input-field">
								<input id ="confirm_password_input" type="Password" name="Password" class="search-box" placeholder="Password" oninput="validateConfirmPassword()"/>
							<span class='text_error' id="confirm_password_err"></span>
							</div>
						</div>
						<!-- <div class="form-row">
				<div class="form-label">
					<label>Country: <span></span> </label> 
				</div>
				<div class="input-field">
					<div class="select">
					<select>
						<option>India</option>
						<option>Uk</option>
						<option>Us</option>
					</select>
					</div>
				</div>
			</div> -->
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