<?PHP include "connect.php"; ?>
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
    <title>Welcome to ARCS Infotech</title>
</head>

<body>
    <div
        style="max-width: 600px; margin: 20px auto; padding: 20px; border: 1px solid #ccc; border-radius: 10px; background-color: #f9f9f9; font-family: Arial, sans-serif; font-size: 16px;">
        <h1 style="color: #333; text-align: center;">Welcome to ARCS Infotech!</h1>
        <p style="color: #555; line-height: 1.6; margin-bottom: 10px;">Dear [User Name],</p>
        <p style="color: #555; line-height: 1.6; margin-bottom: 10px;">We are thrilled to welcome you as a new member of
            our community!</p>
        <p style="color: #555; line-height: 1.6; margin-bottom: 10px;">With our platform, you can briefly describe the
            features or benefits.</p>
        <p style="color: #555; line-height: 1.6; margin-bottom: 10px;">We hope you enjoy your experience with us!</p>
        <p style="color: #555; line-height: 1.6; margin-bottom: 10px;">Best regards,<br> ARCS Infotech</p>
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
    <div class='container'
        style='max-width: 600px; margin: 20px auto; padding: 20px; border: 1px solid #ccc; border-radius: 10px; background-color: #f9f9f9;'>
        <div class='header' style='text-align: center; margin-bottom: 20px;'>
            <img src='https://media.licdn.com/dms/image/C4D0BAQHfMFESqEXJcA/company-logo_200_200/0/1680076276966/arcsinfotech_logo?e=1719446400&v=beta&t=trYarQS_IF3oI0t1kmCMEEZrFhjRcpa6k3rekC0KRRo'
                alt='Company Logo' style='max-width: 200px; height: auto;'>
        </div>
        <h1 style='text-align: center; color: #333;'>Password Change Required</h1>
        <p style='color: #555; line-height: 1.6;'>Your password needs to be changed for security reasons. Please click
            the button below to change your password:</p>
        <p><a href='#' class='btn'
                style='display: inline-block; padding: 10px 20px; background-color: #ff651b; color: #fff; text-decoration: none; border-radius: 5px;'>Change
                Password</a></p>
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
    <div
        style="max-width: 600px; margin: 20px auto; padding: 20px; border: 1px solid #ccc; border-radius: 10px; background-color: #f9f9f9;">
        <h1 style="text-align: center; color: #333;">Welcome to ARCS Infotech!</h1>
        <p style="color: #555; line-height: 1.6;">Hello [User Name],</p>
        <p style="color: #555; line-height: 1.6;">We are excited to inform you that you have been successfully added to
            our database.</p>
        <p style="color: #555; line-height: 1.6;">As a registered user, you now have access to exclusive features and
            updates.</p>
        <p style="color: #555; line-height: 1.6;">Thank you for joining us!</p>
        <p style="color: #555; line-height: 1.6;">If you have any questions or concerns, feel free to contact us.</p>
        <p style="color: #555; line-height: 1.6;"><strong>Best regards,</strong><br>ARCS Infotech</p>
    </div>
</body>

</html>

<script>
    function sendEmail() {
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'emialmsgsfile/changepasswordlink.php', true);
        var data = 'link=' + encodeURIComponent('<?php echo $link; ?>') + '&name=' + encodeURIComponent('<?php echo $name; ?>') + '&email=' + encodeURIComponent('<?php echo $email; ?>');

        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        xhr.onreadystatechange = function () {
            if (xhr.readyState == 4 && xhr.status == 200) {
                alert(xhr.responseText); // Display the response
            }
        };
        // alert("hello");
        xhr.send(data);
    }
    sendEmail();
</script>

<script>
    URL = "emp_change_password.php"
    async function sendEmail() {
        try {
            const response = await fetch(URL, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                }
            });

            const data = await response.text();
            // alert(data); // Display the response
        } catch (error) {
            console.error('Error:', error);
        }
    }

    sendEmail();

    URL = "email_validation.php"
    async function isDuplicateEmail(email = '') {
        try {
            const response = await fetch(URL, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: `email=${email}`,
            });

            const data = await response.json();
            // console.log(data);
            return data.exists; // true if email exists, false otherwise
        } catch (error) {
            console.log('Error checking email:', error);
            return false; // Handle error scenario
        }

    }
</script>

&lt;p&gt;&lt;img
src=&quot;https://media.licdn.com/dms/image/C4D0BAQHfMFESqEXJcA/company-logo_200_200/0/1680076276966/arcsinfotech_logo?e=1719446400&amp;amp;v=beta&amp;amp;t=trYarQS_IF3oI0t1kmCMEEZrFhjRcpa6k3rekC0KRRo&quot;
alt=&quot;Company Logo&quot;&gt;&lt;/p&gt;&lt;h2&gt;Password Changed Successfully&lt;/h2&gt;&lt;p&gt;Your password has
been changed Successfully. If it&#039;s not you then please click the button below:&amp;nbsp;&lt;/p&gt;&lt;p&gt;&lt;a
href=&quot;#&quot;&gt;It&#039;s Not Me&lt;/a&gt;&lt;/p&gt;

<body style='margin: 0; padding: 0; font-family: Arial, sans-serif; font-size: 16px;'>
    <div class='container'
        style='max-width: 600px; margin: 20px auto; padding: 20px; border: 1px solid #ccc; border-radius: 10px; background-color: #f9f9f9; text-align: center;'>
        <p><img src='https://media.licdn.com/dms/image/C4D0BAQHfMFESqEXJcA/company-logo_200_200/0/1680076276966/arcsinfotech_logo?e=1719446400&amp;v=beta&amp;t=trYarQS_IF3oI0t1kmCMEEZrFhjRcpa6k3rekC0KRRo'
                alt='Company Logo' style='max-width: 200px; height: auto;'></p>
        <h2 style='color: #333;'>Password Changed Successfully</h2>
        <p style='color: #555; line-height: 1.6;'>Your password has been changed Successfully. If it's not you then
            please click the button below:&nbsp;</p>
        <p><a href='#' style='color: blue; text-decoration: none; font-weight: bold;'>It's Not Me</a></p>
    </div>
</body>
