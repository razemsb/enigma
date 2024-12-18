<?php
session_start();
include '../database/database.php'; 
if(!$_SESSION['admin_auth'] === true && !$_SESSION['admin_auth_pass'] === true) {
    header('Location: ../index.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $message = $_POST['message'];
    $receiver_admin_id = $_POST['receiver_admin_id'];

    if (!empty($message) && !empty($receiver_admin_id)) {

        $sender_admin_id = $_SESSION['user_id']; 

        $sql = "INSERT INTO admin_messages (sender_admin_id, receiver_admin_id, message, sent_at)
                VALUES ('$sender_admin_id', '$receiver_admin_id', '$message', NOW())";

        if (mysqli_query($conn, $sql)) {
            echo "Сообщение отправлено!";
        } else {
            echo "Ошибка: " . mysqli_error($conn);
        }
    } else {
        echo "Заполните все поля!";
    }
}
?>
