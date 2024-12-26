<?php
require_once 'database/database.php';
session_start();
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
    <link rel="shortcut icon" href="icons/lettering.svg">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/main.css">
    <title>Enigma | Главная</title>
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
                            <h3 class="fs-4"><?= $user['Login']; 
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
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-md-10 col-sm-12 text-center mb-5">
            <h1 class="display-4 fw-bold text-primary">Добро пожаловать на <span class="highlight text-success">Enigma</span></h1>
            <p class="lead text-muted">Ваш путь к современным веб-решениям</p>
        </div>
        <div class="col-lg-10">
            <div class="card shadow-sm border-0 rounded-4">
            <div class="card-body p-5">
    <h3 class="card-title text-center mb-4">О проекте</h3>
    <p class="card-text text-center mb-3 fs-5">
        Мы — команда <strong>Enigma</strong>, объединенная страстью к созданию современных веб-приложений. Наша цель — разрабатывать проекты, которые сочетают эстетику, функциональность и высокие технологии.
    </p>
    <p class="card-text text-center fs-5">
        В основе нашей работы лежат проверенные инструменты: <span class="highlight text-success">HTML5</span>, <span class="highlight text-success">CSS3</span>, <span class="highlight text-success">JavaScript</span>, <span class="highlight text-success">PHP</span> и <span class="highlight text-success">MySQL</span>.
    </p>
    <p class="card-text text-center fs-5">
        Мы активно используем такие мощные решения, как <span class="highlight text-primary">Bootstrap</span>, <span class="highlight">Laravel</span>, <span class="script_language text-primary">jQuery</span>, <span class="script_language text-primary">Vue.js</span> и <span class="script_language text-primary">React</span>, чтобы создавать удобные и привлекательные интерфейсы.
    </p>
    <hr class="my-4">
    <h4 class="card-title text-center mt-4 text-secondary">Что еще мы умеем</h4>
    <p class="card-text text-center fs-5">
        Для задач, требующих нестандартного подхода, мы используем языки <span class="highlight text-info">C#</span>, <span class="highlight text-info">C++</span>, <span class="highlight text-info">Java</span> и <span class="highlight text-info">Python</span>. Это позволяет нам решать широкий спектр задач — от создания серверной логики до разработки сложных систем.
    </p>
</div>

            </div>
        </div>
    </div>
</div>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body p-5">
                    <h3 class="card-title text-center mb-4">Услуги</h3>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <h5 class="card-title">Создание веб-приложений с нуля</h5>
                            <span class="badge bg-primary rounded-pill">New</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <h5 class="card-title">Сопровождение и поддержка существующих веб-приложений</h5>
                            <span class="badge bg-secondary rounded-pill">Popular</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <h5 class="card-title">Оптимизация и ускорение работы веб-приложений</h5>
                            <span class="badge bg-success rounded-pill">Best</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <h5 class="card-title">Модернизация и рефакторинг веб-приложений</h5>
                            <span class="badge bg-danger rounded-pill">Hot</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <h5 class="card-title">Создание landing page и корпоративных сайтов</h5>
                            <span class="badge bg-warning rounded-pill">Sale</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <h5 class="card-title">Разработка и внедрение CRM систем</h5>
                            <span class="badge bg-info rounded-pill">Info</span>
                        </li>
                    </ul>
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
<a href="support" class="fixed-circle">
    <img src="icons/support.svg" alt="support" class="img-fluid">
</a>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="scripts/load.js"></script>
</body>
</html>