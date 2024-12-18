<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL); 

session_start();
require_once('../database/database.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['login'];
    $pass = $_POST['password'];

    $stmt = $conn->prepare("SELECT ID, Password, is_admin, is_active FROM users WHERE Login = ?");
    $stmt->bind_param("s", $name);
    $stmt->execute();
    $stmt->bind_result($id, $Password, $is_admin, $is_active);
    
    if ($stmt->fetch()) {
        if ($is_active == 'banned') {
            echo "<script>alert('Ваш аккаунт заблокирован');</script>";
            $_SESSION['error'] = 'account_banned';
            header('Location: ../error');
        }

        if (password_verify($pass, $Password)) {
            if ($is_admin == 'admin') {
                echo "<script>alert('Успешная авторизация администратора'); window.location.href = '../index';</script>";   
                $_SESSION['admin_auth'] = true;
                $_SESSION['user_login'] = $name;
                $_SESSION['user_auth'] = true;
                $_SESSION['user_id'] = $id;
                $_SESSION['system_admin'] = false;
            } elseif ($is_admin == 'user') {
                echo "<script>alert('Успешная авторизация'); window.location.href = '../index';</script>";
                $_SESSION['user_auth'] = true;
                $_SESSION['user_login'] = $name;
                $_SESSION['admin_auth'] = false;
                $_SESSION['user_id'] = $id;
                $_SESSION['system_admin'] = false;
            }elseif($is_admin == 'system_admin') {
                echo "<script>alert('Успешная авторизация системного администратора'); window.location.href = '../index';</script>";   
                /*$_SESSION['admin_auth_pass'] = false;*/
                $_SESSION['user_login'] = $name;
                $_SESSION['user_auth'] = true;
                $_SESSION['user_id'] = $id;
                $_SESSION['system_admin'] = true;
                $_SESSION['admin_auth'] = true;
            }
        } else {
            echo "<script>alert('Неверный логин или пароль'); window.location.href = 'login';</script>";
        }
    } else {
        echo "<script>alert('Пользователь не найден'); window.location.href = 'login';</script>";
    }

    $stmt->close();
}
$conn->close();
?>
