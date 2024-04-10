<?php
include "connect.php";
// Starting the session
if (!isset($_SESSION["user_name"])) {
    header("location:emp_login.php");
}
?>
<?php
// $Name ="";
// $Email ="";
// $Mobile ="";
// $Subject ="";
// $Message ="";
function clean_input($fields)
{
	$fields = trim($fields);
	$fields = stripslashes($fields);
	// $fields = htmlspecialchars($fields);
	// $fields = str_replace("'", "", $fields);
	return $fields;
}
if (isset($_GET['Id'])) {
    $Id = clean_input($_GET['Id']);

    // Check if the ID is valid
    if (!empty($Id) && is_numeric($Id)) {
        // Proceed with the query
        $sql = "SELECT * FROM `contact_request` WHERE contact_id = $Id";
        $result = mysqli_query($conn, $sql);

        if ($result) {
            $row_count = mysqli_num_rows($result);

            if ($row_count > 0) {
                $row = mysqli_fetch_array($result);
                $Name = $row['contact_name'];
                $Email = $row['contact_email'];
                $Mobile = $row['contact_number'];
                $Subject = $row['contact_subject'];
                $Message = $row['contact_message'];
            } else {
                // Handle the case where no data is found
                $_SESSION['flash_message'] = "No data found";
                header('location:client_dashboard.php');
                exit();
            }
        } else {
            // Error in query execution
            $_SESSION['flash_message'] = "Invalid ID of the client";
            header('location:client_dashboard.php');
            exit();
        }
    } else {
        // Invalid ID provided
        $_SESSION['flash_message'] = "Invalid ID provided";
        header('location:client_dashboard.php');
        exit();
    }
}
$is_data = false;
if(isset($_POST["submit"])){
    $data = clean_input($_POST["editor"]);
    if($data !== ""){
        $is_data = true;
    }
}
?>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Message</title>
    <link rel="icon" type="image/x-icon" href="images/arcs_logo.png">

    <!-- Bootstrap -->
    <link href="css/client_dashboard.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>

<body>

    <?php include "header.php"; ?>
    <div class="content">
        <div class="wrapper">
            <div class="bedcram">
            </div>
            <?php include "left_sidebar.php"; ?>
            <div class="right_side_content" style="height: 710px; overflow-y: scroll;">
                <h1>Messages Requested</h1>
                <div class="list-contet">

                    <!-- popup -->
                    <!-- <div class="popup" id="popup">
                        <h2 style="margin-top: 25px;">Thank You</h2>
                        <p>Your details has been Successfully Submuitted. Thanks</p>
                        <button style="margin-right: 15px;" type="button" class="del_btn" onclick="closepopup()">Okay</button>
                    </div> -->
                    <div class="info-contact-us">
                        <div class="contact-us-category">Name: </div>
                        <div>
                            <?php echo $Name ?>
                        </div>
                    </div>
                    <div class="info-contact-us">
                        <div class="contact-us-category">Email: </div>
                        <div>
                            <?php echo $Email ?>
                        </div>
                    </div>
                    <div class="info-contact-us">
                        <div class="contact-us-category">Number: </div>
                        <div>
                            <?php echo $Mobile ?>
                        </div>
                    </div>
                    <div class="info-contact-us">
                        <div class="contact-us-category">Subject: </div>
                        <div>
                            <?php echo $Subject ?>
                        </div>
                    </div>
                    <div class="info-contact-us-message">
                        <div class="contact-us-category-message">Message: </div>
                        <div class="message-data">
                            <?php echo $Message ?>
                        </div>
                    </div>
                </div>
                <div class="message-reply">
                    <p class="head-reply">REPLY</p>
                    <form id="main" action="message_display.php?Id=<?php echo $Id;?>" onsubmit="return validateForm()" method="POST">
                        <textarea name="editor" id="editor"></textarea>
                        <a href="request.php" style="padding: 8px 30px; font-size: 16px" class="submit-btn" value="Send"> Back </a>
                        <input name="submit" type="submit" style="padding: 8px 30px; font-size: 16px" class="submit-btn" value="Send">
                        <span style="margin: -10px; padding:0px;" class='text_error1' id="message_error"></span>
                    </form>

                    <div class="data">
                        <?php 
                            if($is_data){
                                echo '<br><br>'.$data;
                            }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- For Displaying the Message -->
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            var messageData = document.querySelector('.message-data');

            // Check if there's overflow
            if (messageData.scrollHeight > messageData.clientHeight) {
                // There's overflow, don't center the content
                messageData.style.alignItems = 'unset'; // or whatever your default alignment is
            } else {
                // No overflow, center the content
                messageData.style.alignItems = 'center';
            }
        });
    </script>

    <!-- For text Editor -->
    <script src="https://cdn.ckeditor.com/ckeditor5/41.1.0/classic/ckeditor.js"></script>

    <!-- For Sending Mail -->
    <!-- <script src="https://smtpjs.com/v3/smtp.js"></script>
    <script>
        function sendEmail() {
            Email.send({
                Host: "smtp.gmail.com",
                Username: "ashuoff2520@gmail.com",
                Password: "Ashu@2520",
                To: '',
                From: "ashuoff2520@gmail.com",
                Subject: "  ",
                Body: document.getElementById('editor').value
            }).then(
                message => alert(message)
            );
        }
    </script> -->

    <!-- For Validating the message -->
    <script>
        let editorData = '';

        ClassicEditor
            .create(document.querySelector('#editor'))
            .then(editor => {
                editorData = editor;
            })
            .catch(error => {
                console.error(error);
            });

        function validateForm() {
            if (!validateMessage()) {
                return false;
            } else {
                console.log("Editor Data:", editorData.getData());
                alert("Message Send Successfully");
                return true;
            }
        }

        function validateMessage() {
            const message = editorData.getData(); // Get data from editor
            console.log("Message:", message);

            const messageRegex = /^(?!(\S{46,}\s*))(?=(\S+\s*){1,512}$).+$/;
            if (!messageRegex.test(message.trim())) {
                document.getElementById("message_error").innerHTML = "Message cannot be empty."
                return false;
            } else {
                document.getElementById("message_error").innerHTML = "";
                return true;
            }
        }
    </script>

    <!-- For popup -->
    <script>

    </script>
</body>

</html>