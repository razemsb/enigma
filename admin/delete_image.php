<?php 
session_start();
require_once '../database/database.php';
if(!$_SESSION['admin_auth'] === true && !$_SESSION['admin_auth_pass'] === true) {
    header('Location: ../index.php');
    exit();
}   
if(isset($_GET['id'])) {
    $query = "SELECT avatar FROM users WHERE ID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $_GET['id']);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();
    if($user['avatar'] != 'uploads/basic_avatar.webp') {
        unlink('../' . $user['avatar']);
    }
    $query = "UPDATE users SET avatar = 'uploads/basic_avatar.webp' WHERE ID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $_GET['id']);
    $stmt->execute();
    $stmt->close();
    $conn->close();
    header('Location: user?id=' . $_GET['id']);
    exit();
}