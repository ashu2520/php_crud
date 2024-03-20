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
$id="";
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

if (isset($_GET['updateid'])) {
    $id = clean_input($_GET['updateid']);
	
	$column_name = $_GET['column_name'];
	$sort_order = $_GET['sort_order'];
	$curr_page = (int)$_GET['page'];


    $sql = "Select * from users_list where Id = $id";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);

	$First_Name = $row["First_Name"];
	$Last_Name = $row["Last_Name"];
	$Gender = $row["Gender"];
	$Age = $row["Age"];
	$Email = $row['Email'];
	$User_Type = $row['User_Type'];
	
}

if (isset($_POST["Submitasd"])) {
	#Getting data from request
	// $User_Name = clean_input($_POST["User_Name"]);
	$First_Name = clean_input($_POST["First_Name"]);
	$Last_Name = clean_input($_POST["Last_Name"]);
	if(isset($_POST["Gender"]))
	{
		$Gender = $_POST["Gender"];
	}
	$Age = clean_input($_POST["Age"]);
	// $Email = clean_input($_POST['Email']);
	$User_Type = clean_input($_POST['User_Type']);
	// $Password = clean_input($_POST["Password"]);


	if (!isset($Gender) || (isset($First_Name) && $First_Name == "") || (isset($User_Type) && $User_Type == "")) {
		$error = true;
	}
	if (!preg_match("/^[a-zA-Z\s'-]+$/", $First_Name) || !preg_match("/^[a-zA-Z\s'-]*$/", $Last_Name) || !preg_match("/^[1-9][0-9]*$/", $Age) || !preg_match("/^[a-zA-Z\s'-]+$/", $User_Type)) {
		$error = true;

	}
	if (!$error) {
		$sql_1 = "UPDATE `users_list` set Id = $id, First_Name='$First_Name', Last_Name='$Last_Name', Gender= '$Gender', Age =$Age, User_Type ='$User_Type', Updatedat=CURRENT_TIMESTAMP where Id = $id";
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
					<form id="main" class="form-edit" onsubmit="return validateForm()" action="client_update.php?updateid=<?php echo $id ?>&column_name=<?php echo $column_name ?>&sort_order=<?php echo $sort_order ?>&page=<?php echo $curr_page ?>" method="POST">

						<!-- First Name -->
						<div class="form-row">
							<div class="form-label">
								<label>First Name: <span>*</span></label>
							</div>
							<div class="input-field">
								<input id="First_Name_input" type="text" name="First_Name" class="search-box" placeholder="First Name" oninput="validateFirst_Name()" value=<?php echo "$First_Name" ?>>
								<span class='text_error' id="First_Name_err"></span>
							</div>
						</div>

						<!-- Last Name -->
						<div class="form-row">
							<div class="form-label">
								<label>Last Name: <span></span></label>
							</div>
							<div class="input-field">
								<input id="Last_Name_input" type="text" name="Last_Name" class="search-box" placeholder="Last Name" oninput="validateLast_Name()" value=<?php echo "$Last_Name" ?>>
								<span class='text_error' id="Last_Name_err"></span>
							</div>
						</div>
						<div class="form-row radio-row">
							<div class="form-label">
								<label>Gender: <span>*</span> </label>
							</div>
							<div class="input-field">
								<label><input id="gender_male" type="radio" name="Gender" value="Male" onblur="validategender()"  <?php if($Gender == "Male"){echo 'checked';} ?>> <span>Male</span></label><label>
									<input id="gender_female" type="radio" name="Gender" value="Female" onblur="validategender()"  <?php if($Gender == "Female"){echo 'checked';} ?>> <span>Female</span> </label>
									<span class='text_error' id="gender_error"></span>
							</div>
						</div>

						<!-- Age -->
						<div class="form-row">
							<div class="form-label">
								<label>Age: <span>*</span></label>
							</div>
							<div class="input-field">
								<input id="Age_input" type="text" name="Age" class="search-box" placeholder="Age" oninput="validateAge()" value=<?php echo "$Age" ?>>
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
								<input id="email_input" type="text" Name="Email" class="search-box" placeholder="Email" disabled value=<?php echo "$Email" ?>>
							<span class='text_error' id="email_err"></span>
							</div>
						</div>

						<!-- User Type -->
						<div class="form-row">
							<div class="form-label">
								<label>User Type: <span>*</span> </label>
							</div>
							<div class="input-field">
								<input id="User_Type_input" type="text" name="User_Type" class="search-box" placeholder="User Type" oninput="validateUser_Type()" value=<?php echo "$User_Type" ?>>
							<span class='text_error' id="User_Type_err"></span>
							</div>
						</div>

						<!-- Password -->
						<!-- <div class="form-row">
							<div class="form-label">
								<label>Password: <span>*</span> </label>
							</div>
							<div class="input-field">
								<input id ="password_input" type="text" name="Password" class="search-box" placeholder="Password" disabled"/>
							<span class='text_error' id="passworderr"></span>

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

<script src="js/client_update.js"></script>
</body>

</html>