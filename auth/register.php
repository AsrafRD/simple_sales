<?php
session_start();
include '../config.php'; // Pastikan path ke config.php benar
include '../functions.php'; // Pastikan path ke functions.php benar

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Simpan pengguna ke database
    $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
    if ($stmt === false) {
        die("Error preparing statement: " . $conn->error);
    }
    $stmt->bind_param("ss", $username, $hashedPassword);
    if ($stmt->execute()) {
        $success = "Registration successful.";
    } else {
        $error = "Error: " . $stmt->error;
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            padding: 0;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .register-container {
            max-width: 500px;
            width: 100%;
            padding: 20px;
            background: #ffffff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .register-header {
            margin-bottom: 20px;
        }
        .register-footer {
            margin-top: 20px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="register-container">
        <div class="register-header">
            <h1 class="text-center">Register</h1>
        </div>
        <?php if (isset($success)): ?>
            <div class="alert alert-success" role="alert">
                <?= htmlspecialchars($success) ?>
            </div>
        <?php endif; ?>
        <?php if (isset($error)): ?>
            <div class="alert alert-danger" role="alert">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>
        <form method="POST" action="register.php">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Register</button>
        </form>
        <div class="register-footer">
            <p>Alreadyave an account? <a href="login.php">Login here</a>.</p>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
