<?php   
require_once('../database/database.php');
session_start();
if(!isset($_SESSION['admin_auth']) || !$_SESSION['admin_auth']) {
    header('Location: ../index.php');
    exit();
}
if(isset($_GET['id'])) {
    $query = "UPDATE categories SET status = 'note_active' WHERE ID = ?";
    $stmt = $conn->prepare($query);
    if($stmt) {
        $stmt->bind_param('i', intval($_GET['id']));
    } else {
        echo "Error: " . $conn->error;
    }
    if($stmt->execute()) {
        $stmt->close();
        $conn->close();
        header('Location: admin_panel?section=products');
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
}   
?> 