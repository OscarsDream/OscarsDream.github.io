<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Ensure Composer autoloader is found
if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    require 'vendor/autoload.php';
} else {
    die("Error: vendor/autoload.php not found. Run 'composer install' if needed.");
}

// Get the token from environment variable
$smtpToken = getenv('PROTON_SMTP_TOKEN');

if (empty($smtpToken)) {
    die("Error: PROTON_SMTP_TOKEN environment variable is not set.<br>Run: export PROTON_SMTP_TOKEN='your_token_here'");
}

$mail = new PHPMailer(true);
try {
    // Server settings
    $mail->isSMTP();
    $mail->Host       = 'smtp.protonmail.ch';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'civara4u@civara.us';
    $mail->Password   = $smtpToken; // Using the token directly
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;

    // Recipients
    $mail->setFrom('civara4u@civara.us', 'Civara Test');
    $mail->addAddress('civaraus@yahoo.com'); // Destination

    // Content
    $mail->Subject = 'Local Test: Proton SMTP Token';
    $mail->Body    = 'This is a test email sent from your local machine using the Proton SMTP submission token.';
    $mail->AltBody = 'This is a plain-text version of the email.';

    $mail->send();
    echo '<h2 style="color:green;">Success!</h2>';
    echo '<p>Message sent successfully using the Proton SMTP token.</p>';
    echo '<p><small>Check your inbox at civaraus@yahoo.com</small></p>';
} catch (Exception $e) {
    echo '<h2 style="color:red;">Mailer Error</h2>';
    echo "<p>Error: {$mail->ErrorInfo}</p>";
    echo '<p><strong>Debug Tip:</strong> Ensure your firewall allows outbound connections on port 587 and the token is correct.</p>';
}
?>
