<?php
require_once 'database/database.php';
session_start();
if(!isset($_SESSION['user_auth'])) {
    header('Location: auth/login.html');
    exit();
}
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: catalog.php');
    exit();
}
$productId = (int)$_GET['id'];
$sql = "SELECT * FROM categories WHERE ID = ? AND status = 'active'";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $productId);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();
if (!$product) {
    header('Location: catalog');
    exit();
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
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="shortcut icon" type="image/x-icon" href="icons/lettering.svg">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/catalog.css">
    <link rel="stylesheet" href="css/main.css">
    <title>Enigma | <?= htmlspecialchars($product['Name']) ?></title>
</head>
<body>
<header>
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="d-flex flex-column flex-md-row align-items-center pb-3 mb-4 border-bottom">
                    <a href="index.php" class="d-flex align-items-center text-decoration-none">
                        <span class="fs-4 text-primary fw-bold">Enigma</span>
                    </a>
                    <nav class="d-inline-flex mt-2 mt-md-0 ms-md-auto">
                        <a class="me-3 py-2 text-decoration-none" href="index">Главная</a>
                        <?php if(isset($_SESSION['user_auth'])): ?>
                            <a class="me-3 py-2 text-decoration-none" href="profile">Профиль</a>
                            <a class="me-3 py-2 text-decoration-none" href="auth/logout">Выход</a>
                        <?php else: ?>   
                            <a class="me-3 py-2 text-decoration-none" href="auth/register">Регистрация</a>
                            <a class="me-3 py-2 text-decoration-none" href="auth/login">Вход</a>
                        <?php endif; ?>
                        <?php if(isset($_SESSION['admin_auth'])): ?>
                            <a class="me-3 py-2 text-decoration-none" href="admin/admin">Админ-панель</a>
                        <?php endif; ?>
                        <?php if(isset($_SESSION['user_auth'])): ?>
                            <a class="me-3 py-2 text-decoration-none" href="profile"><?= $_SESSION['user_login'] ;
                            if($_SESSION['system_admin'] == true) {
                                echo " <p class='text-danger'>(Администратор)</p>";
                            }elseif($_SESSION['admin_auth'] == true) {
                                echo " <p class='text-danger'>(Модератор)</p>";
                            }endif;
                            ?></a>
                        <?php  if (isset($user['avatar']) && !empty($user['avatar']) && file_exists($user['avatar'])): ?>
                            <a href="profile"><img src="<?php echo $user['avatar']; ?>" alt="Avatar" class="rounded-circle mt-3" style="width: 50px; height: 50px; object-fit: cover;"></a>
                           <?php else: ?>
                            <a href="profile"><img src="https://via.placeholder.com/50" alt="Avatar" class="rounded-circle mt-3" style="width: 50px; height: 50px; object-fit: cover;"></a>
                        <?php endif; ?>
                        <button id="theme-toggle" class="btn btn-light position-fixed top-0 end-0 m-3">🌙</button>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</header>

<div class="container mt-5">
    <div class="row">
        <div class="col-md-6">
            <img src="<?= htmlspecialchars($product['Image']) ?>" class="img-fluid rounded shadow" alt="<?= htmlspecialchars($product['Name']) ?>">
        </div>
        <div class="col-md-6">
            <h1 class="text-primary fw-bold mb-3"><?= htmlspecialchars($product['Name']) ?></h1>
            <p class="text-muted">Категория: <?= htmlspecialchars($product['category']) ?></p>
            <p class="fw-bold">Цена: <?= number_format($product['price'], 0, ',', ' ') ?> ₽</p>
            <p><?= htmlspecialchars($product['Description']) ?></p>
            <form action="cart" method="POST">
                <input type="hidden" name="product_id" value="<?= $product['ID'] ?>">
                <button type="submit" class="btn btn-primary">Добавить в корзину</button>
            </form>
        </div>
    </div>
</div>

<script src="scripts/load.js"></script>   
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
