<?php 
include "connect.php";

function clean_input($fields)
{
	$fields = trim($fields);
	$fields = stripslashes($fields);
	$fields = htmlspecialchars($fields);
	$fields = str_replace("'", "", $fields);
	return $fields;
}
$Name = "";
$Email = "";
$Mobile = "";
$Subject = "";
$Message = "";
$error = false;
// $emailerr = false;

if (isset($_POST["Name"]) && isset($_POST["Email"])  && isset($_POST["Number"]) && isset($_POST["Subject"]) && isset($_POST["Message"])) {
	#Getting data from request
	// $User_Name = clean_input($_POST["User_Name"]);
	$Name = clean_input($_POST["Name"]);
	$Email = clean_input($_POST['Email']);
	$Mobile = clean_input($_POST['Number']);
	$Subject = clean_input($_POST["Subject"]);
	$Message = clean_input($_POST["Message"]);

	if ((isset($Name) && $Name == "") || (isset($Email) && $Email == "") || (isset($Mobile) && $Mobile == "") || (isset($Subject) && $Subject == "") || (isset($Message) && $Message == "")) {
		$error = true;
	}
	if (!preg_match("/^[a-zA-Z\s'-]+$/", $Name) || !filter_var($Email, FILTER_VALIDATE_EMAIL) || !preg_match("/^[0-9]{10}$/", $Mobile) || !preg_match("/^(?![_\W])[\w\d].{0,254}$/", $Subject) || !preg_match("/^(?!(\S{46,}\s*))(?=(\S+\s*){1,128}$).+$/", $Message)) {
		$error = true;
        
	}
	if (!$error) {
		$sql = "INSERT INTO `contact_request` (contact_name, contact_email, contact_number, contact_subject, contact_message) VALUES ('$Name', '$Email' ,'$Mobile', '$Subject', '$Message')";
		$result = mysqli_query($conn, $sql);
		if ($result) {
			// header("location:client_dashboard.php");
            // echo "Sucessfully submitted";    
		} else {
			die(mysqli_error($conn));
		}
	}
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us Form</title>
    <link rel="icon" type="image/x-icon" href="images/arcs_logo.png">
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <div class="main">
        <div class="map">
            <iframe
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3431.723964152326!2d76.72738137628434!3d30.669900574614605!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x390fef724f4664b1%3A0x5cf04152a26499fa!2sArcs%20Infotech!5e0!3m2!1sen!2sin!4v1710740217851!5m2!1sen!2sin"
                width="650" height="650" style="border:0;" allowfullscreen="" loading="lazy"
                referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>
        <div class="contact-us-form">
            <h1>Contact Us</h1>
            <form onsubmit="return validateForm()" action="contact_us_form.php" method="POST">
                <div class="input-contact-us">
                    <input id="name_input" name="Name" placeholder="Name" oninput="validateName()">
                    <span class='text_error1' id="name_err"></span>
                </div>
                <div class="input-contact-us">
                    <input id="email_input" name="Email" placeholder="E-mail" oninput="validateEmail()">
                    <span class='text_error1' id="email_err"></span>
                </div>
                <div class="input-contact-us">
                    <input id="mobile_input" name="Number" placeholder="Number" oninput="validateMobileNumber()">
                    <span class='text_error1' id="mobile_error"></span>
                </div>
                <div class="input-contact-us">
                    <input id="subject_input" name="Subject" placeholder="Subject" oninput="validateSubject()">
                    <span class='text_error1' id="subject_error"></span>
                </div>
                <div class="input-contact-us">
                    <textarea id ="message_input" name="Message" class="form_row" cols="38" rows="6" placeholder="Message" oninput="validateMessage()"></textarea>
                    <span class='text_error2' id="message_error"></span>
                </div>
                <div class="submit-contact-us">
                    <input type="submit" class="submit-btn" name="Submitasd" value="Contact">
                </div>
            </form>
        </div>
    </div>
    <script src="js/contact_us_form.js"></script>
</body>

</html>