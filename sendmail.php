<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require 'config.php';

$name = $_POST['name'] ?? '';
$email = $_POST['email'] ?? '';
$message = $_POST['message'] ?? '';

$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host = 'mail.protonmail.ch';
    $mail->SMTPAuth = true;
    $mail->Username = $SMTP_USER;
    $mail->Password = $SMTP_PASS;
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    $mail->setFrom('civara4u@civara.us', 'Civara Contact');
    $mail->addAddress('civara4u@civara.us');

    if ($email) {
        $mail->addReplyTo($email, $name ?: $email);
    }

    $mail->Subject = 'New Civara.us Contact';
    $mail->Body = "Name: $name\nEmail: $email\n\n$message";

    $mail->send();
    echo 'OK';
} catch (Exception $e) {
    echo 'Mailer Error';
}
