<?php
// --- SECURITY CHECKS --- //
if ($_SERVER["REQUEST_METHOD"] == "POST") {

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
$phoneSafe   = htmlspecialchars($phoneNumber, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
$messageSafe = htmlspecialchars($message, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');

// Prevent email header injection
// Prevent header injection
foreach ([$name, $email, $phoneNumber] as $value) {
    if (preg_match("/[\r\n]/", $value)) {
        exit("Invalid input detected.");
    }
}

// --- EMAIL SETTINGS --- //
$to = "office@eisberg-gelato.ro";  
$subject = "Mail nou de contact de pe website";

$body = "Ai primit un mail nou de contact de pe website:\n\n";
$body .= "Nume: $nameSafe\n";
$body .= "Email: $email\n";
$body .= "Telefon: $phoneSafe\n\n";
$body .= "Mesaj:\n$messageSafe\n\n";
$body .= "----\nIP: " . $_SERVER['REMOTE_ADDR'];

$headers = "From: Formular contact - eisberg-gelato \r\n";
$headers .= "Reply-To: $email\r\n";
$headers .= "Content-Type: text/plain; charset=utf-8\r\n";

// --- SEND EMAIL --- //
 if (mail($to, $subject, $body, $headers)) {
        header("Location: " . $_SERVER["HTTP_REFERER"] . "?success=1"); // redirect back
        exit;
    } else {
        http_response_code(500);
        echo "Ceva nu a funcționat. Vă rugăm încercați din nou.";
        exit;
    }
} else {
    http_response_code(403);
    echo "Invalid request.";
}
?>
