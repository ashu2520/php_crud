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
$subject = "";
$body = "";
$subject_error = false;
$body_error = false;
function clean_input($fields)
{
    $fields = trim($fields);
    $fields = stripslashes($fields);
    $fields = htmlspecialchars($fields);
    //   $fields = str_replace("'", "", $fields);
    return $fields;
}
if (isset($_POST["submit"])) {
    $subject = clean_input($_POST['subject']);
    $body = $_POST['editor'];
    $temp_slug = $_SESSION["temp_slug"];
    unset($_SESSION['temp_slug']);

    $subject_error = isset($subject) && $subject == "" ? true : false;
    $body_error = isset($body) && $body == "" ? true : false;

    if (!$subject_error && !$body_error) {
        echo $body;
        // Assuming $conn is your MySQL database connection object
        $sql = "UPDATE `email_templates` SET temp_subject = ?, temp_content = ?, temp_updated_at = CURRENT_TIMESTAMP WHERE temp_slug = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $subject, $body, $temp_slug);
        $stmt->execute();
        $result = mysqli_stmt_execute($stmt);

        if ($result) {
            $_SESSION['flash_message'] = "Sucessfully Updated";
            //echo "Updated Successfully";
            header("location:email_temp.php");
        } else {
            die(mysqli_error($conn));
        }
    }
}
?>
<?php
// $temp_slug = "";
if (isset($_GET["temp_slug"])) {
    $temp_slug = $_GET["temp_slug"];
    $_SESSION['temp_slug'] = $temp_slug;
    $sql = "Select * from `email_templates` WHERE temp_slug = '$temp_slug'";
    $result = mysqli_query($conn, $sql);
    if ($result) {
        $row = mysqli_fetch_array($result);
        $subject = $row['temp_subject'];
        $body = $row['temp_content'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Email Templates</title>
    <link rel="icon" type="image/x-icon" href="images/arcs_logo.png">

    <!-- Bootstrap -->
    <link href="css/client_dashboard.css" rel="stylesheet">
    <!-- <link href="css/style.css" rel="stylesheet"> -->

    <Style>
        .email_subject {
            display: flex;
            /* justify-content: space-around; */
            position: relative;
        }

        .email_subject label {
            position: absolute;
            margin-top: 5px;
            /* padding-top: 10px; */
        }

        .email_subject input {
            width: 632px;
            position: absolute;
            padding-left: 5px;
            margin-left: 70px;
            height: 35px;
            transition: border-color 0.15s ease-in-out 0s, box-shadow 0.15s ease-in-out 0s;
            color: #555555;
            border-radius: 5px;
            border-style: groove;
            border-width: 1px;
            font-size: 14px;
        }

        .email_body {
            margin-top: 50px;
            float: left;
            width: fit-content;
            /* border: 1px solid red; */
        }

        .ck.ck-content {
            width: 715px;
            height: 270px;
        }

        #main {
            margin-top: 10px;
        }

        #main input {

            margin-top: 10px;
            padding: 8px 30px;
            font-size: 16px
        }
    </Style>
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
                <h1>Edit Email Template</h1>
                <div class="list-contet">
                    <form action="edit_templates.php" onsubmit="return validateForm()" method="POST">
                        <div class="email_subject">
                            <div>
                                <label>Subject: <span></span></label>
                            </div>
                            <div>
                                <input id="subject_input" type="text" name="subject" class=""
                                    placeholder=" Email Subject" value="<?php echo $subject ?>">
                            </div>
                            <span style="margin: 40px 0px 0px 70px; position: absolute;" class='text_error'
                                id="subject_error"></span>
                        </div>
                        <div class="email_body">
                            <div>
                                <label>Body: <span></span></label>
                            </div>
                            <div id="main">
                                <textarea name="editor" id="editor"><?php echo $body ?></textarea>
                                <input name="submit" type="submit" class="submit-btn" value="Save">
                                <span class='text_error' id="message_error"></span>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- <script src="js/client_update.js"></script> -->
    <script src="https://cdn.ckeditor.com/ckeditor5/41.1.0/classic/ckeditor.js"></script>

    <script>
        let editorData = '';

        ClassicEditor
            .create(document.querySelector('#editor'), {
                allowedContent: true,
                autoParagraph: false, // Disable automatic paragraph creation
                // enterMode: CKEDITOR.ENTER_BR // Use <br> for line breaks instead of <p>
                disallowedContent: [], // Disable disallowed content
                extraAllowedContent: '*', // Allow all elements and attributes
                fullPage: true // 
            })
            .then(editor => {
                editorData = editor;
            })
            .catch(error => {
                console.error(error);
            });

        function validateForm() {
            console.log('validateSubject:', validateSubject());
            console.log('validateMessage:', validateMessage());

            if (!validateMessage() || !validateSubject()) {
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
        function validateSubject() {
            let subject = document.getElementById("subject_input").value.trim();
            var subjectRegex = /^(?!(\S{46,}\s*))(?=(\S+\s*){1,64}$).+$/;
            if (!subjectRegex.test(subject)) {
                document.getElementById("subject_error").innerHTML = "Subject cannot be empty."
                return false;
            } else {
                document.getElementById("subject_error").innerHTML = "";
                return true;
            }
        }
    </script>
</body>

</html>