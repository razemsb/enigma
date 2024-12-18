<?php
require_once '../database/database.php';
session_start();

if ($_POST['ticket_id']) {
    $ticket_id = $_POST['ticket_id'];

    $query = "UPDATE tickets SET status = 'closed' WHERE ID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $ticket_id);
    $stmt->execute();
    $stmt->close();
}
?>
