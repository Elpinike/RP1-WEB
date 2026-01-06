<?php
$host = 'mysql-jaelle.alwaysdata.net';  // AlwaysData MySQL host
$db   = 'jaelle_supercar';              // Your full database name (login + _ + db)
$user = 'jaelle';                       // Your AlwaysData SQL username
$pass = 'J@e!!e1988';                 // Your AlwaysData SQL password
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
