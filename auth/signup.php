<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
require_once('../database/database.php');

function sanitize_input($data) {
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

function validate_email($email) {
    return filter_var(trim($email), FILTER_SANITIZE_EMAIL);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = sanitize_input($_POST['login']);
    $pass = sanitize_input($_POST['password']);
    $repeatpass = sanitize_input($_POST['repeatpassword']);
    $email = validate_email($_POST['email']);

    if (empty($name) || empty($pass) || empty($repeatpass) || empty($email)) {
        echo "<script>alert('Все поля обязательны для заполнения.'); window.history.back();</script>";
        exit();
    }

    if ($pass !== $repeatpass) {
        echo "<script>alert('Пароли не совпадают.'); window.history.back();</script>";
        exit();
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('Некорректный email.'); window.history.back();</script>";
        exit();
    }

    $conn = new mysqli($host, $user, $password, $db);
    if ($conn->connect_error) {
        die("Ошибка подключения: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("SELECT Login, Email FROM users WHERE Login = ? OR Email = ?");
    if (!$stmt) {
        die("Ошибка подготовки запроса: " . $conn->error);
    }

    $stmt->bind_param("ss", $name, $email);
    if (!$stmt->execute()) {
        die("Ошибка выполнения запроса: " . $stmt->error);
    }

    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        echo "<script>alert('Логин или email уже занят.'); window.history.back();</script>";
        $stmt->close();
        $conn->close();
        exit();
    }
    $stmt->close();

    $hashed_password = password_hash($pass, PASSWORD_BCRYPT);

    $is_admin = 'user';
    $orders_count = 0;
    $avatar = 'uploads/basic_avatar.webp'; 

    $stmt = $conn->prepare("INSERT INTO users (Login, Password, Email, date_reg, is_admin, avatar, orders_count) VALUES (?, ?, ?, NOW(), ?, ?, ?)");
    if (!$stmt) {
        die("Ошибка подготовки запроса: " . $conn->error);
    }

    $stmt->bind_param("sssssi", $name, $hashed_password, $email, $is_admin, $avatar, $orders_count);
    if (!$stmt->execute()) {
        die("Ошибка выполнения запроса: " . $stmt->error);
    }

    $_SESSION['user_auth'] = true;
    $_SESSION['user_id'] = $conn->insert_id;
    $_SESSION['admin_auth'] = false;
    $_SESSION['user_login'] = $name;

    session_regenerate_id(true);

    echo "<script>alert('Регистрация прошла успешно!'); window.location.href = '../index';</script>";

    $stmt->close();
    $conn->close();
}
?>