<?php
require_once __DIR__ . '/../php/config.php';

// Only accept POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../testdrive.php');
    exit;
}

// Check login
if (!isset($_SESSION['customer_id'])) {
    header('Location: ../login.php');
    exit;
}

// Collect POST data
$customer_id    = $_SESSION['customer_id'];
$condition      = $_POST['condition'] ?? '';
$car_id         = $_POST['car_id'] ?? null;
$date           = $_POST['date'] ?? null;
$alternate_date = $_POST['alternate_date'] ?? null;
$time_slot      = $_POST['time_slot'] ?? null;
$message        = trim($_POST['message'] ?? '');

$errors = [];

// Validate condition
if ($condition === '' || !in_array($condition, ['1', '2'])) {
    $errors[] = 'Veuillez sélectionner la condition du véhicule.';
}

// Validate car selection
if (!$car_id || !is_numeric($car_id)) {
    $errors[] = 'Veuillez choisir un modèle.';
} else {
    // Verify car exists
    $stmt = $pdo->prepare('SELECT id FROM cars WHERE id = ? LIMIT 1');
    $stmt->execute([$car_id]);
    if (!$stmt->fetch()) {
        $errors[] = 'Modèle de voiture invalide.';
    }
}

// Validate date
if (!$date) {
    $errors[] = 'Veuillez choisir une date.';
} else {
    $dateObj = DateTime::createFromFormat('Y-m-d', $date);
    $today = new DateTime();
    $today->setTime(0, 0, 0);
    
    if (!$dateObj || $dateObj->format('Y-m-d') !== $date) {
        $errors[] = 'Format de date invalide.';
    } elseif ($dateObj <= $today) {
        $errors[] = 'La date doit être dans le futur.';
    }
}

// Validate alternate date (optional but must be valid if provided)
if ($alternate_date !== '' && $alternate_date !== null) {
    $altDateObj = DateTime::createFromFormat('Y-m-d', $alternate_date);
    $today = new DateTime();
    $today->setTime(0, 0, 0);
    
    if (!$altDateObj || $altDateObj->format('Y-m-d') !== $alternate_date) {
        $errors[] = 'Format de date alternative invalide.';
    } elseif ($altDateObj <= $today) {
        $errors[] = 'La date alternative doit être dans le futur.';
    }
} else {
    $alternate_date = null; // Ensure null for DB
}

// Validate time slot
$valid_slots = ['weekday_morning', 'weekday_afternoon', 'saturday_morning'];
$valid_slots = ['Semaine: Matin', 'Semaine: Après-midi', 'Samedi: Matin'];
if (!$time_slot) {
    $errors[] = 'Veuillez choisir un créneau horaire.';
} elseif (!in_array($time_slot, $valid_slots, true)) {  // Added strict comparison
    $errors[] = 'Créneau horaire invalide.';
}

// Validate message length
if (strlen($message) > 1000) {
    $errors[] = 'Le message ne peut pas dépasser 1000 caractères.';
}

// If errors, redirect back
if ($errors) {
    $_SESSION['testdrive_errors'] = $errors;
    $_SESSION['testdrive_old'] = [
        'condition' => $condition,
        'date' => $date,
        'alternate_date' => $alternate_date,
        'time_slot' => $time_slot,
        'message' => $message
    ];
    header('Location: ../testdrive.php');
    exit;
}

// Generate unique testdrive_id
$stmt = $pdo->query("SELECT COUNT(*) FROM testdrive");
$count = $stmt->fetchColumn() + 1;
$testdrive_id = 'TD' . str_pad($count, 3, '0', STR_PAD_LEFT);

// Insert into database
$stmt = $pdo->prepare("
    INSERT INTO testdrive
    (testdrive_id, customer_id, car_id, date, alternate_date, time_slot, message, status, sent_at)
    VALUES (?, ?, ?, ?, ?, ?, ?, 'pending', NOW())
");
$stmt->execute([$testdrive_id, $customer_id, $car_id, $date, $alternate_date, $time_slot, $message]);

// Success
$_SESSION['flash_success'] = "Votre demande d'essai a été envoyée avec succès.";
header('Location: ../success.php?type=testdrive');
exit;
?>
