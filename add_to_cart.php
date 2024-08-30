<?php
session_start();
include 'functions.php';

if (isset($_GET['id']) && isset($_GET['quantity'])) {
    $productId = $_GET['id'];
    $quantity = (int)$_GET['quantity'];
    addToCart($productId, $quantity);
    header('Location: index.php');
    exit();
}
