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

$id = "";
$name = "";
$email = "";
$mobile = "";
$gender = "";
$country = "";
$state = "";
$position = "";
$role = "";
$error = false;
$emailerr = false;

if (isset($_GET['updateid'])) {
	$id = clean_input($_GET['updateid']);

	// Check if the id is valid
	if (!empty($id) && is_numeric($id)) {
		$sql = "SELECT * FROM `users` WHERE user_id = $id";
		$result = mysqli_query($conn, $sql);

		// Check if the query was successful
		if ($result) {
			if (mysqli_num_rows($result) > 0) {
				$row = mysqli_fetch_assoc($result);

				$name = $row["user_name"];
				$email = $row["user_email"];
				$mobile = $row["user_mobile"];

				$pattern = '/^\+(\d+)\s*/';
				preg_match($pattern, $mobile, $matches); // $matches will contain the matched groups i.e country code
				$countrycode = (int) $matches[1];
				$mobile = preg_replace($pattern, '', $mobile);

				$country = $row['user_country'];
				$state = $row['user_state'];
				$gender = $row["user_gender"];
				$user_status = $row['user_status'];
				$position = $row['user_type'];
				$role = $row['user_role_id'];
				// echo $role;
				if ($role == 1) {
					$_SESSION['flash_message'] = "Invalid ID provided";
					header('location:client_dashboard.php');
					exit();
				}

			} else {
				// No data found for the provided ID
				$_SESSION['flash_message'] = "No data found";
				header('location:client_dashboard.php');
				exit();
			}
		} else {
			// Error in executing the query
			$_SESSION['flash_message'] = "No data found";
			header('location:client_dashboard.php');
			exit();

		}
	} else {
		// Invalid or missing ID
		$_SESSION['flash_message'] = "Invalid ID provided";
		header('location:client_dashboard.php');
		exit();

	}


}

// To check curr_page
if (isset($_GET["page"])) {
	$curr_page = clean_input($_GET["page"]);
	if (is_int($curr_page) || $curr_page < 1) {
		$curr_page = 1;
	}
} else {
	$curr_page = 1;
}

// To check Column Name
if (isset($_GET["column_name"])) {
	$column_name = clean_input($_GET["column_name"]);
	if ($column_name !== "user_id" && $column_name !== "user_name" && $column_name !== "user_gender" && $column_name !== "user_mobile" && $column_name !== "user_email" && $column_name !== "user_type") {
		$column_name = "user_created_at";
	}
} else {
	$column_name = "user_created_at";
}

// To check Sort order
if (isset($_GET["sort_order"])) {
	$sort_order = clean_input($_GET["sort_order"]);
	if ($sort_order !== "ASC" && $sort_order !== "DESC") {
		$sort_order = "DESC";
	}
} else {
	$sort_order = "DESC";
}

// When we Update the user.
if (isset($_POST["Submitasd"])) {
	// #Getting data from request
	$name = clean_input($_POST["name"]);
	$country_code = strval($_POST['country_code']);
	$mobile = clean_input($_POST["mobile"]);

	$mobile = str_replace("(", "", $mobile);
	$mobile = str_replace(")", "", $mobile);
	$mobile = str_replace("-", "", $mobile);
	$mobile = str_replace(" ", "", $mobile);

	$mobile = "+" . $country_code . " " . $mobile;

	if (isset($_POST["gender"])) {
		$gender = $_POST["gender"];
	}
	$country = (int) clean_input($_POST["country"]);
	$state = (int) clean_input($_POST["state"]);

	$status = clean_input($_POST["status"]);
	$position = clean_input($_POST["User_type"]);
	$role = clean_input($_POST['role_type']);

	// print_r($_POST);
	// echo $name ." ". $mobile ." ". $gender ." ". $country ." ". $state ." ". $status ." ". $position ." ". $role; 

	if ((isset($name) && $name == "") || (isset($mobile) && $mobile == "") || (isset($gender) && $gender == "") || (isset($country) && $country == "") || (isset($state) && $state == "") || (isset($status) && $status == "") || (isset($position) && $position == "") || (isset($role) && $role == "") || !preg_match("/^[a-zA-Z\s'-]+$/", $name) || !preg_match("/^\+\d{1,4}\s?([1-9]\d{5,11})$/", $mobile)) {
		$error = true;
	}
	if (!$error) {
		$sql_1 = "UPDATE `users` set user_name='$name', user_mobile='$mobile', user_gender= '$gender', user_country = '$country', user_state = '$state', user_status = '$status', user_type ='$position',  user_role_id  =$role, user_updated_at=CURRENT_TIMESTAMP where user_id = $id";
		$result_1 = mysqli_query($conn, $sql_1);
		if ($result_1) {
			$_SESSION['flash_message'] = "Sucessfully Updated";
			header("location:client_dashboard.php?column_name=$column_name&sort_order=$sort_order&page=$curr_page");
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

	<!-- Bootstrap -->
	<link href="css/client_dashboard.css" rel="stylesheet">
	<script type='text/javascript' src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
	<!-- Required for using jQuery input mask plugin -->
	<script type='text/javascript' src="https://rawgit.com/RobinHerbots/jquery.inputmask/3.x/dist/jquery.inputmask.bundle.js"></script>
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
								<!-- Country Code Select -->
								<select style="width: 86px; height: 35px;" id="country_code" class="form-control"
									name="country_code">
									<?php
									echo "<option value='$countrycode'>" . "+" . $countrycode . "</option>";
									// Fetching country phonecodes
									$sql_countries_phonecode = "SELECT * FROM `countries` WHERE country_phonecode";
									$result_countries_phonecode = mysqli_query($conn, $sql_countries_phonecode);

									while ($row = mysqli_fetch_assoc($result_countries_phonecode)) {
										$country_id = $row['country_id'];
										// echo $country_id;
										$country_phonecode = (int) $row['country_phonecode'];
										// $selected = ($country_code == $country_phonecode) ? 'selected' : '';
									
										echo "<option $selected value='$country_phonecode'>" . "+" . $country_phonecode . "</option>";
									}
									?>
								</select>
								<?php
								// ab humare pass counry code hai...
								
								?>
								<input style="width: 207px; height: 35px;" id="mobile_input" type="text" name="mobile"
									class="search-box" placeholder="####-###-###" value="<?php echo $mobile ?>">
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
								<input id="email_input" type="text" Name="email" class="search-box" placeholder="Email"
									disabled value="<?php echo $email ?>">
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
										onblur="vaildategender()" <?php if ($gender == "Male") {
											echo 'checked';
										} ?>>
									<span style="margin-right: 21px;">Male</span></label><label>
									<input id="gender_female" type="radio" name="gender" value="Female"
										onblur="vaildategender()" <?php if ($gender == "Female") {
											echo 'checked';
										} ?>>
									<span>Female</span> </label>
								<span class='text_error' id="gender_error"></span>
							</div>
						</div>

						<!-- Location -->
						<div class="form-row">
							<div class="form-label">
								<label>Location: <span>*</span></label>
							</div>
							<div class="input-field">
								<div class="input-field">
									<select style=" width:140px; margin-top: 3px; height: 35px; margin-right: 20px;"
										id="country_select" class="form-select" name="country" autocomplete="off"
										onchange="loadCountry()">
										<option>Select Country</option>
										<?php
										$sql_countries = "Select * from `countries`";
										$result_countries = mysqli_query($conn, $sql_countries);
										while ($row = mysqli_fetch_array($result_countries)) {
											$country_name = $row['country_name'];
											$country_id = $row['country_id'];
											$selected = ($country == $country_id) ? 'selected' : '';
											echo "<option $selected value='$country_id'>" . $country_name . "</option>";

										}
										?>
									</select>
									<select style=" width:130px; margin-top: 3px; height: 35px;" id="state_select"
										class="form-select" name="state" autocomplete="off" onblur="validatelocation()">
										<option>Select State</option>

										<?php
										$sql_states = "SELECT * FROM `states` WHERE state_country_id = $country";
										$result_states = mysqli_query($conn, $sql_states);
										while ($row = mysqli_fetch_array($result_states)) {
											$state_name = $row['state_name'];
											$state_id = $row['state_id'];
											$selected = ($state == $state_id) ? 'selected' : '';
											echo "<option $selected value='$state_id'>" . $state_name . "</option>";

										}
										?>
									</select>
								</div>
								<span class='text_error' id="location_error"></span>
							</div>
						</div>

						<!-- Status -->
						<div class="form-row radio-row">
							<div class="form-label">
								<label>Status: <span>*</span> </label>
							</div>
							<div class="input-field">
								<label><input id="status_active" type="radio" name="status" value="Active"
										onblur="validatestatus()" <?php if ($user_status == "Active") {
											echo 'checked';
										} ?>>
									<span style="margin-right: 15px;">Active </span></label><label>
									<input id="status_inactive" type="radio" name="status" value="Inactive"
										onblur="validatestatus()" <?php if ($gender == "Inactive") {
											echo 'checked';
										} ?>>
									<span style="margin-right: 15px;">Inactive</span>
									<input id="status_suspend" type="radio" name="status" value="Suspend"
										onblur="validatestatus()" <?php if ($gender == "Suspend") {
											echo 'checked';
										} ?>>
									<span>Suspend</span> </label>
								<span class='text_error' id="status_error"></span>
							</div>
						</div>

						<!-- User Type -->
						<div class="form-row">
							<div class="form-label">
								<label>Position: <span>*</span></label>
							</div>
							<div class="input-field">
								<div class="input-field">
									<select style="margin-top: 3px; height: 35px;" id="User_type_input"
										class="form-select" name="User_type" autocomplete="off">
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
								<select style="margin-top: 3px; height: 35px;" id="role_input" class="form-select"
									name="role_type" autocomplete="off">
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