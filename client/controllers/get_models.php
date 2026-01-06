<?php
require_once __DIR__ . '/../php/config.php';

$condition = $_GET['condition'] ?? '';
$make_id   = $_GET['make_id'] ?? 0;

$cond_id = ($condition === '2' || $condition === 2 || $condition === 'preowned')
    ? 2
    : 1;

$stmt = $pdo->prepare("
    SELECT c.id, c.model
    FROM cars c
    WHERE c.condition_id = ? AND c.make_id = ?
    ORDER BY c.model
");
$stmt->execute([$cond_id, $make_id]);

echo '<option value="">-- Choisir un modèle --</option>';
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo "<option value='{$row['id']}'>" . htmlspecialchars($row['model']) . "</option>";
}
