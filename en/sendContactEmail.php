<?php
// --- SECURITY CHECKS --- //
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    exit("Method Not Allowed");
}

// Collect and trim input
$name    = trim($_POST["name"] ?? '');
$email   = trim($_POST["email"] ?? '');
$phoneNumber = trim($_POST["phoneNumber"] ?? '');
$message = trim($_POST["message"] ?? '');

// Validate fields
if ($name === '' || $email === '' || $phoneNumber === '' || $message === '') {
    exit("Please fill in all fields.");
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    exit("Invalid email address.");
}

// Basic sanitization
$nameSafe    = htmlspecialchars($name, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
$phoneSafe   = htmlspecialchars($phone, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
$messageSafe = htmlspecialchars($message, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');

// Prevent email header injection
// Prevent header injection
foreach ([$name, $email, $phone] as $value) {
    if (preg_match("/[\r\n]/", $value)) {
        exit("Invalid input detected.");
    }
}

// --- EMAIL SETTINGS --- //
$to = "office@eisberg-gelato.ro";  // ← CHANGE THIS to your email
$subject = "Mail nou de contact de pe website";

$body = "Ai primit un mail nou de contact de pe website:\n\n";
$body .= "Nume: $nameSafe\n";
$body .= "Email: $email\n";
$body .= "Telefon: $phoneSafe\n\n";
$body .= "Mesaj:\n$messageSafe\n\n";
$body .= "----\nIP: " . $_SERVER['REMOTE_ADDR'];

$headers = "From: no-reply@example.com\r\n";
$headers .= "Reply-To: $email\r\n";
$headers .= "Content-Type: text/plain; charset=utf-8\r\n";

// --- SEND EMAIL --- //
$success = mail($to, $subject, $body, $headers);

if ($success) {
    echo "Thank you, your message has been sent.";
} else {
    echo "Sorry, something went wrong. Please try again later.";
}
?>
