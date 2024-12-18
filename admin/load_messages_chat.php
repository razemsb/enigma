<?php
require_once '../database/database.php';
session_start();

if ($_GET['id']) {
    $id = $_GET['id'];
    $queryMessages = "SELECT * FROM chat WHERE user_id = ? OR admin_id = ?";
    $stmtMessages = $conn->prepare($queryMessages);
    $stmtMessages->bind_param('ii', $id, $_SESSION['user_id']);
    $stmtMessages->execute();
    $messages = $stmtMessages->get_result();

    while ($message = $messages->fetch_assoc()) {
        echo '<div class="message ' . ($message['sender'] == 'admin' ? 'bg-primary' : 'bg-light') . ' p-2 my-2">
                <strong>' . $message['sender'] . ':</strong> ' . $message['message'] . '
              </div>';
    }

    $stmtMessages->close();
}
?>
