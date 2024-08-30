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

if (isset($_GET['id'])) {
    deleteProduct($conn);
    header("Location: index.php");
    exit();
}
?>
