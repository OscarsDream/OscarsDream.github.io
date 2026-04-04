<?php
// config.php — keep outside public access if possible

$SMTP_USER = 'your_username';

// Encrypted SMTP password (base64-encoded)
$ENCRYPTED_SMTP_PASS = 'REPLACE_WITH_ENCRYPTED_VALUE';

/**
 * CONFIGURATION TEMPLATE
 * 
 * 1. Copy this file to config.php
 * 2. Set your environment variables (SMTP_SECRET_KEY)
 * 3. Update the values below in config.php
 */

// 1. Get the secret key from the environment
$SECRET_KEY = getenv('SMTP_SECRET_KEY');

if (!$SECRET_KEY) {
    // In the real app, this stops execution. 
    // In this example, we just warn the user.
    echo "WARNING: SMTP_SECRET_KEY environment variable is not set.\n";
    echo "Please set it in your .env file or server configuration.\n";
    // die('SMTP secret key not set'); // Uncomment in real config.php
}

// 2. Define the encrypted password placeholder
// In config.php, replace this string with your actual encrypted password
$ENCRYPTED_SMTP_PASS = 'YOUR_BASE64_ENCRYPTED_PASSWORD_HERE';

// 3. Decryption logic (Only runs if SECRET_KEY is set)
if ($SECRET_KEY && $ENCRYPTED_SMTP_PASS !== 'YOUR_BASE64_ENCRYPTED_PASSWORD_HERE') {
    $iv = substr(hash('sha256', $SECRET_KEY), 0, 16);
    $SMTP_PASS = openssl_decrypt(
        base64_decode($ENCRYPTED_SMTP_PASS),
        'AES-256-CBC',
        $SECRET_KEY,
        OPENSSL_RAW_DATA,
        $iv
    );
} else {
    $SMTP_PASS = '';
}

