<?php
session_start();
include 'functions.php';

$cartItems = getCartItems();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validasi input
    $name = htmlspecialchars($_POST['name']);
    $address = htmlspecialchars($_POST['address']);
    $paymentMethod = htmlspecialchars($_POST['payment_method']);

    // Proses checkout: simpan pesanan ke database, dll.
    if (!empty($cartItems)) {
        $totalAmount = calculateTotalAmount($cartItems);
        $orderId = saveOrder($cartItems, $totalAmount); // Simpan pesanan dan dapatkan ID pesanan
        
        if ($orderId) {
            clearCart(); // Kosongkan keranjang setelah checkout
            header('Location: thank_you.php?order_id=' . $orderId);
            exit();
        } else {
            $error = "Failed to process your order. Please try again.";
        }
    } else {
        $error = "Your cart is empty. Please add items to your cart before checking out.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h1 class="my-4">Checkout</h1>

        <?php if (isset($error)): ?>
            <div class="alert alert-danger">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($cartItems)): ?>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Price</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cartItems as $productId => $quantity): ?>
                        <?php $product = getProduct($productId); ?>
                        <tr>
                            <td><?= htmlspecialchars($product['name']) ?></td>
                            <td><?= htmlspecialchars($quantity) ?></td>
                            <td><?= htmlspecialchars($product['price'] * $quantity) ?> USD</td>
                        </tr>
                    <?php endforeach; ?>
                    <tr>
                        <td colspan="2" class="text-right"><strong>Total:</strong></td>
                        <td><strong><?= htmlspecialchars(calculateTotalAmount($cartItems)) ?> USD</strong></td>
                    </tr>
                </tbody>
            </table>

            <form method="post">
                <div class="form-group">
                    <label for="name">Name:</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>
                <div class="form-group">
                    <label for="address">Address:</label>
                    <textarea class="form-control" id="address" name="address" rows="3" required></textarea>
                </div>
                <div class="form-group">
                    <label for="payment_method">Payment Method:</label>
                    <select class="form-control" id="payment_method" name="payment_method" required>
                        <option value="credit_card">Credit Card</option>
                        <option value="paypal">PayPal</option>
                        <option value="bank_transfer">Bank Transfer</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Place Order</button>
            </form>
        <?php else: ?>
            <p>Your cart is empty. <a href="index.php">Continue shopping</a></p>
        <?php endif; ?>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
