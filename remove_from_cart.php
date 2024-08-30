<?php
session_start();
include 'functions.php';

if (isset($_GET['id'])) {
    $productId = $_GET['id'];
    removeFromCart($productId);
    header('Location: cart.php');
    exit();
}
