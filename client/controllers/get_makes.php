<?php
require_once __DIR__ . '/../php/config.php';

$condition = $_GET['condition'] ?? '';

// Convert condition to numeric condition_id
// 1 = Neuve, 2 = Occasion
$cond_id = ($condition === '2' || $condition === 2 || $condition === 'preowned')
    ? 2
    : 1;

$stmt = $pdo->prepare("
    SELECT DISTINCT m.id, m.name
    FROM cars c
    JOIN car_make m ON c.make_id = m.id
    WHERE c.condition_id = ?
    ORDER BY m.name
");
$stmt->execute([$cond_id]);

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo "<option value='{$row['id']}'>" . htmlspecialchars($row['name']) . "</option>";
}
