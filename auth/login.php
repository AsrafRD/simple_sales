<?php
session_start();
include '../config.php'; // Pastikan path ke config.php benar
include '../functions.php'; // Pastikan path ke functions.php benar

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        $error = 'Username and password are required.';
    } elseif (login($username, $password)) {
        if ($_SESSION['role'] == 'admin') {
            header('Location: ../admin/index.php');
        } elseif ($_SESSION['role'] == 'customer') {
            header('Location: ../index.php');
        }
        exit();
    } else {
        $error = 'Invalid username or password.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/styles.css"> <!-- Link ke file styles.css -->
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4 text-center">Login</h1>
        <?php if (isset($error)): ?>
            <div class="alert alert-danger" role="alert">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>
        <form method="POST" action="login.php">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Login</button>
            <p class="text-center mt-3">Don't have an account? <a href="register.php">Register here</a>.</p>
        </form>
    </div>
</body>
</html>

