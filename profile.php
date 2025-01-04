<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL); 
require_once 'database/database.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}
if (!isset($_SESSION['system_admin'])) {
    $_SESSION['system_admin'] = false;
}
$userId = $_SESSION['user_id'];
$query = "SELECT * FROM users WHERE ID = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $userId);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['avatar'])) {

    $file = $_FILES['avatar'];
    
    if ($file['error'] == 0) {
     
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        if (in_array($file['type'], $allowedTypes)) {
     
            $fileName = uniqid('avatar_', true) . '.' . pathinfo($file['name'], PATHINFO_EXTENSION);
            $filePath = 'uploads/' . $fileName;
        
            if (move_uploaded_file($file['tmp_name'], $filePath)) {

                echo "Файл загружен успешно. Путь: " . $filePath . "<br>";

                $updateQuery = "UPDATE users SET avatar = ? WHERE ID = ?";
                $stmt = $conn->prepare($updateQuery);

                if ($stmt === false) {
                    die('Ошибка подготовки запроса: ' . $conn->error);
                }

                $stmt->bind_param('si', $filePath, $userId);
                $executeResult = $stmt->execute();

                if ($executeResult) {
                    echo "Данные успешно обновлены в базе данных.<br>";
                    header("Location: profile.php");
                    exit();
                } else {
                    echo "Ошибка выполнения запроса: " . $stmt->error . "<br>";
                }

                $stmt->close();
            } else {
                $error = "Ошибка при загрузке файла.";
            }
        } else {
            $error = "Разрешены только изображения форматов JPEG, PNG и GIF.";
        }
    } else {
        $error = "Ошибка загрузки файла.";
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="shortcut icon" type="image/x-icon" href="icons/lettering.svg">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/main.css">
    <title>Enigma | <?= $user['Login']; ?></title>
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
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-md-10 col-sm-12 text-center mb-5">
            <h1 class="display-4 fw-bold text-primary">Добро пожаловать, <span class="highlight text-success"><?php echo htmlspecialchars($user['Login']); ?></span></h1>
            <p class="lead text-muted">Ваш профиль в Enigma</p>
        </div>
        <div class="col-lg-10">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body p-5">
                    <h3 class="card-title text-center mb-4">Информация о пользователе</h3>
                    <div class="text-center mb-4">
                        <?php if (!empty($user['avatar']) && file_exists($user['avatar'])): ?>
                            <img src="<?php echo $user['avatar']; ?>" alt="Avatar" class="rounded-circle" style="width: 150px; height: 150px; object-fit: cover;"><br>
                            <form action="admin/delete_avatar.php" method="POST" enctype="multipart/form-data">
                            <button type="submit" class="btn btn-danger mt-2">Удалить аватар</button>
                            <input type="hidden" name="user_id" value="<?php echo $user['ID']; ?>">
                            </form>
                        <?php else: ?>
                            <img src="https://via.placeholder.com/150" alt="Avatar" class="rounded-circle" style="width: 150px; height: 150px; object-fit: cover;">
                        <?php endif; ?>
                    </div>
                    <?php if($user['is_admin'] == 'admin'): ?>
                        <p class="card-text text-center fs-5">
                            <span class="text-primary">Администратор</span>
                        </p>
                    <?php elseif($user['is_admin'] == 'system_admin'): ?>
                        <p class="card-text text-center fs-5">
                            <span class="text-danger">Системный администратор</span>
                        </p>
                    <?php endif; ?>
                    <?php if ($user['avatar'] === 'uploads/basic_avatar.webp'): ?>
                    <form action="profile.php" method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="avatar" class="form-label">Загрузите новый аватар</label>
                            <input type="file" class="form-control" id="avatar" name="avatar" accept="image/*">
                        </div>
                        <button type="submit" class="btn btn-primary">Загрузить аватар</button>
                    </form>
                    <?php endif; ?>
                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger mt-3"><?php echo $error; ?></div>
                    <?php endif; ?>
                    <ul class="user-info list-group list-group-flush mb-4">
                        <li class="user-info-item d-flex justify-content-between py-2 border-bottom">
                            <span>Почта:</span>
                            <span><?php echo htmlspecialchars($user['Email']); ?></span>
                        </li>
                        <li class="user-info-item d-flex justify-content-between py-2 border-bottom">
                            <span>Статус:</span>
                            <span><?php echo $user['is_active'] == 'active' ? 'Активен' : 'Заблокирован'; ?></span>
                        </li>
                        <li class="user-info-item d-flex justify-content-between py-2 border-bottom">
                            <span>Количество заказов:</span>
                            <span><?php echo htmlspecialchars($user['orders_count']); ?></span>
                        </li>
                    </ul>
                    <div class="d-grid gap-2">
                        <a href="auth/logout.php" class="btn btn-danger">Выйти</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container mt-3">
    <div class="container mt-5">
        <h2 class="text-center mb-3">Мои заказы</h2>
        <div class="row justify-content-center">
        <?php
            $query = "SELECT * FROM orders WHERE user_id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param('i', $_SESSION['user_id']);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows > 0) {
                while($order = $result->fetch_assoc()) {
                    echo '<div class="col-lg-4 col-md-6 col-sm-12 mt-3">';
                    echo '<div class="card shadow-sm border-0 rounded-4">';
                    echo '<div class="card-body p-3">';
                    echo '<h5 class="card-title text-center mb-2">Заказ #' . $order['ID'] . '</h5>';
                    echo '<p class="card-text text-center">Дата: ' . date('d.m.Y', strtotime($order['order_date'])) . ' ' . '</p>';
                    $product_ids = explode(', ', $order['products']);
                    echo '<p class="card-text text-center">Товары: ';
                    $products_list = [];
                    foreach ($product_ids as $product_id) {
                        $sql = "SELECT * FROM categories WHERE ID = ?";
                        $stmt = $conn->prepare($sql);
                        $stmt->bind_param('i', $product_id);
                        $stmt->execute();
                        $product_result = $stmt->get_result();
                        while ($product = $product_result->fetch_assoc()) {
                            $products_list[] = $product['Name'];
                        }
                    }
                    echo implode(', ', $products_list); 
                    echo '</p>';
                    echo '<p class="card-text text-center">Стоимость: ' . $order['total_price'] . ' руб.</p>';
                    echo '</div>';
                    echo '</div>';
                    echo '</div>';
                }
            } else {
                echo '<p class="text-center">У вас нет заказов.</p>';
            }
            ?>
        </div>
    </div>
</div>

<footer class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body p-5">
                    <h5 class="card-title text-center mb-4">© 2025 Enigma. Все права защищены.</h5>
                    <p class="card-text text-center">
                       <a href="support" class="text-decoration-none">Техническая поддержка</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</footer>

<script src="scripts/load.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</body>
</html>
