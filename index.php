<?php
session_start();
include 'functions.php';
include 'layouts/header.php';

// if (!isAuthenticated()) {
//     header('Location: auth/login.php');
//     exit();
// }

// if (!isCustomer()) {
//     echo "Access denied. You are not authorized to view this page.";
//     exit();
// }

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['logout'])) {
    logout(); // Panggil fungsi logout
}

$loggedIn = isset($_SESSION['logged_in']) && $_SESSION['logged_in'];

// Ambil produk dari database
$products = getProducts();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simple Sales</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
    <h1 class="text-center my-4">Welcome to Simple Sales</h1>
    <div class="container">
        <div class="row">
            <?php if (empty($products)): ?>
                <div class="col-12 text-center">
                    <p class="text-muted">No products found.</p>
                </div>
            <?php else: ?>
                <?php foreach ($products as $product): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card">
                            <img src="assets/images/<?= htmlspecialchars($product['image']) ?>" class="card-img-top" alt="<?= htmlspecialchars($product['name']) ?>">
                            <div class="card-body">
                                <h5 class="card-title"><?= htmlspecialchars($product['name']) ?></h5>
                                <p class="card-text"><?= htmlspecialchars($product['price']) ?> USD</p>
                                <a class="btn btn-primary" href="add_to_cart.php?id=<?= htmlspecialchars($product['id']) ?>&quantity=1">Add to Cart</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <?php include 'layouts/footer.php'; ?>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
