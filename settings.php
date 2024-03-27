<?php
include "connect.php";
// Starting the session
if (!isset($_SESSION["user_name"])) {
  header("location:emp_login.php");
}
if($_SESSION["User_role_id"] != 1){
    header("location:emp_login.php");
    exit();
}
?>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Settings</title>

    <!-- Bootstrap -->
    <link href="css/client_dashboard.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">

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
                <h1>Settings</h1>
                <form action="">
                <div class="list-contet">
                    <div class="sup-details">
                        <p>Super Admin Details</p>
                        <div class="sup-list-show">

                            <div class="sup-form-row">
                                <div class="sup-form-label">
                                    <label>Name: </label>
                                </div>
                                <div class="sup-input-field">
                                    <input class="sup-search-box">
                                    <span class='text_error'></span>
                                </div>
                            </div>
                            <div class="sup-form-row">
                                <div class="sup-form-label">
                                    <label>Email: </label>
                                </div>
                                <div class="sup-input-field">
                                    <input class="sup-search-box">
                                    <span class='text_error'></span>
                                </div>
                            </div>
                            <div class="sup-form-row">
                                <div class="sup-form-label">
                                    <label>Mobile Number: </label>
                                </div>
                                <div class="sup-input-field">
                                    <input class="sup-search-box">
                                    <span class='text_error'></span>
                                </div>
                            </div>
                            <div class="sup-form-row">
                                <div class="sup-form-label">
                                    <label>Location: </label>
                                </div>
                                <div class="sup-input-field">
                                    <input class="sup-search-box">
                                    <span class='text_error'></span>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="extra_settings">
                        <div class="extra-settings-form-row">
                            <div class="extra-settings-label">
                                <label>Number of Users shown per page: </label>
                            </div>
                            <div class="extra-settings-field">
                                <input class="extra-settings-search-box">
                                <span class='text_error'></span>
                            </div>
                        </div>
                        <div class="extra-settings-form-row">
                            <div class="extra-settings-label">
                                <label>Link Expired(in Minutes): </label>
                            </div>
                            <div class="extra-settings-field">
                                <input class="extra-settings-search-box">
                                <span class='text_error'></span>
                            </div>
                        </div>
                        <div class="extra-settings-form-row">
                            <div class="extra-settings-label">
                                <label>Select Date Format: </label>
                            </div>
                            <div class="extra-settings-field">
                                <input class="extra-settings-search-box">
                                <span class='text_error'></span>
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-label">
                            <label><span></span> </label>
                        </div>
                        <div class="input-field">
                            <input style="margin-left: 90px; margin-top: 10px; padding: 12px 20px;" type="submit"
                                class="submit-btn" name="Submitasd" value="Update">
                        </div>
                    </div>
                </div>
                </form>
            </div>
        </div>
    </div>

    <!-- <script src="js/client_update.js"></script> -->
</body>

</html>