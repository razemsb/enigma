<?php 
session_start();
require_once 'database/database.php';

if(!isset($_SESSION['user_auth'])) {
    header('Location: auth/login');
    exit();
}
$sql = "SELECT * FROM users WHERE ID = ?";
$stmt = $conn->prepare($sql);   
$stmt->bind_param('i', $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {
    $productId = (int)$_POST['delete'];
    if (isset($_SESSION['cart'][$productId])) {
        unset($_SESSION['cart'][$productId]);
    }
    header('Location: cart');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/main.css">
    <title>Enigma | Корзина</title>
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
                            <img src="<?= $user['avatar']; ?>" class="rounded-circle  mt-1 mb-1 ms-3" style="width: 50px; height: 50px; object-fit: cover;">
                            </div>
                            <?php else: ?>
                            <?php endif; ?>
                            <button class="navbar-toggler border-0 mt-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#sideMenu" aria-controls="sideMenu"><img src="icons/menu.svg" style="width: 30px; height: 30px; object-fit: cover;"></button>
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
                                echo "<img src='icons/admin.svg' class='ms-2 admin-svg' style='width: 30px; height: 30px; object-fit: cover;'>";
                                }
                                if($_SESSION['system_admin'] == true) {
                                    echo " <p class='text-danger mt-1'>(Администратор)</p>";
                                }elseif($_SESSION['admin_auth'] == true) {
                                    echo " <p class='text-danger mt-1'>(Модератор)</p>";
                                }
                                ?>
                                </h3>
                                <img src="<?= $user['avatar']; ?>" class="rounded-circle mt-1 mb-1 ms-auto" style="width: 50px; height: 50px; object-fit: cover;">
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
                                    <a href="profile" class="text-decoration-none">Профиль</a>
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
<div class="container">
    <div class="row">
        <div class="col-12">
            <h1>Корзина <?php if( count($_SESSION['cart']) >= 1) {
                echo "(" . count($_SESSION['cart']) . ")";    
            }
            ?>
            </h1>
             <?php
             if(isset($_SESSION['cart'])) {
                $product_id = array_keys($_SESSION['cart']);
                $product_id = implode(', ', $product_id);
                $sql = "SELECT * FROM categories WHERE ID IN ($product_id)";
                $stmt = $conn->prepare($sql);
                $stmt->execute();
                $result = $stmt->get_result();
                while($product = $result->fetch_assoc()) {
                    echo "
                    <div class='card mb-3 d-flex'>
                     <div class='row g-0'>
                       <div class='col-md-4'>
                         <img src='{$product['Image']}' class='img-fluid rounded-start' style='width: 100%; height: 100%; object-fit: cover;'>
                       </div>
                       <div class='col-md-8'>
                         <div class='card-body'>
                           <h5 class='card-title'>{$product['Name']} ID: {$product['ID']}</h5>
                           <p class='card-text'>Цена: {$product['price']} ₽</p>
                         </div>
                         <form action='' method='POST'>
                            <input type='hidden' name='delete' value='{$product['ID']}'>
                            <a href='' class='btn btn-danger ms-auto mb-5'>Очистить корзину</a>
                        </form>
                       </div>
                     </div>
                    </div>
                    ";
                }
             }
             $total_price = 0;
             if(isset($_SESSION['cart'])) {
                 $product_id = array_keys($_SESSION['cart']);
                 $product_id = implode(', ', $product_id);
                 $sql = "SELECT * FROM categories WHERE ID IN ($product_id)";
                 $stmt = $conn->prepare($sql);
                 $stmt->execute();
                 $result = $stmt->get_result();
                 while($product = $result->fetch_assoc()) {
                     $total_price += $product['price'];
                 }
             }
             ?>
        </div>
    </div>
</div>
<hr>
<div class="container d-flex">
    <p class="text-start">Общая сумма:<?= '<p class="fw-bold ms-2 me-1 text-success">'.$total_price."</p>" ?>  руб.</p>
    <div class="column-gap ms-auto d-flex">
    <form class="ms-1" action="" method="POST">
       <input type="hidden" name="" value="">
       <a href="" class="btn btn-primary ms-auto mb-5">Оформить заказ</a>
    </form>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="scripts/load.js"></script>
</body>
</html>