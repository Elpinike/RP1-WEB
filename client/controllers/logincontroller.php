<?php
require_once __DIR__ . '/../php/config.php';

/* 1. Collect POST data */
$email = trim($_POST['email'] ?? '');
$pass  = $_POST['password'] ?? '';

$errors = [];

/* 2. Basic validation */
if ($email === '') {
    $errors[] = 'L\'e-mail est requis.';
} elseif (strlen($email) > 100) {
    $errors[] = 'E-mail trop long.';
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Format d\'e-mail invalide.';
}

if ($pass === '') {
    $errors[] = 'Le mot de passe est requis.';
} elseif (strlen($pass) > 255) {
    $errors[] = 'Mot de passe trop long.';
}

/* 3. Look the user up by e-mail */
if (!$errors) {
    $stmt = $pdo->prepare(
        'SELECT id, name, password, status
           FROM customers
          WHERE email = ?
          LIMIT 1'
    );
    $stmt->execute([$email]);
    $user = $stmt->fetch();


    if (!$user || !password_verify($pass, $user['password'])) {
        $errors[] = 'E-mail ou mot de passe incorrect.';
    } elseif ($user['status'] !== 'actif') {
        $errors[] = 'Veuillez activer votre compte.';
    }
}

/* 4. On error → bounce back with flashes */
if ($errors) {
    $_SESSION['login_errors'] = $errors;
    $_SESSION['old_email']    = $email;
    header('Location: ../login.php');
    exit;
}

/* 5. Success → set session */
$_SESSION['customer_id']  = $user['id'];
$_SESSION['customer_name'] = $user['name'];
$_SESSION['flash_success'] = 'Bon retour parmi nous !';

/* 6. Check if redirect_after_login exists */
if (!empty($_SESSION['redirect_after_login'])) {
    $redirect = $_SESSION['redirect_after_login'];
    unset($_SESSION['redirect_after_login']);
    header('Location: ../' . ltrim($redirect, '/'));
    exit;
}

/* 7. Otherwise go to success page with countdown */
header('Location: ../success.php?type=login');
exit;
?>
