<?php
session_start();
require_once 'database/database.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" type="image/x-icon" href="icons/lettering.svg">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/error.css">
    <title>Enigma | Ошибка</title>
</head>
<body>
<main>
    <div class="error-container">
        <?php if ($_SESSION['error'] == 'account_banned'): ?>  
            <h1>Ваш аккаунт заблокирован</h1>
            <p>Пожалуйста, свяжитесь с администрацией для получения дополнительной информации.</p>
            <p>Ваш IP-адрес: <?= $_SERVER['REMOTE_ADDR']; ?></p>
            <a href="auth/login.html">Вернуться к авторизации</a>
        <?php elseif($_SESSION['error'] == 'none_admin_rule'): ?>
            <h1>Недостаточно прав</h1>
            <p>У вас нет прав для доступа к этой странице.</p>
            <a href="index.php" >Вернуться на главную</a>
        <?php elseif($_SESSION['error'] == 'db_error'): ?>
            <h1>Ошибка базы данных</h1>
            <p>Произошла ошибка базы данных. Пожалуйста, попробуйте позже.</p>
        <?php else: ?>
            <h1>Неизвестная ошибка</h1>
            <p>Произошла ошибка. Пожалуйста, попробуйте позже.</p>
        <?php endif; ?>
    </div>
</main>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="scripts/logout.js"></script>
</body>
</html>
