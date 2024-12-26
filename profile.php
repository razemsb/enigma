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
    <title>Профиль | Enigma</title>
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
                        <a class="me-3 py-2 text-decoration-none" href="index">Главная</a>
                        <a class="me-3 py-2 text-decoration-none" href="catalog">Каталог</a>
                        <?php if(isset($_SESSION['user_auth'])): ?>
                            <a class="me-3 py-2 text-decoration-none" href="auth/logout">Выход</a>
                        <?php else: ?>   
                        <a class="me-3 py-2 text-decoration-none" href="auth/register">Регистрация</a>
                        <a class="me-3 py-2 text-decoration-none" href="auth/login">Вход</a>
                        <?php endif; ?>
                        <?php if(isset($_SESSION['admin_auth'])): ?>
                            <a class="me-3 py-2 text-decoration-none" href="admin/admin_login">Админ-панель</a>
                        <?php endif; ?>
                        <?php if(isset($_SESSION['user_auth'])): ?>
                            <a class="me-3 py-2 text-decoration-none" href="profile"><?= $_SESSION['user_login'] ;
                            if($_SESSION['system_admin'] == true) {
                                echo " <p class='text-danger'>(Администратор)</p>";
                            }elseif($_SESSION['admin_auth'] == true) {
                                echo " <p class='text-danger'>(Модератор)</p>";
                            }
                            ?></a>
                            <?php 
                           if (isset($user['avatar']) && !empty($user['avatar']) && file_exists($user['avatar'])): ?>
                               <a href="profile"><img src="<?php echo $user['avatar']; ?>" alt="Avatar" class="rounded-circle mt-3" style="width: 50px; height: 50px; object-fit: cover;"></a>
                           <?php else: ?>
                               <a href="profile"><img src="https://via.placeholder.com/50" alt="Avatar" class="rounded-circle mt-3" style="width: 50px; height: 50px; object-fit: cover;"></a>
                           <?php endif; ?>
                        <?php endif; ?>
                        <button id="theme-toggle" class="btn btn-light position-fixed top-0 end-0 m-3">🌙</button>
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
                            <span class="highlight text-primary">Администратор</span>
                        </p>
                    <?php elseif($user['is_admin'] == 'system_admin'): ?>
                        <p class="card-text text-center fs-5">
                            <span class="highlight text-danger">Системный администратор</span>
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
                    
                    <ul class="list-group list-group-flush mb-4">
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Почта:</span>
                            <span><?php echo htmlspecialchars($user['Email']); ?></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Статус:</span>
                            <span><?php echo $user['is_active'] == 'active' ? 'Активен' : 'Заблокирован'; ?></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
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
