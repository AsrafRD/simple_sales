<?php
include '../config.php';
include '../functions.php';

if (isset($_GET['id'])) {
    deleteProduct($conn);
    header("Location: index.php");
    exit();
}
?>
