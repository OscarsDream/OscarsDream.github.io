<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Ensure Composer autoloader is found
if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    require 'vendor/autoload.php';
} else {
    die("<div style='background:#ffcccc;color:#cc0000;padding:20px;text-align:center;font-family:sans-serif;'>
            <h2>Error: vendor/autoload.php not found.</h2>
            <p>Run 'composer install' if needed.</p>
            <p>Redirecting to home page in 8 seconds...</p>
            <meta http-equiv='refresh' content='8;url=index.html'>
         </div>");
}

// Get the token from environment variable
$smtpToken = getenv('PROTON_SMTP_TOKEN');

if (empty($smtpToken)) {
    die("<div style='background:#ffcccc;color:#cc0000;padding:20px;text-align:center;font-family:sans-serif;'>
            <h2>Error: PROTON_SMTP_TOKEN environment variable is not set.</h2>
            <p>Run: export PROTON_SMTP_TOKEN='your_token_here'</p>
            <p>Redirecting to home page in 8 seconds...</p>
            <meta http-equiv='refresh' content='8;url=index.html'>
         </div>");
}

$mail = new PHPMailer(true);
try {
    // Server settings
    $mail->isSMTP();
    $mail->Host       = 'smtp.protonmail.ch';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'civara4u@civara.us';
    $mail->Password   = $smtpToken; 
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;

    // Recipients
    $mail->setFrom('civara4u@civara.us', 'Civara Test');
    $mail->addAddress('civaraus@yahoo.com'); 

    // Content
    $mail->Subject = 'Local Test: Proton SMTP Token';
    $mail->Body    = 'This is a test email sent from your local machine using the Proton SMTP submission token.';
    $mail->AltBody = 'This is a plain-text version of the email.';

    $mail->send();

    // SUCCESS MESSAGE
    echo "<div style='background:#d4f3ef;color:#333;padding:20px;text-align:center;font-family:sans-serif;'>
            <h2>Success!</h2>
            <p>Thanks reaching out to Civara.us.</p>
            <p>Have a safe and healthy day!!!</p>
            <p>You will be redirected to the home page shortly…</p>
            <meta http-equiv='refresh' content='8;url=index.html'>
         </div>";

} catch (Exception $e) {
    // ERROR MESSAGE
    echo "<div style='background:#ffcccc;color:#cc0000;padding:20px;text-align:center;font-family:sans-serif;'>
            <h2>Error</h2>
            <p>Mailer Error: {$mail->ErrorInfo}</p>
            <p>Redirecting to home page in 8 seconds...</p>
            <meta http-equiv='refresh' content='8;url=index.html'>
         </div>";
}
?>
