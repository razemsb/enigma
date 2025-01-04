<?php
session_start();
require_once '../database/database.php';

if (!isset($_GET['file']) || empty($_GET['file'])) {
    header('Location: ../cart');
    exit();
}

$file = '../temp/' . basename($_GET['file']);
if (!file_exists($file)) {
    die('Файл не найден!');
}
$sql = "SELECT * FROM users WHERE ID = ?";
$stmt = $conn->prepare($sql);   
$stmt->bind_param('i', $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enigma | Покупка</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/main.css">
</head>
<body>
<header>
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="d-flex flex-column flex-md-row align-items-center pb-3 mb-4 border-bottom">
                    <a href="" class="d-flex align-items-center text-decoration-none">
                        <span class="fs-4 enigma_logo">Enigma</span>
                    </a>
                    <nav class="d-inline-flex mt-2 mt-md-0 ms-md-auto">
                    <?php if (isset($_SESSION['user_auth'])): ?>
                    <div class="d-flex flex-column flex-md-row align-items-center pb-3 mb-4 mt-5">
                    <h3 class="fs-4"><?= $user['Login']; ?></h3>
                    <img src="../<?= $user['avatar']; ?>" class="rounded-circle  mt-1 mb-1 ms-3" style="width: 50px; height: 50px; object-fit: cover;">
                    </div>
                    <?php else: ?>
                    <?php endif; ?>
                        <button class="navbar-toggler border-0 mt-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#sideMenu" aria-controls="sideMenu"><img src="../icons/menu.svg" style="width: 30px; height: 30px; object-fit: cover;"></button>
                        <div class="offcanvas offcanvas-end offcanvas-menu" tabindex="-1" id="sideMenu" aria-labelledby="sideMenuLabel">
                         <div class="offcanvas-header">
                             <h5 class="offcanvas-title" id="sideMenuLabel">Меню</h5>
                             <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                         </div>
                         <div class="offcanvas-body">
                             <ul class="list-group">
                            <?php if (isset($_SESSION['user_auth'])): ?>
                            <div class="d-flex flex-column flex-md-row align-items-center pb-3 mb-4 border-bottom">
                            <h3 class="fs-4"><?= $user['Login']; if($_SESSION['admin_auth'] == true) {
                            echo "<img src='../icons/admin.svg' class='ms-2 admin-svg' style='width: 30px; height: 30px; object-fit: cover;'>";
                            }
                            if($_SESSION['system_admin'] == true) {
                                echo " <p class='text-danger mt-1'>(Администратор)</p>";
                            }elseif($_SESSION['admin_auth'] == true) {
                                echo " <p class='text-danger mt-1'>(Модератор)</p>";
                            }
                            ?>
                            </h3>
                            <img src="../<?= $user['avatar']; ?>" class="rounded-circle mt-1 mb-1 ms-auto" style="width: 50px; height: 50px; object-fit: cover;">
                            </div>
                            <?php endif; ?>
                            <?php if($_SESSION['admin_auth'] == true): ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <a href="admin/admin_profile" class="text-decoration-none">Личный кабинет</a>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <a href="admin/admin_panel" class="text-decoration-none">Админ панель</a>
                            </li>
                            <?php endif; ?>
                            <?php if(isset($_SESSION['user_auth'])): ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <a href="auth/logout" class="text-decoration-none">Выход</a>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center"> 
                                <a href="index" class="text-decoration-none">На главную</a>
                            </li>
                            <?php else: ?> 
                            <li class="list-group-item d-flex justify-content-between align-items-center"> 
                                <a href="auth/registration" class="text-decoration-none">Регистрация</a>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center"> 
                                <a href="auth/login" class="text-decoration-none">Вход</a>
                            </li>
                            <?php endif; ?>   
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <a href="catalog" class="text-decoration-none">Каталог</a>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <a href="support" class="text-decoration-none">Поддержка</a>
                            </li>
                            <button id="theme-toggle" class="btn btn-light position-fixed top-0 end-0 m-3">🌙</button>
                             </ul>
                         </div>
                     </div>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</header>
    <div class="container mt-5 d-flex justify-content-center">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow-sm border-0 rounded-4">
                    <div class="card-body text-center p-5">
                        <h2 class="card-title mb-4 text-success">🎉 Заказ успешно оформлен!</h2>
                        <p class="card-text mb-4">Ваш заказ был успешно обработан. Спасибо за покупку!</p>
                        <a href="<?php echo htmlspecialchars($file); ?>" class="btn btn-primary btn-lg" download>
                            📄 Скачать чек
                        </a>
                        <a href="../" class="btn btn-secondary btn-lg mt-3">Вернуться на главную</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
<script src="../scripts/load.js"></script>   
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
