<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
require_once('../database/database.php');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = htmlspecialchars(trim($_POST['login']), ENT_QUOTES, 'UTF-8');
    $pass = trim($_POST['password']);
    $stmt = $conn->prepare("SELECT ID, Password, is_admin, is_active FROM users WHERE Login = ?");
    if (!$stmt) {
        die("Ошибка подготовки запроса: " . $conn->error);
    }
    $stmt->bind_param("s", $name);
    $stmt->execute();
    $stmt->bind_result($id, $hashedPassword, $is_admin, $is_active);

    if ($stmt->fetch()) {
        if ($is_active === 'banned') {
            $_SESSION['error'] = 'account_banned';
            header('Location: ../error');
            exit();
        }
        if (password_verify($pass, $hashedPassword)) {
            $_SESSION['user_auth'] = true;
            $_SESSION['user_login'] = $name;
            $_SESSION['user_id'] = $id;

            if ($is_admin === 'admin') {
                $_SESSION['admin_auth'] = true;
                $_SESSION['system_admin'] = false;
                header('Location: ../index');
                exit();
            } elseif ($is_admin === 'user') {
                $_SESSION['admin_auth'] = false;
                $_SESSION['system_admin'] = false;
                header('Location: ../index');
                exit();
            } elseif ($is_admin === 'system_admin') {
                $_SESSION['admin_auth'] = true;
                $_SESSION['system_admin'] = true;
                header('Location: ../index');
                exit();
            }
        } else {
            $_SESSION['error'] = 'invalid_credentials';
            header('Location: login');
            exit();
        }
    } else {
        $_SESSION['error'] = 'user_not_found';
        header('Location: login');
        exit();
    }

    $stmt->close();
}

$conn->close();
?>