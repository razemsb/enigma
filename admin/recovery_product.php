<?php
session_start();
require_once('../database/database.php');
if(!$_SESSION['admin_auth'] === true && !$_SESSION['admin_auth_pass'] === true) {
    header('Location: ../index.php');
    exit();
}
if(isset($_GET['id'])) {
    $query = "UPDATE categories SET status = 'active' WHERE ID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $_GET['id']);
    $stmt->execute();
    $stmt->close();
    $conn->close();
    header('Location: admin_panel?section=products');
    exit();
}   
?>