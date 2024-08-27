<?php
include 'functions.php';
$products = getProducts();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simple Sales</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/styles.css"> <!-- Your custom styles if needed -->
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
                            <img src="assets/images/<?= $product['image'] ?>" class="card-img-top" alt="<?= $product['name'] ?>">
                            <div class="card-body">
                                <h5 class="card-title"><?= $product['name'] ?></h5>
                                <p class="card-text"><?= $product['price'] ?> USD</p>
                                <a href="cart.php?action=add&id=<?= $product['id'] ?>" class="btn btn-primary">Add to Cart</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

