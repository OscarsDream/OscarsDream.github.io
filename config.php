<?php
// config.php — keep outside public access if possible

$SMTP_USER = 'civara4u@civara.us';

// Encrypted SMTP password (base64-encoded)
$ENCRYPTED_SMTP_PASS = 'REPLACE_WITH_ENCRYPTED_VALUE';

// Secret key from environment variable
$SECRET_KEY = getenv('SMTP_SECRET_KEY');
if (!$SECRET_KEY) {
    die('SMTP secret key not set');
}

$iv = substr(hash('sha256', $SECRET_KEY), 0, 16);
$SMTP_PASS = openssl_decrypt(
    base64_decode($ENCRYPTED_SMTP_PASS),
    'AES-256-CBC',
    $SECRET_KEY,
    OPENSSL_RAW_DATA,
    $iv
);
