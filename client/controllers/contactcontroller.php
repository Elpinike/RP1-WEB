<?php
// contactcontroller.php
require_once __DIR__ . '/../php/config.php';

// 1. Grab POST data safely
$first   = trim($_POST['first_name'] ?? '');
$last    = trim($_POST['last_name'] ?? '');
$email   = trim($_POST['email'] ?? '');
$phone   = trim($_POST['phone'] ?? '');
$subject = trim($_POST['subject'] ?? '');
$message = trim($_POST['message'] ?? '');
$customer_id = $_SESSION['customer_id'] ?? null;

$errors = [];

// 2. Validate fields

// Names
if ($first === '') {
    $errors[] = 'Le prénom est requis.';
} elseif (strlen($first) > 50) {
    $errors[] = 'Le prénom ne peut pas dépasser 50 caractères.';
}

if ($last === '') {
    $errors[] = 'Le nom est requis.';
} elseif (strlen($last) > 50) {
    $errors[] = 'Le nom ne peut pas dépasser 50 caractères.';
}

// Email
if ($email === '') {
    $errors[] = 'L\'e-mail est requis.';
} elseif (strlen($email) > 100) {
    $errors[] = 'L\'e-mail ne peut pas dépasser 100 caractères.';
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Adresse e-mail invalide.';
}

// Phone (optional)
if ($phone !== '') {
    if (strlen($phone) > 20) {
        $errors[] = 'Le numéro de téléphone ne peut pas dépasser 20 caractères.';
    } elseif (!preg_match('/^[0-9+\s\-]{7,20}$/', $phone)) {
        $errors[] = 'Format de téléphone invalide.';
    }
}

// Message
if ($message === '') {
    $errors[] = 'Le message est requis.';
} elseif (strlen($message) > 2000) {
    $errors[] = 'Le message ne peut pas dépasser 2000 caractères.';
}

// 3. If errors, redirect back
if ($errors) {
    $_SESSION['contact_errors'] = $errors;
    $_SESSION['contact_old'] = [
        'first_name' => $first,
        'last_name' => $last,
        'email' => $email,
        'phone' => $phone,
        'message' => $message
    ];
    header('Location: ../contact.php');
    exit;
}

// 4. Insert into database
$stmt = $pdo->prepare("
    INSERT INTO messages (customer_id, name, surname, email, phone, message)
    VALUES (?, ?, ?, ?, ?, ?)
");
$stmt->execute([$customer_id, $first, $last, $email, $phone, $message]);

// 5. Redirect to success
$_SESSION['flash_success'] = 'Message envoyé avec succès !';
header('Location: ../success.php?type=contact');
exit;
?>
