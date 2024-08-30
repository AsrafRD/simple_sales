<?php
include '../config.php';
include '../functions.php';
include 'header.php';

// if (!isAuthenticated()) {
//     header('Location: login.php');
//     exit();
// }

// if (!isAdmin()) {
//     echo "Access denied. You are not authorized to view this page.";
//     exit();
// }

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    updateProduct($conn);
}

$id = $_GET['id'];
$product = getProduct($id);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h1>Edit Product</h1>
        <form action="edit_product.php?id=<?= htmlspecialchars($id) ?>" method="post" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?= htmlspecialchars($id) ?>">
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" id="name" name="name" class="form-control" value="<?= htmlspecialchars($product['name']) ?>" required>
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description" name="description" class="form-control" rows="4" required><?= htmlspecialchars($product['description']) ?></textarea>
            </div>
            <div class="form-group">
                <label for="price">Price (USD)</label>
                <input type="number" id="price" name="price" class="form-control" value="<?= htmlspecialchars($product['price']) ?>" step="0.01" required>
            </div>
            <div class="form-group">
                <label for="image">Image</label>
                <input type="file" id="image" name="image" class="form-control-file">
                <?php if ($product['image']): ?>
                    <img src="../assets/images/<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>" style="width: 100px; margin-top: 10px;">
                <?php endif; ?>
            </div>
            <button type="submit" name="update_product" class="btn btn-primary">Update Product</button>
            <a href="index.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
