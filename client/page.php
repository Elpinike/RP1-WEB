<!DOCTYPE html>
<html lang="en">
<head>
      <link href="flaticon/flaticon.css" rel="stylesheet">
          <link href="css/style.css" rel="stylesheet">
          <link rel="stylesheet" href="../assets/fontawesome/css/all.min.css">
  <style>
    body {
      background-color: #e9b0b0;
    }
    img {
      width: 300px;
      display: block;
      margin: 20px auto;
    }
  </style>
</head>
<body>

<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>

<?php
echo password_hash('admin', PASSWORD_DEFAULT);
?>
<br>

<br>
<i class="fa-solid fa-car"></i>
<i class="fa-brands fa-github"></i>
<i class="fa-duotone fa-star"></i>
<br><br>

<?php
require_once __DIR__ . '/php/config.php';

echo "✅ Connection successful!";
?>

<BR>

<?php
require_once __DIR__ . '/php/dbconnect.php';

$stmt = $pdo->query("SELECT COUNT(*) FROM cars");
$count = $stmt->fetchColumn();

echo "✅ Connected! There are $count cars in the database.";
?>

</body>
</html>
