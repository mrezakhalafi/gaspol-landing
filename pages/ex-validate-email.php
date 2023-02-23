<?php

ini_set('display_errors', 1);

// CARA 1

require_once ("../gmail/vendor/autoload.php");

$mail = new PHPMailer();
$mail->isSMTP();
$mail->SMTPAuth = true;
$mail->SMTPSecure = 'ssl';
$mail->Host = 'smtp.gmail.com';
$mail->Port = '465';
$mail->isHTML();
$mail->Username = 'diokhususgame@gmail.com';
$mail->Password = 'assalamualaikum';
$mail->SetFrom('no-reply@howcode.org');
$mail->Subject = 'Email Validation';
$mail->Body = 'Hi, Dio. This is your email validation. Please check!';
$mail->AddAddress('diowijaya2014@gmail.com');

$mail->Send();

// END

// CARA 2

// The message
// $message = "Hi, how are you doing?";

// mail('diowijaya2014@gmail.com', 'Email Subject', $message);

// use PHPMailer\PHPMailer\PHPMailer;
// use PHPMailer\PHPMailer\SMTP;
// use PHPMailer\PHPMailer\Exception;

// require_once __DIR__ . 'PHPMailer/src/Exception.php';
// require_once __DIR__ . 'PHPMailer/src/PHPMailer.php';
// require_once __DIR__ . 'PHPMailer/src/SMTP.php';

// passing true in constructor enables exceptions in PHPMailer
// $mail = new PHPMailer(true);

// try {
    // Server settings
    // $mail->SMTPDebug = SMTP::DEBUG_SERVER; // for detailed debug output
    // $mail->isSMTP();
    // $mail->Host = 'smtp.gmail.com';
    // $mail->SMTPAuth = true;
    // $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    // $mail->Port = 587;

    // $mail->Username = 'diokhususgame@gmail.com'; // YOUR gmail email
    // $mail->Password = 'assalamualaikum'; // YOUR gmail password

    // Sender and recipient settings
    // $mail->setFrom('diokhususgame@gmail.com');
    // $mail->addAddress('diowijaya2014@gmail.com');
    // $mail->addReplyTo('diokhususgame@gmail.com'); // to set the reply to

    // Setting the email content
//     $mail->IsHTML(true);
//     $mail->Subject = "Send email using Gmail SMTP and PHPMailer";
//     $mail->Body = 'HTML message body. <b>Gmail</b> SMTP email body.';
//     $mail->AltBody = 'Plain text message body for non-HTML email client. Gmail SMTP email body.';

//     $mail->send();
//     echo "Email message sent.";
// } catch (Exception $e) {
//     echo "Error in sending email. Mailer Error: {$mail->ErrorInfo}";
// }

// END

?>