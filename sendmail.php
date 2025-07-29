<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $to = "civara4u@civara.us"; // Replace with your actual email
    $subject = isset($_POST['newsletter']) ? "New Newsletter Signup" : "New Contact Form Submission";

    $email = strip_tags($_POST["email"]);
    $name = isset($_POST["name"]) ? strip_tags($_POST["name"]) : "N/A";
    $message = isset($_POST["message"]) ? strip_tags($_POST["message"]) : "N/A";

    $body = "Name: $name\nEmail: $email\nMessage:\n$message";
    $headers = "From: $email";

    if (mail($to, $subject, $body, $headers)) {
        echo "Message sent successfully.";
    } else {
        echo "Error sending message.";
    }
}
?>
