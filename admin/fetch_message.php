<?php
session_start();
include '../database/database.php';

if(!isset($_SESSION['admin_auth']) || !isset($_SESSION['user_id'])) {
    echo "Unauthorized access!";
    exit();
}

if (isset($_GET['receiver_id'])) {
    $receiver_id = $_GET['receiver_id'];
    $sender_admin_id = $_SESSION['user_id']; 

    $sql = "SELECT m.message, m.sent_at, sender.Login AS sender_login, receiver.Login AS receiver_login
            FROM admin_messages m
            JOIN users sender ON m.sender_admin_id = sender.ID
            JOIN users receiver ON m.receiver_admin_id = receiver.ID
            WHERE (m.sender_admin_id = '$sender_admin_id' AND m.receiver_admin_id = '$receiver_id')
            OR (m.sender_admin_id = '$receiver_id' AND m.receiver_admin_id = '$sender_admin_id')
            ORDER BY m.sent_at ASC";

    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo '<div class="message">';
            echo '<strong class="sender">' . htmlspecialchars($row['sender_login']) . '</strong> → ';
            echo '<strong class="receiver">' . htmlspecialchars($row['receiver_login']) . '</strong>: ';
            echo '<p>' . htmlspecialchars($row['message']) . '</p>';
            echo '<span class="timestamp">' . $row['sent_at'] . '</span>';
            echo '<hr>';
            echo '</div>';
        }
    } else {
        echo '<p>Нет сообщений для отображения.</p>';
    }
} else {
    echo '<p>Не указан получатель сообщения.</p>';
}
?>
