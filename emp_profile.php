<?php
include "uber_connect.php";
if (!isset($_SESSION["uber_emp_name"])) {
    header("location:uber_login.php");
}
$image_path = "";
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
        // echo "<img src='{$image_path}' alt='User Profile Image'>";

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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/uber_style.css">
    <link rel="stylesheet" href="css/style.css">
    <title>Employee Profile</title>
</head>

<body>
    <div class="navbar-container">
        <div class="navbar-left-panel">
            <div class="heading-container">
                <div id="heading"><a id="uber-name" href="uber.php">Uber</a></div>
            </div>
            <div class="navbar-options">
                <!-- <div>Ride</div>
                <div>Drive</div>
                <div>Buisness</div>
                <div>Uber eats</div>    
                <div>About</div> -->
            </div>
        </div>
        <div class="navbar-right-panel">
            <div class="navbar-options">
                <!-- <div> EN</div>
                <div>Help</div> -->
                <a style="color: black; background-color: white;" id="login-btn" href="uber_logout.php">Log out</a>
                <!-- <div id="signup-button"> -->
                <a style="color: black" id="signup-btn" href="uber_update.php">Update</a>
                <!-- </div> -->
            </div>
        </div>
    </div>
    <div class="emp-main">
        <?php
        if (isset($_SESSION['uber_flash_message'])) {
            $message = $_SESSION['uber_flash_message'];
            unset($_SESSION['uber_flash_message']);
            echo "<span id='flash-message' class='flash-message'> $message</span>";
        }
        ?>
        <div class="emp-edit-form">
            <div class="emp-edit-btn">
                <a href="uber_update.php">Edit</a>
            </div>
            <h1>My Profile</h1>

            <div class="profile-img">
                <div style="margin-left: 220px;" class="emp-edit-image">
                    <?php $image_path = $image_path != "" ? $image_path : 'https://static.vecteezy.com/system/resources/thumbnails/005/544/718/small_2x/profile-icon-design-free-vector.jpg'; ?>
                    <img id="profile-pic" <?php echo "src='{$image_path}'" ?> alt="">
                </div>
                <!-- <div class="emp-edit-image-data">
                    <label for="input-file">Update Image</label>
                    <input id="input-file" type="file" name="pic" accept="image/jpeg, image/png, image/jpg">
                </div> -->
            </div>
            <div class="emp-edit-form-row">
                <div class="emp-edit-label">
                    <label for="">Name: </label>
                </div>
                <div class="emp-edit-input">
                    <input disabled id="name_input" name="name" placeholder="Name" oninput="validateName()"
                        value="<?php echo $name ?>">
                </div>
            </div>
            <div class="emp-edit-form-row">
                <div class="emp-edit-label">
                    <label for="">Mobile: </label>
                </div>
                <div class="emp-edit-input">
                    <!-- echo "<option value='$countrycode'>" . "+" . $countrycode . "</option>"; -->
                    <input disabled="disabled" value="<?php echo '+' . $countrycode . ' ' . $mobile ?>">
                </div>
                <span class='text_error_3' id="mobile_error"></span>
            </div>
            <div class="emp-edit-form-row">
                <div class="emp-edit-label">
                    <label for="">Email: </label>
                </div>
                <div class="emp-edit-input">
                    <input disabled="disabled" id="email_input" name="email" placeholder="E-mail"
                        value="<?php echo $email ?>">
                </div>
                <span class='text_error_3' id="email_err"></span>
            </div>
            <div class="emp-edit-form-row">
                <div class="emp-edit-label">
                    <label for="">Gender: </label>
                </div>

                <div style="margin-top: 5px;" class="emp-edit-input">
                    <input disabled='disabled' value='<?php echo $gender ?>'>
                </div>
                <span style="margin-top: 45px;" class='text_error_3' id="gender_error"></span>
            </div>
            <div class="emp-edit-form-row">
                <div class="emp-edit-label">
                    <label for="">Location: </label>
                </div>
                <div class="emp-edit-input">
                    <?php
                    $sql_countries = "Select country_name from `countries` WHERE country_id = $country";
                    $result_countries = mysqli_query($conn, $sql_countries);
                    $row = mysqli_fetch_array($result_countries);
                    $country_name = $row['country_name'];
                    ?>
                    <input disabled="disabled" style=" width:150px; margin-right: 20px;" type="text"
                        value="<?php echo $country_name ?>">

                    <?php
                    $sql_states = "SELECT state_name FROM `states` WHERE state_id = $state";
                    $result_states = mysqli_query($conn, $sql_states);
                    $row = mysqli_fetch_array($result_states);
                    $state_name = $row['state_name'];
                    ?>
                    <input disabled="disabled" style=" width:155px;" type="text" value="<?php echo $state_name ?>">
                </div>
            </div>
            <!-- <div class="submit-contact-us">
                <input type="submit" class="submit-btn" name="Submitasd" value="Update">
            </div> -->
        </div>
    </div>
    <script>
        // For Flash Messages
    setTimeout(function () {
      document.getElementById("flash-message").style.display = 'none';
    }, 3000);
    </script>
</body>

</html>