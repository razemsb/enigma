<?php
session_start();
require_once('../database/database.php');
if(!$_SESSION['admin_auth'] === true) {
    header('Location: ../index.php');
    exit();
}
if($_SESSION['admin_auth'] === true) {
    header('Location: admin_panel');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Админ панель верефикация</title>
    <link rel="stylesheet" href="../css/admin.css">
    <link rel="shortcut icon" type="image/x-icon" href="../icons/lettering.svg">
    <link rel="stylesheet" href="../css/main.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
</head>
<body>
<header>
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="d-flex flex-column flex-md-row align-items-center pb-3 mb-4 border-bottom">
                    <a href="" class="d-flex align-items-center text-decoration-none">
                        <span class="fs-4">Enigma</span>
                    </a>

                    <nav class="d-inline-flex mt-2 mt-md-0 ms-md-auto">
                        <a class="me-3 py-2 text-dark text-decoration-none" href="../index.php">Главная</a>
                        <a class="me-3 py-2 text-dark text-decoration-none" href="../catalog.php">Каталог</a>
                        <?php if(isset($_SESSION['user_auth'])): ?>
                            <a class="me-3 py-2 text-dark text-decoration-none" href="../profile.php">Профиль</a>
                            <a class="me-3 py-2 text-dark text-decoration-none" href="logout_admin.php">Выход</a>
                        <?php else: ?>   
                        <a class="me-3 py-2 text-dark text-decoration-none" href="../auth/register.php">Регистрация</a>
                        <a class="me-3 py-2 text-dark text-decoration-none" href="../auth/login.php">Вход</a>
                        <?php endif; ?>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</header>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <form action="admin_verify.php" method="POST" class="form">
                    <h1 class="title">Админ панель</h1>
                    <input type="hidden" name="admin_login" value="<?= $_SESSION['user_login'] ?>">
                    <label for="admin_password">Введите пароль:</label>
                    <input type="password" name="admin_password" id="admin_password" class="form-control">
                    <button type="submit" class="btn">Войти</button>
                </form>
            </div>
        </div>
    </div>
<script src="../scripts/load.js"></script>
</body>
</html>