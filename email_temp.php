<?php
include "connect.php";
// Starting the session
if (!isset($_SESSION["user_name"])) {
  header("location:emp_login.php");
}
?>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Email Templates</title>

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
            <h1>Email Template</h1>
            <div class="list-contet">
               
            </div>
        </div>
    </div>
</div>

<!-- <script src="js/client_update.js"></script> -->
</body>

</html>