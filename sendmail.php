<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $to = "civara4u@civara.us";
    $subject = isset($_POST['newsletter']) ? "New Newsletter Signup" : "New Contact Form Submission";

    $email = strip_tags($_POST["email"]);
    $name = isset($_POST["name"]) ? strip_tags($_POST["name"]) : "N/A";
    $message = isset($_POST["message"]) ? strip_tags($_POST["message"]) : "N/A";

    $body = "Name: $name\nEmail: $email\nMessage:\n$message";
    $headers = "From: $email";

    mail($to, $subject, $body, $headers);

    // Show confirmation page with redirect
    echo '<!DOCTYPE html>
    <html lang="en">
    <head>
      <meta charset="UTF-8">
      <meta http-equiv="refresh" content="4;URL=https://civara.us#contact">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Message Sent</title>
      <style>
        body {
          font-family: sans-serif;
          background: #fefcbf;
          color: #333;
          text-align: center;
          padding: 5em 2em;
        }
      </style>
    </head>
    <body>
      <h1>Thanks reaching out to Civara.us.</h1>
      <p>Have a safe and healthy day!!!</p>
      <p>You will be redirected shortly...</p>
    </body>
    </html>';
}
?>
