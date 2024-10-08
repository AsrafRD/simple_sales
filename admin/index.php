<?php
session_start();
include '../config.php';
include '../functions.php';
include 'header.php';

// Periksa apakah tombol logout ditekan
// if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['logout'])) {
//     logout();
// }


// Debugging: Periksa apakah sesi diset dengan benar

// if (!isAuthenticated()) {
//     header('Location: ../auth/login.php');
//     exit();
// }

// if (!isAdmin()) {
//     echo "Access denied. You are not authorized to view this page.";
//     exit();
// }

// Jika sampai di sini, berarti pengguna telah diotentikasi dan merupakan admin


// Cek apakah sesi login ada dan benar
// if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
//     // Jika tidak ada atau sesi tidak benar, arahkan kembali ke halaman login
//     header('Location: ../auth/login.php');
//     exit();
// }

// Jika sesi ada dan benar, lanjutkan dengan konten halaman
// echo "Welcome, " . htmlspecialchars($_SESSION['username']) . "!";

$products = getProducts();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Product List</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h1>Product List</h1>
        <a href="add_product.php" class="btn btn-primary mb-3">Add New Product</a>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Price</th>
                    <th>Image</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $product): ?>
                    <tr>
                        <td><?= htmlspecialchars($product['id']) ?></td>
                        <td><?= htmlspecialchars($product['name']) ?></td>
                        <td><?= htmlspecialchars($product['description']) ?></td>
                        <td><?= htmlspecialchars($product['price']) ?> USD</td>
                        <td>
                            <?php if ($product['image']): ?>
                                <img src="../assets/images/<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>" style="width: 100px;">
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="edit_product.php?id=<?= htmlspecialchars($product['id']) ?>" class="btn btn-warning btn-sm">Edit</a>
                            <a href="delete_product.php?action=delete&id=<?= htmlspecialchars($product['id']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
