<?php
// ADD THESE TWO LINES FOR DEBUGGING
error_reporting(E_ALL);
ini_set('display_errors', 1);

require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Helper function to output a message and redirect
function showMessageAndRedirect($type, $message) {
    $bgColor = ($type === 'success') ? '#d4f3ef' : '#ffcccc';
    $textColor = ($type === 'success') ? '#333' : '#cc0000';
    $redirectUrl = 'index.html'; // Redirects to the home page
    
    // Output HTML with meta refresh
    //        <meta http-equiv='refresh' content='8;url=$redirectUrl'>
    echo "<div style='background:$bgColor;color:$textColor;padding:20px;text-align:center;font-family:sans-serif;'>
            <h2>" . ($type === 'success' ? 'Success!' : 'Error') . "</h2>
            <p>$message</p>
            <p>You will be redirected to the home (index.html) page shortly…</p>
            <meta http-equiv='refresh' content='8;url=index.html'>
         </div>";
    exit;
}

// 1. Check if form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // 2. Read the email (and other fields)
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $message = trim($_POST['message'] ?? '');
    $isNewsletter = isset($_POST['newsletter']);

    // 3. Basic Validation
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        showMessageAndRedirect('error', "Error: Please provide a valid email address.");
    }

    // 4. Get SMTP Token
    $smtpToken = getenv('PROTON_SMTP_TOKEN');
    if (empty($smtpToken)) {
        showMessageAndRedirect('error', "Server Error: SMTP token missing.");
    }

    $mail = new PHPMailer(true);
    try {
        // ... (SMTP settings) ...
        $mail->isSMTP();
        $mail->Host = 'smtp.protonmail.ch';
        $mail->SMTPAuth = true;
        $mail->Username = 'civara4u@civara.us';
        $mail->Password = $smtpToken;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('civara4u@civara.us', 'Civara Website');

        if ($isNewsletter) {
            // Logic for Newsletter
            $mail->addAddress('civara4u@civara.us'); 
            $mail->Subject = "New Newsletter Subscriber: $email";
            $mail->Body = "A new user subscribed with email: $email";
        } else {
            // Logic for Contact Form
            $mail->addAddress('civara4u@civara.us');
            $mail->Subject = "New Contact Message from $name";
            $mail->Body = "Name: $name\nEmail: $email\n\nMessage:\n$message";
        }

        $mail->send();
        
        // SUCCESS: Display the specific message and redirect
        showMessageAndRedirect('success', "Thanks reaching out to Civara.us.\nHave a safe and healthy day!!!");
        
    } catch (Exception $e) {
        // Log the error for admin debugging
        error_log("Mailer Error: {$mail->ErrorInfo}");
        
        // Display generic error to user (avoid leaking sensitive info)
        showMessageAndRedirect('error', "Failed to send message. Please try again later.");
    }
} else {
    // If someone tries to access sendmail.php directly without POST
    header("Location: index.html");
    exit;
}
?>
