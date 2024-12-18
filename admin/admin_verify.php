<?php
session_start();
require_once('../database/database.php');
if(isset($_POST['admin_password'])) {
    $admin_login = $_SESSION['user_login'];
    $admin_password = $_POST['admin_password'];

    $stmt = $conn->prepare("SELECT password_admin FROM admin WHERE login_admin = ?");
    $stmt->bind_param("s", $admin_login);
    $stmt->execute();
    $stmt->bind_result($Password);
    if ($stmt->fetch()) {
        if (password_verify($admin_password, $Password)) {
            $_SESSION['admin_auth_pass'] = true;
            header('Location: admin_panel');
            exit();
        } else {
            echo "<script>alert('Неверный пароль'); window.location.href = 'admin_login';</script>";
            exit();
        }
    } else {
        echo "<script>alert('Пользователь не найден'); window.location.href = 'admin_login.php';</script>";
        exit();
    }
}