<?php
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1. Grab & trim
    $first   = trim($_POST['first_name'] ?? '');
    $last    = trim($_POST['last_name']  ?? '');
    $email   = trim($_POST['email']      ?? '');
    $phone   = trim($_POST['phone']      ?? '');
    $pass    = $_POST['password']        ?? '';
    $pass2   = $_POST['password_confirm']?? '';

    // 2. Validation
    if ($first === '')             $errors[] = 'First name is required.';
    if ($last === '')              $errors[] = 'Surname is required.';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL))
                                   $errors[] = 'Invalid e-mail address.';
    if ($pass !== $pass2)          $errors[] = 'Passwords do not match.';
    if (!preg_match('/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}$/', $pass))
                                   $errors[] = 'Password is too weak.';

    // 3. Duplicate e-mail?
    if (!$errors) {
        $stmt = $pdo->prepare('SELECT id FROM customers WHERE email = ? LIMIT 1');
        $stmt->execute([$email]);
        if ($stmt->fetch()) $errors[] = 'E-mail already in use.';
    }

    // 4. Insert
    if (!$errors) {
        $hash = password_hash($pass, PASSWORD_BCRYPT);

        // status defaults to 'pending' per your ENUM, so omit it
        $stmt = $pdo->prepare(
          'INSERT INTO customers (name, surname, email, phone, password)
           VALUES (?, ?, ?, ?, ?)'
        );
        $stmt->execute([$first, $last, $email, $phone, $hash]);

        // 5. Generate CLT### and update
        $id  = $pdo->lastInsertId();                          // auto PK
        $clt = 'CLT' . str_pad($id, 3, '0', STR_PAD_LEFT);    // CLT001
        $pdo->prepare('UPDATE customers SET client_id = ? WHERE id = ?')
            ->execute([$clt, $id]);

        // 6. Redirect or flash a success
        $_SESSION['flash'] = 'Great success!';
        header('Location: login.php');
        exit;
    }
}
?>
