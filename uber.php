<?php
// include "uber_connect.php";
// if (!isset($_SESSION["uber_emp_name"])) {
//     header("location:uber_login.php");
// }
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

if (isset($_POST["Name"]) && isset($_POST["Email"]) && isset($_POST["Number"]) && isset($_POST["Subject"]) && isset($_POST["Message"])) {
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
    if (!preg_match("/^[a-zA-Z\s'-]+$/", $Name) || !filter_var($Email, FILTER_VALIDATE_EMAIL) || !preg_match("/^[0-9]{10}$/", $Mobile) || !preg_match("/^(?![_\W])[\w\d].{0,254}$/", $Subject)) {
        $error = true;

    }
    if (!$error) {
        $sql = "INSERT INTO `contact_request` (contact_name, contact_email, contact_number, contact_subject, contact_message) VALUES ('$Name', '$Email' ,'$Mobile', '$Subject', '$Message')";
        $result = mysqli_query($conn, $sql);
        if ($result) {
            header("location:uber.php");
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
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/uber_style.css">
    <link rel="stylesheet" href="registration.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
    <div>
        <div class="navbar-container">
            <div class="navbar-left-panel">
                <div class="heading-container">
                    <div id="heading"><a id="uber-name" href="uber.php">Uber</a></div>
                </div>
                <div class="navbar-options">
                    <div>Ride</div>
                    <div>Drive</div>
                    <div>Buisness</div>
                    <div>Uber eats</div>
                    <div>About</div>
                </div>
            </div>
            <div class="navbar-right-panel">
                <div class="navbar-options">
                    <div> EN</div>
                    <div>Help</div>
                    <a style="color: black; background-color: white;" id="login-btn" href="">Log In</a>
                    <!-- <div id="signup-button"> -->
                    <a style="color: black" id="signup-btn" href="">Sign Up</a>
                    <!-- </div> -->
                </div>

            </div>

        </div>

        <div class="page-1">
            <div class="main-container-1">
                <div class="details">
                    <h1>Go Anywhere with Uber</h1>
                    <div id="request">Request a ride, hop in, and go.</div>
                    <div class="input-details">
                        <input placeholder="Enter location" />
                        <br>
                        <input placeholder="Enter destination" />
                    </div>

                    <button class="button-container">See Prices</button>

                </div>
                <div class="image-container">
                    <img
                        src="https://www.uber-assets.com/image/upload/f_auto,q_auto:eco,c_fill,w_558,h_698/v1684852612/assets/ba/4947c1-b862-400e-9f00-668f4926a4a2/original/Ride-with-Uber.png">
                </div>
            </div>
        </div>

        <div class="page-2">
            <div class="main-container-2">
                <div class="image-container-2">
                    <img
                        src="https://www.uber-assets.com/image/upload/f_auto,q_auto:eco,c_fill,w_558,h_558/v1684855112/assets/96/4dd3d1-94e7-481e-b28c-08d59353b9e0/original/earner-illustra.png">
                </div>
                <div class="text-container-2">
                    <h1>Drive when you want, make what you need
                    </h1>
                    <p>Make money on your schedule with deliveries or rides—or both. You can use your own car or choose
                        a rental through Uber.</p>
                    <div class="page-2-button">
                        <button id="get-started-button">Get started</button>
                        <button id="already-button">Already have an account? Sign in</button>

                    </div>
                </div>

            </div>

        </div>

        <div class="page-2">
            <div class="main-container-2">

                <div class="text-container-2">
                    <h1>The Uber you know, reimagined for business
                    </h1>
                    <p>Uber for Business is a platform for managing global rides and meals, and local deliveries, for
                        companies of any size.</p>
                    <div class="page-2-button">
                        <button id="get-started-button">Get started</button>
                        <button id="already-button">Check Out for our solutions</button>

                    </div>
                </div>
                <div class="image-container-2">
                    <img
                        src="https://www.uber-assets.com/image/upload/f_auto,q_auto:eco,c_fill,w_558,h_558/v1684887108/assets/76/baf1ea-385a-408c-846b-59211086196c/original/u4b-square.png">
                </div>

            </div>
        </div>

        <div class="page-2">
            <div class="main-container-2">
                <div class="image-container-2">
                    <img
                        src="https://www.uber-assets.com/image/upload/f_auto,q_auto:eco,c_fill,w_558,h_558/v1696243819/assets/18/34e6fd-33e3-4c95-ad7a-f484a8c812d7/original/fleet-management.jpg">
                </div>
                <div class="text-container-2">
                    <h1>Make money by renting out your car</h1>
                    <p>Connect with thousands of drivers and earn more per week with Uber’s free fleet management tools.
                    </p>
                    <div class="page-2-button">
                        <button id="get-started-button">Get started</button>
                    </div>
                </div>

            </div>

        </div>

        <div class="page-3">
            <div class="page-3-container">
                <h1>It’s easier in the apps</h1>
                <div class="qr-codes-container">
                    <div class="qr">
                        <img
                            src="https://www.uber-assets.com/image/upload/f_auto,q_auto:eco,c_fill,w_150,h_150/v1690807720/assets/a5/9986ad-0d9f-4396-8539-389bce97f579/original/Final-Download-Uber-App.png">
                        <div class="qr-text">
                            <h2>Download the Uber app</h2>
                            <div class="scan">Scan and download</div>
                        </div>
                        <div class="arrow-container">
                            <i class="fas fa-arrow-right"></i>
                        </div>

                    </div>
                    <div class="qr">
                        <img
                            src="https://www.uber-assets.com/image/upload/f_auto,q_auto:eco,c_fill,w_150,h_150/v1690807720/assets/a5/9986ad-0d9f-4396-8539-389bce97f579/original/Final-Download-Uber-App.png">
                        <div class="qr-text">
                            <h2>Download the Uber app</h3>
                                <div class="scan">Scan and download</div>
                        </div>
                        <div class="arrow-container">
                            <i class="fas fa-arrow-right"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="main">
            <div class="map">
                <iframe
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3431.723964152326!2d76.72738137628434!3d30.669900574614605!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x390fef724f4664b1%3A0x5cf04152a26499fa!2sArcs%20Infotech!5e0!3m2!1sen!2sin!4v1710740217851!5m2!1sen!2sin"
                    width="650" height="650" style="border:0;" allowfullscreen="" loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
            <div class="contact-us-form">
                <h1>Contact Us</h1>
                <form onsubmit="return validateForm()" action="uber.php" method="POST">
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
                    <div class="input-contact-us_1">
                        <textarea id="message_input" name="Message" class="form_row" cols="38" rows="6"
                            placeholder="Message" oninput="validateMessage()"></textarea>
                        <span class='text_error2' id="message_error"></span>
                    </div>
                    <div class="submit-contact-us">
                        <input type="submit" class="submit-btn" name="Submitasd" value="Contact">
                    </div>
                </form>
            </div>
        </div>
        <!-- <div class="emp-edit">
            <h2>Update Details</h2>
            <form action="">
                <div>
                    <label for="">Name: </label>
                    <input type="text">
                </div>
            <div>
                <label for="">Mobile: </label>
                <input type="text">
            </div>
            <div>
                <label for="">Email: </label>
                <input type="text">
            </div>
            <div>
                <label for="">Gender: </label>
                <input type="text">
            </div>
            <div>
                <label for="">Location: </label>
                <input type="text">
            </div>
            <div>
                <input type="submit" name="submit" id="emp-submit" value=" Submit ">
            </div>
        </form>
        </div> -->

    </div>
    <div class="footer">
        <div class="footer-container">
            <a id="uber">Uber</a>
            <br>
            <a id="help-center">Visit Help center</a>
            <div class="table-container">
                <div class="table-col">
                    <div class="table-heading">
                        Company
                    </div>

                    <div>About us</div>
                    <div>Our offering</div>
                    <div>Newsroom</div>
                    <div>Investors</div>
                    <div>blog</div>
                    <div>Careers</div>
                    <div>AI</div>
                    <div>Gift Card</div>
                </div>
                <div class="table-col">
                    <div class="table-heading">
                        Products
                    </div>

                    <div>Ride</div>
                    <div>Ride</div>
                    <div>Deliver</div>
                    <div>Eat</div>
                    <div>Uber for buisness</div>
                    <div>Uber Freight</div>
                </div>

                <div class="table-col">
                    <div class="table-heading">
                        Global Citizenship
                    </div>

                    <div>Safety</div>
                    <div>Diversity and Inclusion</div>

                </div>

                <div class="table-col">
                    <div class="table-heading">
                        Travel
                    </div>

                    <div>Reserve</div>
                    <div>Airports</div>
                    <div>Cities</div>

                </div>
            </div>
            <div class="contacts">
                <div class="socials">
                    <svg width="1em" height="1em" viewBox="0 0 24 24" fill="none">
                        <title>facebook</title>
                        <path
                            d="M21.79 1H2.21C1.54 1 1 1.54 1 2.21v19.57c0 .68.54 1.22 1.21 1.22h10.54v-8.51H9.9v-3.33h2.86V8.71c0-2.84 1.74-4.39 4.27-4.39.85 0 1.71.04 2.56.13v2.97h-1.75c-1.38 0-1.65.65-1.65 1.62v2.12h3.3l-.43 3.33h-2.89V23h5.61c.67 0 1.21-.54 1.21-1.21V2.21C23 1.54 22.46 1 21.79 1Z"
                            fill="currentColor"></path>
                    </svg>

                    <svg width="1em" height="1em" viewBox="0 0 24 24" fill="none">
                        <title>twitter</title>
                        <path
                            d="M23 5.13c-.81.36-1.69.61-2.61.72.94-.56 1.66-1.45 2-2.51-.88.52-1.85.9-2.89 1.1A4.558 4.558 0 0 0 16.18 3a4.543 4.543 0 0 0-4.42 5.58c-3.78-.19-7.13-2-9.37-4.75-.39.67-.62 1.45-.62 2.28 0 1.58.8 2.97 2.02 3.78-.75-.02-1.45-.23-2.06-.57v.06c0 2.2 1.57 4.04 3.65 4.45-.38.12-.78.17-1.19.17-.29 0-.58-.03-.85-.08a4.557 4.557 0 0 0 4.25 3.16 9.112 9.112 0 0 1-5.64 1.95c-.37 0-.73-.02-1.08-.06 2.01 1.29 4.4 2.04 6.97 2.04 8.36 0 12.93-6.92 12.93-12.93 0-.2 0-.39-.01-.59.86-.65 1.63-1.45 2.24-2.36Z"
                            fill="currentColor"></path>
                    </svg>

                    <svg width="1em" height="1em" viewBox="0 0 24 24" fill="none">
                        <title>youtube</title>
                        <path
                            d="M23 12s0-3.85-.46-5.58c-.25-.95-1-1.7-1.94-1.96C18.88 4 12 4 12 4s-6.88 0-8.6.46c-.95.25-1.69 1.01-1.94 1.96C1 8.15 1 12 1 12s.04 3.85.5 5.58c.25.95 1 1.7 1.95 1.96 1.71.46 8.59.46 8.59.46s6.88 0 8.6-.46c.95-.25 1.69-1.01 1.94-1.96.46-1.73.42-5.58.42-5.58Zm-13 3.27V8.73L15.5 12 10 15.27Z"
                            fill="currentColor"></path>
                    </svg>

                    <svg width="1em" height="1em" viewBox="0 0 24 24" fill="none">
                        <title>linkedin</title>
                        <path
                            d="M21.37 1H2.62C1.73 1 1 1.71 1 2.58v18.83c0 .88.73 1.59 1.62 1.59h18.75c.9 0 1.63-.71 1.63-1.59V2.58C23 1.71 22.27 1 21.37 1ZM7.53 19.75H4.26V9.25h3.27v10.5ZM5.89 7.81C4.85 7.81 4 6.96 4 5.92s.84-1.89 1.89-1.89c1.04 0 1.89.85 1.89 1.89.01 1.04-.84 1.89-1.89 1.89Zm13.86 11.94h-3.26v-5.1c0-1.22-.02-2.78-1.7-2.78-1.7 0-1.96 1.33-1.96 2.7v5.19H9.57V9.26h3.13v1.43h.04c.44-.83 1.5-1.7 3.09-1.7 3.3 0 3.91 2.17 3.91 5v5.76h.01Z"
                            fill="currentColor"></path>
                    </svg>

                    <svg width="1em" height="1em" viewBox="0 0 24 24" fill="none">
                        <title>instagram</title>
                        <g fill="currentColor">
                            <path
                                d="M21.15 2.85C19.05.74 16.23 1 12 1 8.04 1 5 .69 2.85 2.85.74 4.95 1 7.77 1 12c0 3.95-.31 7 1.85 9.15C4.95 23.26 7.77 23 12 23c3.96 0 7 .31 9.15-1.85C23.25 19.05 23 16.23 23 12c0-4.31.24-7.07-1.85-9.15Zm-1.4 16.9c-1.37 1.37-3.18 1.27-7.75 1.27-4.29 0-6.34.15-7.75-1.27-1.44-1.44-1.27-3.51-1.27-7.75 0-4.23-.15-6.33 1.27-7.75C5.66 2.84 7.6 2.98 12 2.98c4.23 0 6.33-.15 7.75 1.27 1.38 1.38 1.27 3.22 1.27 7.75 0 4.24.15 6.34-1.27 7.75Z">
                            </path>
                            <path
                                d="M12 6.35a5.65 5.65 0 1 0 .001 11.301A5.65 5.65 0 0 0 12 6.35Zm0 9.32c-2.02 0-3.67-1.64-3.67-3.67 0-2.03 1.64-3.67 3.67-3.67 2.03 0 3.67 1.64 3.67 3.67 0 2.02-1.65 3.67-3.67 3.67ZM17.87 4.81c-.73 0-1.32.59-1.32 1.32 0 .73.59 1.32 1.32 1.32.73 0 1.32-.59 1.32-1.32 0-.73-.59-1.32-1.32-1.32Z">
                            </path>
                        </g>
                    </svg>
                </div>
                <div class="address">
                    <div>English</div>
                    <div>San Francisco Bay Area</div>
                </div>
            </div>
        </div>
    </div>

    <script src="js/contact_us_form.js"></script>
</body>

</html>