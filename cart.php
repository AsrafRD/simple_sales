<?php
session_start();
include 'functions.php';

// Tangani penghapusan item dari keranjang
if (isset($_GET['action']) && $_GET['action'] == 'remove' && isset($_GET['id'])) {
    $productId = $_GET['id'];
    removeFromCart($productId);
    header('Location: cart.php');
    exit();
}

$cartItems = getCartItems();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Your Cart</h1>
        <div class="cart">
            <?php if (empty($cartItems)): ?>
                <div class="alert alert-info" role="alert">
                    Your cart is empty.
                </div>
            <?php else: ?>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Quantity</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($cartItems as $productId => $quantity): ?>
                            <?php $product = getProduct($productId); ?>
                            <tr>
                                <td><?= htmlspecialchars($product['name']) ?></td>
                                <td><?= htmlspecialchars($quantity) ?></td>
                                <td>
                                    <a href="cart.php?action=remove&id=<?= htmlspecialchars($productId) ?>" class="btn btn-danger btn-sm">Remove</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <!-- Tombol Continue Shopping dan Checkout -->
                <div class="d-flex justify-content-between">
                    <a href="index.php" class="btn btn-primary">Continue Shopping</a>
                    <a href="checkout.php" class="btn btn-success">Checkout</a>
                </div>

            <?php endif; ?>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>