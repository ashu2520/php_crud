<?php
include "uber_connect.php";
if (!isset($_SESSION["uber_emp_name"])) {
    header("location:uber_login.php");
}
function clean_input($fields)
{
    $fields = trim($fields);
    $fields = stripslashes($fields);
    $fields = htmlspecialchars($fields);
    $fields = str_replace("'", "", $fields);
    return $fields;
}
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

    $image = $_FILES['pic'];
    $id = $_SESSION["uber_id"];
    $user_dir = "uploads/" . $id . "/";

    // print_r($_POST);
    // echo $name ." ". $mobile ." ". $gender ." ". $country ." ". $state ." ". $status ." ". $position ." ". $role; 

    if ((isset($name) && $name == "") || (isset($mobile) && $mobile == "") || (isset($gender) && $gender == "") || (isset($country) && $country == "") || (isset($state) && $state == "") || !preg_match("/^[a-zA-Z\s'-]+$/", $name) || !preg_match("/^\+\d{1,4}\s?([1-9]\d{5,11})$/", $mobile)) {
        $error = true;
    }
    if (!$error) {
        $sql_1 = "UPDATE `employees` set emp_name='$name', emp_mobile='$mobile', emp_gender= '$gender', emp_country = '$country', emp_state = '$state', emp_updated_at=CURRENT_TIMESTAMP where emp_id = $id";
        $result_1 = mysqli_query($conn, $sql_1);
        if ($result_1) {
            if (!empty($image['tmp_name'])) {
                // Check if the user's directory exists
                if (!file_exists($user_dir)) {
                    mkdir($user_dir, 0777, true);
                } else {
                    // Check if "dp.png" already exists
                    $target_path = $user_dir . "dp.png";
                    if (file_exists($target_path)) {
                        unlink($target_path);
                    }
                }

                // Move the uploaded file to the user's directory
                $filename = "dp.png";
                $target_path = $user_dir . $filename;
                move_uploaded_file($image['tmp_name'], $target_path);
            }
            $_SESSION['uber_flash_message'] = "Details Updated Successfully";
            header("location:emp_profile.php");
            exit();
        } else {
            die(mysqli_error($conn));
        }
    }
}
$id = $_SESSION['uber_id'];
$sql = "SELECT * FROM `employees` WHERE emp_id = $id";
$result = mysqli_query($conn, $sql);
// Check if the query was successful
if ($result) {
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);

        $name = $row["emp_name"];
        $email = $row["emp_email"];
        $mobile = $row["emp_mobile"];

        $pattern = '/^\+(\d+)\s*/';
        preg_match($pattern, $mobile, $matches); // $matches will contain the matched groups i.e country code
        $countrycode = (int) $matches[1];
        $mobile = preg_replace($pattern, '', $mobile);

        $country = $row['emp_country'];
        $state = $row['emp_state'];
        $gender = $row["emp_gender"];

        // Directory path to the user's dp.png
        $image_path = "uploads/{$id}/dp.png";
        if (!file_exists($image_path)) {
            $image_path = "";
        }

    } else {
        // No data found for the provided ID
        $_SESSION['uber_flash_message'] = "No data found";
        header('location:emp_profile.php');
        exit();
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/uber_style.css">
    <link rel="stylesheet" href="css/style.css">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script type='text/javascript' src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <!-- Required for using jQuery input mask plugin -->
    <script type='text/javascript'
        src="https://rawgit.com/RobinHerbots/jquery.inputmask/3.x/dist/jquery.inputmask.bundle.js"></script>
    <title>Employee Update</title>
</head>

<body>
    <div class="navbar-container">
        <div class="navbar-left-panel">
            <div class="heading-container">
                <div id="heading"><a id="uber-name" href="uber.php">Uber</a></div>
            </div>
            <div class="navbar-options">
            </div>
        </div>
        <div class="navbar-right-panel">
            <div class="navbar-options">
                <a style="color: black; background-color: white;" id="login-btn" href="uber_logout.php">Log out</a>
                <!-- <div id="signup-button"> -->
                <a style="color: black" id="signup-btn" href="emp_profile.php">Back</a>
                <!-- </div> -->
            </div>

        </div>

    </div>
    <div class="emp-main">
        <div style="height: 860px;" class="emp-edit-form">
            <h1>My Profile</h1>
            <form onsubmit="return validateForm()" action="uber_update.php" method="POST" enctype="multipart/form-data">
                <div class="profile-img">
                    <div class="emp-edit-image">
                        <?php $image_path = $image_path != "" ? $image_path : 'https://static.vecteezy.com/system/resources/thumbnails/005/544/718/small_2x/profile-icon-design-free-vector.jpg'; ?>
                        <img id="profile-pic" <?php echo "src='{$image_path}'" ?> alt="">
                    </div>
                    <div class="emp-edit-image-data">
                        <label for="input-file">Upload Image</label>
                        <input id="input-file" type="file" name="pic" accept="image/jpeg, image/png, image/jpg">
                    </div>
                </div>
                <div class="emp-edit-form-row">
                    <div class="emp-edit-label">
                        <label for="">Name: </label>
                    </div>
                    <div class="emp-edit-input">
                        <input id="name_input" name="name" placeholder="Name" oninput="validateName()"
                            value="<?php echo $name ?>">
                    </div>
                    <span class='text_error_3' id="name_err"></span>
                </div>
                <div class="emp-edit-form-row">
                    <div class="emp-edit-label">
                        <label for="">Mobile: </label>
                    </div>
                    <div class="emp-edit-input">
                        <select id="country_code" class="form-control" name="country_code">
                            <?php
                            echo "<option value='$countrycode'>" . "+" . $countrycode . "</option>";
                            // Fetching country phonecodes
                            $sql_countries_phonecode = "SELECT * FROM `countries` WHERE country_phonecode != $countrycode";
                            $result_countries_phonecode = mysqli_query($conn, $sql_countries_phonecode);

                            while ($row = mysqli_fetch_assoc($result_countries_phonecode)) {
                                $country_id = $row['country_id'];
                                $country_phonecode = (int) $row['country_phonecode'];
                                echo "<option $selected value='$country_phonecode'>" . "+" . $country_phonecode . "</option>";
                            }
                            ?>
                        </select>
                        <input style="width: 260px; " id="mobile_input" type="text" name="mobile" class="search-box"
                            placeholder="####-###-###" value="<?php echo $mobile ?>">
                    </div>
                    <span class='text_error_3' id="mobile_error"></span>
                </div>
                <div class="emp-edit-form-row">
                    <div class="emp-edit-label">
                        <label for="">Email: </label>
                    </div>
                    <div class="emp-edit-input">
                        <input style="background-color: #DCDCDC;" disabled id="email_input" name="email"
                            placeholder="E-mail" value="<?php echo $email ?>">
                    </div>
                </div>
                <div class="emp-edit-form-row">
                    <div style="display: flex; padding-top: 0px; margin-top: 0px; margin-bottom: 10px;"
                        class="emp-edit-label">
                        <label for="">Gender: </label>
                    </div>
                    <div style="width: 370px; margin-top: 0px;" class="emp-edit-input">
                        <label><input style="width: 13px; position: relative; top:10px;" id="gender_male" type="radio"
                                name="gender" value="Male" onblur="vaildategender()" <?php if ($gender == "Male") {
                                    echo 'checked';
                                } ?>>
                            <span>Male</span></label>

                        <label><input style="width: 13px; position: relative; top:10px;" id="gender_female" type="radio"
                                name="gender" value="Female" onblur="vaildategender()" <?php if ($gender == "Female") {
                                    echo 'checked';
                                } ?>>
                            <span>Female</span> </label>
                    </div>
                    <span style="margin-top: 45px;" class='text_error_3' id="gender_error"></span>
                </div>
                <div class="emp-edit-form-row">
                    <div class="emp-edit-label">
                        <label for="">Location: </label>
                    </div>
                    <div class="emp-edit-input">
                        <select style=" width:170px; margin-right: 20px;" id="country_select" class="form-select"
                            name="country" autocomplete="off" onchange="loadCountry()">
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
                        <select style=" width:175px;" id="state_select" class="form-select" name="state"
                            autocomplete="off" onblur="validatelocation()">
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
                    <span class='text_error_3' id="location_error"></span>
                </div>
                <div class="submit-contact-us">
                    <a id="submit-btn" href="emp_profile.php">Back</a>
                    <input type="submit" class="submit-btn" name="Submitasd" value="Update">
                </div>
            </form>

        </div>
    </div>
    <script>
        let profile_pic = document.getElementById("profile-pic");
        let input_file = document.getElementById("input-file");

        input_file.onchange = function () {
            profile_pic.src = URL.createObjectURL(input_file.files[0]);
        }
    </script>
    <script src="js/emp_edit.js"></script>
</body>

</html>