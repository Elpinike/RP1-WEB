<?php
require_once __DIR__ . '/config.php';

// 1. Set customer ID
$customer_id = $_SESSION['customer_id'] ?? null;

// 2. Fetch car conditions
$stmt = $pdo->query("SELECT id, name FROM car_condition");
$conditions = $stmt->fetchAll();

// 3. Fetch car makes
$stmt = $pdo->query("SELECT id, name FROM car_make");
$makes = $stmt->fetchAll();

// 4. Time slots
$time_slots = [
  'Semaine: Matin'       => 'Semaine: Matin',
  'Semaine: Après-midi'  => 'Semaine: Après-midi',
  'Samedi: Matin'        => 'Samedi: Matin',
];
?>