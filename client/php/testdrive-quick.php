<?php
require_once __DIR__ . '/config.php';

// Must have car_id in URL
if (!isset($_GET['car_id']) || !is_numeric($_GET['car_id'])) {
    header('Location: index.php');
    exit;
}

$car_id = (int)$_GET['car_id'];

// Fetch car details with all joins
$stmt = $pdo->prepare("
    SELECT 
        c.*,
        m.name AS make_name,
        cond.name AS condition_name,
        f.name AS fuel_name,
        t.name AS transmission_name
    FROM cars c
    JOIN car_make m ON c.make_id = m.id
    JOIN car_condition cond ON c.condition_id = cond.id
    JOIN car_fuel f ON c.fuel_id = f.id
    JOIN car_transmission t ON c.transmission_id = t.id
    WHERE c.id = ?
    LIMIT 1
");
$stmt->execute([$car_id]);
$car = $stmt->fetch();

// If car not found, redirect
if (!$car) {
    header('Location: index.php');
    exit;
}

// Time slots (same as regular testdrive)
$time_slots = [
    'Semaine: Matin'       => 'Semaine: Matin',
    'Semaine: Après-midi'  => 'Semaine: Après-midi',
    'Samedi: Matin'        => 'Samedi: Matin',
];
?>
