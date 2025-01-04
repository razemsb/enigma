<?php 
session_start();
require_once '../database/database.php';

if (!isset($_SESSION['user_auth'])) {
    header('Location: auth/login');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['total_price']) && isset($_SESSION['cart'])) {
    if (count($_SESSION['cart']) == 0) {
        header('Location: ../cart');
        exit();
    }

    $user_id = $_SESSION['user_id'];
    $total_price = $_POST['total_price'];
    $products = array_keys($_SESSION['cart']);
    $products = implode(', ', $products);

    try {
        $conn->begin_transaction();
        $sql = "INSERT INTO orders (user_id, total_price, products) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('iis', $user_id, $total_price, $products);
        $stmt->execute();
        $sql2 = "UPDATE users SET orders_count = orders_count + 1 WHERE ID = ?";
        $stmt = $conn->prepare($sql2);
        $stmt->bind_param('i', $user_id);
        $stmt->execute();
        unset($_SESSION['cart']);
        $conn->commit();
        header('Location: ../cart');
        exit();
    } catch (Exception $e) {
        $conn->rollback();
        error_log("Ошибка транзакции: " . $e->getMessage());
        header('Location: ../cart?error=transaction_failed');
        exit();
    }
}
?>
