<?php
include "connect.php";
// Starting the session
if (!isset($_SESSION["user_name"])) {
    header("location:emp_login.php");
}
if ($_SESSION["User_role_id"] != 1) {
    header("location:emp_login.php");
    exit();
}
?>
<?php
$error = false;
$setting_err = false;
function clean_settings_input($fields)
{
    $fields = trim($fields);
    $fields = stripslashes($fields);
    $fields = htmlspecialchars($fields);
    $fields = str_replace("'", "", $fields);
    return $fields;
}
// print_r($_POST);
if (isset($_POST["Submitasd"]) && $_POST["Submitasd"] !== "") {
    $Name = clean_settings_input($_POST['name']);
    $Email = clean_settings_input($_POST['email']);
    $Mobile = clean_settings_input($_POST['mobile']);
    $Gender = clean_settings_input($_POST['gender']);
    $num_per_page = clean_settings_input($_POST["num_per_page"]);
    $link_exp_time = clean_settings_input($_POST["link_exp_time"]);
    $format_date = $_POST["date_format"];

    $id = $_SESSION["Id"];
    // # Email Check

    if (!filter_var($Email, FILTER_VALIDATE_EMAIL) || !(isset($Email)) || $Email == "" || !preg_match("#^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$#", $Email)) {
        $error = true;
    } elseif ((!preg_match("/^[a-zA-Z\s'-]+$/", $Name) || $Name == "")) {
        $error = true;
    } elseif (!preg_match("/^[0-9]{10}$/", $Mobile) || $Mobile == "") {
        $error = true;
    } elseif (!preg_match('/^(male|female)$/i', $Gender) || $Gender == "") {
        $error = true;
    } elseif ($num_per_page == 0 || $num_per_page == "" || $link_exp_time == 0 || $link_exp_time == "") {
        $setting_err = true;
    }
    // echo $Name;
    // var_dump($error);
    // die();
    if (!$error && !$setting_err) {

        $sql_2 = "UPDATE `users` set user_name = '$Name', user_email = '$Email', user_mobile = '$Mobile', user_gender = '$Gender', user_updated_at = CURRENT_TIMESTAMP WHERE user_id = $id";
        $result_2 = mysqli_query($conn, $sql_2);

        // print_r($_POST);

        $sql_1 = "UPDATE `settings` set  setting_updated_at = CURRENT_TIMESTAMP, setting_row_per_page= $num_per_page, setting_token_expiry_time =$link_exp_time,  setting_date_format  = '$format_date' where setting_id = 1";
        $result_1 = mysqli_query($conn, $sql_1);
        if ($result_1) {
            $_SESSION['num_per_page'] = $num_per_page;
            $_SESSION['link_exp_time'] = $link_exp_time;
            $_SESSION["date_format"] = $format_date;

            // $_SESSION["flash_message"] = "Settings Updated Sucessfully";
            echo '<script> alert("Settings Updated Successfully"); </script>';
            header("location:settings.php");
        }
    }

}

?>
<?php
$id = $_SESSION["Id"];
$sql = "SELECT * FROM `users` WHERE user_id = $id ";
$result = mysqli_query($conn, $sql);
if ($result) {
    $row = mysqli_fetch_array($result);
    $Name = $row['user_name'];
    $Email = $row['user_email'];
    $Mobile = $row['user_mobile'];
    $Gender = $row['user_gender'];
}
$sql_setting = "SELECT * FROM `settings` WHERE setting_id = 1";
$result_setting = mysqli_query($conn, $sql_setting);
if ($result_setting) {
    $row = mysqli_fetch_array($result_setting);
    $num_per_page = $row['setting_row_per_page'];
    $link_exp_time = $row['setting_token_expiry_time'];
    $format_date = $row['setting_date_format'];
}
?>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Settings</title>
    <link rel="icon" type="image/x-icon" href="images/arcs_logo.png">

    <!-- Bootstrap -->
    <link href="css/client_dashboard.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">

</head>

<body>
    <?php include "header.php"; ?>
    <div class="clear"></div>
    <div class="clear"></div>
    <div class="content">
        <?php
        if ($error || $setting_err) {
            // unset($_POST["Submitasd"]);
            $_POST["Submitasd"] = "";
            echo '<div id="error-message" style="position: absolute; top: 160px;" class="error-message-div error-msg"><img src="images/unsucess-msg.png"><strong>UnSucess!</strong> Something is not right </div>';
        }
        // elseif($setting_err == true) {
        //     unset($_POST["Submitasd"]);
        //     echo '<div id="error-message" class="error-message-div error-msg"><img src="images/unsucess-msg.png"><strong>UnSucess!</strong> Something is not right </div>';
        // }
        ?>
        <div class="wrapper">
            <div class="bedcram">
            </div>
            <?php include "left_sidebar.php"; ?>
            <div class="right_side_content">
                <h1>Settings</h1>
                <div class="list-contet">
                    <div class="sup-details">
                        <p>Super Admin Details</p>
                        <div class="sup-list-show">
                            <form onsubmit="return validateEmail()" action="settings.php" method="POST">
                                <div class="sup-form-row">
                                    <div class="sup-form-label">
                                        <label>Name: </label>
                                    </div>
                                    <div class="sup-input-field">
                                        <input class="sup-search-box" name="name"
                                            value="<?php echo $Name; ?>">
                                    </div>
                                </div>
                                <div class="sup-form-row">
                                    <div class="sup-form-label">
                                        <label>Email: </label>
                                    </div>
                                    <div class="sup-input-field">
                                        <input id="email_input" class="sup-search-box" name="email" autocomplete="off"
                                            onblur="validateEmail()" value="<?php echo $Email; ?>">
                                    </div>
                                </div>
                                <span class='text_error' id="email_err"></span>
                                <div class="sup-form-row">
                                    <div class="sup-form-label">
                                        <label>Mobile Number: </label>
                                    </div>
                                    <div class="sup-input-field">
                                        <input  class="sup-search-box"  name="mobile"
                                            value="<?php echo $Mobile; ?>">
                                    </div>
                                </div>
                                <div class="sup-form-row">
                                    <label>Gender: </label>
                                    <div class="sup-form-label">
                                    </div>
                                    <div class="sup-input-field">
                                        <input class="sup-search-box" name="gender" value="<?php echo $Gender; ?>">
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
                                <input class="extra-settings-search-box" name="num_per_page" type="number"
                                    value="<?php echo $num_per_page; ?>">
                                <span class='text_error'></span>
                            </div>
                        </div>
                        <div class="extra-settings-form-row">
                            <div class="extra-settings-label">
                                <label>Link Expired(in Minutes): </label>
                            </div>
                            <div class="extra-settings-field">
                                <input class="extra-settings-search-box" name="link_exp_time" type="number"
                                    value="<?php echo $link_exp_time; ?>">
                                <span class='text_error'></span>
                            </div>
                        </div>
                        <div class="extra-settings-form-row">
                            <div class="extra-settings-label">
                                <label>Select Date Format: </label>
                            </div>
                            <div class="extra-settings-field" style="width: 260px; margin-right: 130px">
                                <select name="date_format" id="date_format" class="form-select search-box"
                                    style="border-color: grey; color: black;">
                                    <option value="YYYY-MM-DD" <?php if ($format_date == "YYYY-MM-DD")
                                        echo "selected"; ?>>YYYY-MM-DD</option>
                                    <option value="DD-MM-YYYY" <?php if ($format_date == "DD-MM-YYYY")
                                        echo "selected"; ?>>DD-MM-YYYY</option>
                                </select>
                                <!-- <input class="extra-settings-search-box"> -->
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
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- <script src="js/client_update.js"></script> -->
    <script>
        // function validateForm() {
        //     if (!validateEmail()) {
        //         return false;
        //     } else {
        //         // console.log("Editor Data:", editorData.getData());
        //         alert("Settings Updated Successfully");
        //         return true;
        //     }
        // }
        // let debounceTimer;
        function validateEmail() {
            // clearTimeout(debounceTimer);
            // debounceTimer = setTimeout(() => {
            var email = document.getElementById("email_input").value.trim();
            var emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;

            if (email === "" || !emailRegex.test(email)) {
                // document.getElementById("email_err").innerHTML = "";
                email_input.style.borderColor = "red";
                return false;
            }
            // document.getElementById("email_err").innerHTML = "";
            email_input.style.borderColor = "";

            return true;
            // }, 300);
        }
        setTimeout(function () {
            document.getElementById("error-message").style.display = 'none';
        }, 3000);
    </script>
</body>

</html>