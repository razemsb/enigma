<?php
session_start();
require_once '../database/database.php';

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_POST['user_id'];   

    $query = "UPDATE users SET avatar = 'uploads/basic_avatar.webp' WHERE ID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $stmt->close();    
    $conn->close();
    header('Location: ../profile');
    exit();
}