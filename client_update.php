<?php
include "connect.php";
// Starting the session
if (!isset ($_SESSION["user_name"])) {
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
$id = "";
$name = "";
$email = "";
$mobile = "";
$gender = "";
// $country = "";
// $state = "";
$position = "";
$role = "";
$error = false;
$emailerr = false;

if (isset ($_GET['updateid'])) {
	$id = clean_input($_GET['updateid']);

	$column_name = $_GET['column_name'];
	$sort_order = $_GET['sort_order'];
	$curr_page = (int) $_GET['page'];


	$sql = "Select * from `login_credentials` where Id = $id";
	$result = mysqli_query($conn, $sql);
	$row = mysqli_fetch_assoc($result);

	$name = $row["Name"];
	$email = $row["Email"];
	$mobile = $row["Mobile"];
	$gender = $row["Gender"];
	// $country = $row['Country'];
	// $state = $row['State'];
	$position = $row['User_type'];
	$role = $row['User_role_id'];

}

if (isset ($_POST["Submitasd"])) {
	#Getting data from request
	// $User_Name = clean_input($_POST["User_Name"]);
	$name = clean_input($_POST["name"]);
	// $email = clean_input($_POST["email"]);
	$mobile = clean_input($_POST["mobile"]);
	if (isset ($_POST["gender"])) {
		$gender = $_POST["gender"];
	}
	$position = clean_input($_POST["User_type"]);
	$role = clean_input($_POST['role_type']);
	
	if ((isset($name) && $name == "") || (isset($mobile) && $mobile == "") || (isset($gender) && $gender == "")  || (isset($position) && $position == "")  || (isset($role) && $role == "") || !preg_match("/^[a-zA-Z\s'-]+$/", $name) || !preg_match("/^[0-9]{10}$/", $mobile)) {
		$error = true;
	}
	if (!$error) {
		$sql_1 = "UPDATE `login_credentials` set Id = $id, Name='$name', Mobile='$mobile', Gender= '$gender', User_type ='$position',  User_role_id  =$role, Updatedat=CURRENT_TIMESTAMP where Id = $id";
		$result_1 = mysqli_query($conn, $sql_1);
		if ($result_1) {
			$_SESSION['flash_message'] = "Sucessfully Updated";
			header("location:client_dashboard.php?column_name=$column_name&sort_order=$sort_order&page=$curr_page");
			exit();
		} else {
			die (mysqli_error($conn));
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
	<?php include "header.php"; ?>

	<div class="clear"></div>
	<div class="clear"></div>
	<div class="content">
		<div class="wrapper">
			<div class="bedcram">
			</div>
			<?php include "left_sidebar.php"; ?>
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
					<form id="main" class="form-edit" onsubmit="return validateForm()"
						action="client_update.php?updateid=<?php echo $id ?>&column_name=<?php echo $column_name ?>&sort_order=<?php echo $sort_order ?>&page=<?php echo $curr_page ?>"
						method="POST">

						<!-- Name -->
						<div class="form-row">
							<div class="form-label">
								<label>Name: <span>*</span></label>
							</div>
							<div class="input-field">
								<input id="name_input" type="text" name="name" class="search-box" placeholder="Name"
									oninput="validateName()" value="<?php echo $name ?>">
								<span class='text_error' id="name_err"></span>
							</div>
						</div>

						<!-- Mobile -->
						<div class="form-row">
							<div class="form-label">
								<label>Mobile: <span>*</span></label>
							</div>
							<div class="input-field">
								<input id="mobile_input" type="text" name="mobile" class="search-box" placeholder="Mobile Number" oninput="validateMobileNumber()" value="<?php echo $mobile ?>">
								<span class='text_error' id="mobile_error"></span>
							</div>
							<!-- echo '<p class="error-ms">Please fill this field</p>'; -->
						</div>


						<!-- Email -->
						<div class="form-row">
							<div class="form-label">
								<label>Email: <span></span></label>
							</div>
							<div class="input-field">
								<input id="email_input" type="text" Name="email" class="search-box" placeholder="Email" disabled oninput="validateEmail()" value="<?php echo $email ?>">
								<span class='text_error' id="email_err"></span>
							</div>
						</div>

						<!-- Gender -->
						<div class="form-row radio-row">
							<div class="form-label">
								<label>Gender: <span>*</span> </label>
							</div>
							<div class="input-field">
								<label><input id="gender_male" type="radio" name="gender" value="Male"
										onblur="validategender()" <?php if ($gender == "Male") {
											echo 'checked';
										} ?>>
									<span>Male</span></label><label>
									<input id="gender_female" type="radio" name="gender" value="Female"
										onblur="validategender()" <?php if ($gender == "Female") {
											echo 'checked';
										} ?>>
									<span>Female</span> </label>
								<span class='text_error' id="gender_error"></span>
							</div>
						</div>
				
						<!-- User Type -->
						<div class="form-row">
							<div class="form-label">
								<label>Position: <span>*</span></label>
							</div>
							<div class="input-field">
								<div class="input-field">
									<select id="User_type_input" class="form-select" name="User_type" autocomplete="off"
										onblur="validateposition()">
										<?php
										$options = array("AIML", "Backend", "Cyber Security", "Data Scientist", "Devops", "Frontend", "Full Stack");
										foreach ($options as $option) {
											$selected = ($position == $option) ? 'selected' : '';
											echo "<option value='$option' $selected>$option</option>";
										}
										?>
									</select>
								</div>
							</div>
						</div>


							<!-- Role Type -->
							<div class="form-row">
								<div class="form-label">
									<label>Role: <span>*</span> </label>
								</div>
								<div class="input-field">
									<select id="role_input" class="form-select" name="role_type" autocomplete="off"
										onblur="validaterole()">
										<?php
										$options = array(
											"5" => "Employee",
											"2" => "Admin",
											"3" => "Manager",
											"4" => "Team Lead"
										);
										foreach ($options as $value => $label) {
											$selected = ($role == $value) ? 'selected' : '';
											echo "<option value='$value' $selected>$label</option>";
										}
										?>
									</select>
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

	<script src="js/client_update.js"></script>
</body>

</html>