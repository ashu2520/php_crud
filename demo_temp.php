<?PHP include "connect.php";?>
<?php

    $sql = "Select * from `email_templates` WHERE temp_slug = 'change_password'";
    $result = mysqli_query($conn, $sql);
    if ($result) {
        $row = mysqli_fetch_array($result);
        $subject = $row['temp_subject'];
        $body = $row['temp_content'];
    }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <div id="main">
        <textarea name="editor" id="editor"><?php echo $body ?></textarea>
        <input type="submit" class="submit-btn" value="Save">
        <span class='text_error' id="message_error"></span>
    </div>
</body>
<script src="https://cdn.ckeditor.com/ckeditor5/41.1.0/classic/ckeditor.js"></script>

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

</script>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Our Platform</title>
</head>
<body>
    <div style="max-width: 600px; margin: 20px auto; padding: 20px; border: 1px solid #ccc; border-radius: 10px; background-color: #f9f9f9; font-family: Arial, sans-serif; font-size: 16px;">
        <h1 style="color: #333; text-align: center;">Welcome to Our Platform!</h1>
        <p style="color: #555; line-height: 1.6; margin-bottom: 10px;">Dear [User Name],</p>
        <p style="color: #555; line-height: 1.6; margin-bottom: 10px;">We are thrilled to welcome you as a new member of our community!</p>
        <p style="color: #555; line-height: 1.6; margin-bottom: 10px;">With our platform, you can [briefly describe the features or benefits].</p>
        <p style="color: #555; line-height: 1.6; margin-bottom: 10px;">We hope you enjoy your experience with us!</p>
        <p style="color: #555; line-height: 1.6; margin-bottom: 10px;">Best regards,<br> [Your Name or Company]</p>
    </div>
</body>
</html>

<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Change Password</title>
</head>
<body style='margin: 0; padding: 0; font-family: Arial, sans-serif; font-size: 16px;'>
    <div class='container' style='max-width: 600px; margin: 20px auto; padding: 20px; border: 1px solid #ccc; border-radius: 10px; background-color: #f9f9f9;'>
        <div class='header' style='text-align: center; margin-bottom: 20px;'>
            <img src='https://media.licdn.com/dms/image/C4D0BAQHfMFESqEXJcA/company-logo_200_200/0/1680076276966/arcsinfotech_logo?e=1719446400&v=beta&t=trYarQS_IF3oI0t1kmCMEEZrFhjRcpa6k3rekC0KRRo' alt='Company Logo' style='max-width: 200px; height: auto;'>
        </div>
        <h1 style='text-align: center; color: #333;'>Password Change Required</h1>
        <p style='color: #555; line-height: 1.6;'>Your password needs to be changed for security reasons. Please click the button below to change your password:</p>
        <p><a href='#' class='btn' style='display: inline-block; padding: 10px 20px; background-color: #ff651b; color: #fff; text-decoration: none; border-radius: 5px;'>Change Password</a></p>
        <p>If you did not request this change, please ignore this email or contact support.</p>
    </div>
</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Team</title>
</head>
<body>
    <div style="max-width: 600px; margin: 20px auto; padding: 20px; border: 1px solid #ccc; border-radius: 10px; background-color: #f9f9f9;">
        <h1 style="text-align: center; color: #333;">Welcome to Our Team!</h1>
        <p style="color: #555; line-height: 1.6;">Hello [User Name],</p>
        <p style="color: #555; line-height: 1.6;">We are excited to inform you that you have been successfully added to our database.</p>
        <p style="color: #555; line-height: 1.6;">As a registered user, you now have access to exclusive features and updates.</p>
        <p style="color: #555; line-height: 1.6;">Thank you for joining us!</p>
        <p style="color: #555; line-height: 1.6;">If you have any questions or concerns, feel free to contact us.</p>
        <p style="color: #555; line-height: 1.6;"><strong>Best regards,</strong><br> [Your Name or Company]</p>
    </div>
</body>
</html>


User name: ashuoff2520@gmail.com
Password: 65995685AE086F1665909202E090032A72B0
port: 2525