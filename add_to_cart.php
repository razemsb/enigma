<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
require_once('database/database.php'); 

if (isset($_POST['product_id'])) {
    $productId = (int)$_POST['product_id'];
    if ($stmt = $conn->prepare("SELECT * FROM categories WHERE ID = ?")) {
        $stmt->bind_param('i', $productId);
        $stmt->execute();
        $result = $stmt->get_result();
        $product = $result->fetch_assoc();
    }
    if ($product) {
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
        $product['quantity'] = 1;
        if (isset($_SESSION['cart'][$productId])) {
            $_SESSION['cart'][$productId]['quantity']++;
        } else {
            $_SESSION['cart'][$productId] = $product;
        }
        $stmt->close();
        echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>Продукт добавлен в корзину<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button></div>";
        header('Location: cart');
        exit();
    } else {
        echo "<div class='alert alert-danger alert-dismissible fade show' role='alert'>Продукт не найден<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button></div>";
       header('Location: catalog');
        exit();
    }
} else {
    header('Location: catalog');
    exit();
}
