<?php
require_once '../database/database.php';
session_start();

if ($_POST['message'] && $_POST['user_id']) {
    $message = $_POST['message'];
    $user_id = $_POST['user_id'];
    $admin_id = $_SESSION['user_id']; 

    $query = "INSERT INTO chat (user_id, admin_id, message, sender) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('iiss', $user_id, $admin_id, $message, 'admin'); 
    $stmt->execute();
    $stmt->close();
}
?>
