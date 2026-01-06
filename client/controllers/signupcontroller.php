<?php
require_once __DIR__ . '/../php/config.php';

// -------------- Grab POST data -----------------
$first = trim($_POST['first_name'] ?? '');
$last  = trim($_POST['last_name'] ?? '');
$email = trim($_POST['email'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$pass  = $_POST['password'] ?? '';
$pass2 = $_POST['password_confirm'] ?? '';

$errors = [];

// -------------- Validate Names -----------------
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

// -------------- Validate Email -----------------
if ($email === '') {
    $errors[] = 'L\'e-mail est requis.';
} elseif (strlen($email) > 100) {
    $errors[] = 'L\'e-mail ne peut pas dépasser 100 caractères.';
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Adresse e-mail invalide.';
}

// -------------- Validate Phone (optional) ------
if ($phone !== '') {
    if (strlen($phone) > 20) {
        $errors[] = 'Le numéro de téléphone ne peut pas dépasser 20 caractères.';
    } elseif (!preg_match('/^[0-9+\s\-]{7,20}$/', $phone)) {
        $errors[] = 'Format de téléphone invalide.';
    }
}

// -------------- Validate Password --------------
if ($pass === '') {
    $errors[] = 'Le mot de passe est requis.';
} elseif (strlen($pass) < 8) {
    $errors[] = 'Le mot de passe doit contenir au moins 8 caractères.';
} elseif (strlen($pass) > 255) {
    $errors[] = 'Le mot de passe est trop long.';
} elseif (!preg_match('/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}$/', $pass)) {
    $errors[] = 'Le mot de passe doit contenir une majuscule, une minuscule et un chiffre.';
}

if ($pass !== $pass2) {
    $errors[] = 'Les mots de passe ne correspondent pas.';
}

// -------------- Check duplicate email ----------
if (!$errors) {
    $stmt = $pdo->prepare('SELECT id FROM customers WHERE email = ? LIMIT 1');
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        $errors[] = 'Cette adresse e-mail est déjà utilisée.';
    }
}

// -------------- If errors, bounce back ---------
if ($errors) {
    $_SESSION['signup_errors'] = $errors;
    $_SESSION['signup_old'] = [
        'first_name' => $first,
        'last_name' => $last,
        'email' => $email,
        'phone' => $phone
    ];
    header('Location: ../signup.php');
    exit;
}

// -------------- Insert -------------------------
$hash = password_hash($pass, PASSWORD_BCRYPT);

$stmt = $pdo->prepare(
    'INSERT INTO customers (name, surname, email, phone, password, status)
     VALUES (?, ?, ?, ?, ?, ?)'
);
$stmt->execute([$first, $last, $email, $phone, $hash, 'actif']);

// Generate customer ID (e.g., SCU001)
$id = $pdo->lastInsertId();
$customerId = 'SCU' . str_pad($id, 3, '0', STR_PAD_LEFT);
$pdo->prepare('UPDATE customers SET customer_id = ? WHERE id = ?')
    ->execute([$customerId, $id]);

// -------------- Success → redirect -------------
$_SESSION['flash_success'] = 'Inscription réussie ! Vous pouvez maintenant vous connecter.';
header('Location: ../success.php?type=signup');
exit;
?>
