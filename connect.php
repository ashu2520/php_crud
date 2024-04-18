<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
$conn = new mysqli("localhost", "root", "root", "employee_management");
if (!$conn) {
  die(mysqli_error($conn));
}
session_start();
// $user_role_id = "";
?>
<?php
//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;


function mailer($temp_slug, $email, $name = "", $randomHash = "")
{
  $conn = new mysqli("localhost", "root", "root", "employee_management");
  if ($temp_slug !== "message") {
    $sql_email = "SELECT temp_subject, temp_content FROM email_templates WHERE temp_slug = '$temp_slug'";
    $result_email = mysqli_query($conn, $sql_email);
    $row = mysqli_fetch_array($result_email);

    $subject = $row["temp_subject"];
    $body = $row["temp_content"];

    $body = str_replace("[token_value]", $randomHash, $body);

    $body = str_replace("[User Name]", $name, $body);
  } else {
    $subject = $name;
    $body = $randomHash;
  }


  // Humlog Manually Load karenge...
  require __DIR__ . '/PHPMailer-master/src/Exception.php';
  require __DIR__ . '/PHPMailer-master/src/PHPMailer.php';
  require __DIR__ . '/PHPMailer-master/src/SMTP.php';

  //Create an instance; passing `true` enables exceptions
  $mail = new PHPMailer(true);

  try {
    //Server settings
    $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
    $mail->isSMTP();                                            //Send using SMTP
    $mail->Host = 'smtp.gmail.com';                     //Set the SMTP server to send through
    $mail->SMTPAuth = true;                                   //Enable SMTP authentication
    $mail->Username = 'ashuoff2520@gmail.com';                     //SMTP username
    $mail->Password = 'gpmr xdqo rcfa lzih';                               //SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;            //Enable implicit TLS encryption
    $mail->Port = 587;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

    //Recipients
    $mail->setFrom('ashuoff2520@gmail.com', 'Sender');
    $mail->addAddress($email); //Add a recipient
    $mail->addReplyTo('ashuoff2520@gmail.com', 'Information');

    //Content
    $mail->isHTML(true);                                  //Set email format to HTML
    $mail->Subject = $subject;
    $mail->Body = $body;
    $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

    $mail->send();

  } catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
  }
}
?>