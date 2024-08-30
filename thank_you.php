<?php
session_start();
$orderId = $_GET['order_id'] ?? null;

if (!$orderId) {
    header('Location: index.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thank You</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h1 class="my-4">Thank You!</h1>
        <p>Your order has been placed successfully. Your order ID is <strong><?= htmlspecialchars($orderId) ?></strong>.</p>
        <a href="index.php" class="btn btn-primary">Continue Shopping</a>
    </div>
</body>
</html>
