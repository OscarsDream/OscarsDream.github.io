<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

// 1. Check if form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // 2. Read the email (and other fields)
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $message = trim($_POST['message'] ?? '');
    $isNewsletter = isset($_POST['newsletter']);

    // 3. Basic Validation
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // In a real app, you might redirect back with an error message
        die("Error: Please provide a valid email address.");
    }

    // 4. Get SMTP Token
    $smtpToken = getenv('PROTON_SMTP_TOKEN');
    if (empty($smtpToken)) {
        die("Server Error: SMTP token missing.");
    }

    $mail = new PHPMailer(true);
    try {
        // ... (SMTP settings from previous code) ...
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
            $mail->addAddress('civara4u@civara.us'); // Send notification to admin
            $mail->Subject = "New Newsletter Subscriber: $email";
            $mail->Body = "A new user subscribed with email: $email";
            
            // Optional: Also send a confirmation to the user
            // $mail->addReplyTo($email, $name);
            // $mail->addAddress($email);
            // $mail->Subject = "Welcome to Civara Newsletter";
            // $mail->Body = "Thank you for subscribing!";
        } else {
            // Logic for Contact Form
            $mail->addAddress('civara4u@civara.us');
            $mail->Subject = "New Contact Message from $name";
            $mail->Body = "Name: $name\nEmail: $email\n\nMessage:\n$message";
        }

        $mail->send();
        
        // Success! The JavaScript in index.html will handle the redirect.
        // We don't output anything here to avoid breaking the JS redirect.
        
    } catch (Exception $e) {
        error_log("Mailer Error: {$mail->ErrorInfo}");
        // Optionally redirect to an error page or return a JSON error if using AJAX
        die("Failed to send message.");
    }
} else {
    // If someone tries to access sendmail.php directly without POST
    header("Location: index.html");
    exit;
}
?>
