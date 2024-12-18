<?php 
require_once '../database/database.php';    
session_start();
if ($_SESSION['admin_auth'] !== true || $_SESSION['admin_auth_pass'] !== true) {
    header('Location: ../index');
    exit();
}

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];
    $query = "SELECT * FROM users WHERE ID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    $queryMessages = "SELECT * FROM chat WHERE user_id = ? OR admin_id = ?";
    $stmtMessages = $conn->prepare($queryMessages);
    $stmtMessages->bind_param('ii', $id, $_SESSION['user_id']);
    $stmtMessages->execute();
    $messages = $stmtMessages->get_result();
    $stmtMessages->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../icons/lettering.svg">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/main.css">
    <title>Чат с <?= $user['Login'] ?></title>
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
                    <div class="dropdown">
                            <a class="d-flex align-items-center text-dark text-decoration-none dropdown-toggle" href="#" id="dropdownUser" data-bs-toggle="dropdown" aria-expanded="false">
                                <span class="fs-5 ms-2 "><?= $_SESSION['user_login'] ?></span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end text-small shadow" aria-labelledby="dropdownUser">
                                <?php if($section != 'none'): ?>
                                <li><a class="dropdown-item me-3 py-2 text-dark text-decoration-none" href="?section=none">Профиль</a></li>
                                <?php endif; ?>
                                <li><a class="dropdown-item me-3 py-2 text-dark text-decoration-none" href="?section=tickets">Тикеты</a></li>
                            </ul>
                        </div>

                    <nav class="d-inline-flex mt-2 mt-md-0 ms-md-auto">          
                        <a class="me-3 py-2 text-dark text-decoration-none" href="../index">Главная</a>
                        <a class="me-3 py-2 text-dark text-decoration-none" href="../catalog">Каталог</a>
                        <?php if(isset($_SESSION['user_auth'])): ?>
                            <a class="me-3 py-2 text-dark text-decoration-none" href="../profile">Профиль</a>
                            <a class="me-3 py-2 text-dark text-decoration-none" href="../auth/logout">Выход</a>
                        <?php else: ?>   
                        <a class="me-3 py-2 text-dark text-decoration-none" href="../auth/registration">Регистрация</a>
                        <a class="me-3 py-2 text-dark text-decoration-none" href="../auth/login">Вход</a>
                        <?php endif; ?>
                        <?php if($_SESSION['admin_auth'] == True): ?>
                            <a class="me-3 py-2 text-dark text-decoration-none" href="admin_login">Админ-панель</a>
                        <?php endif; ?>
                        <?php if(isset($_SESSION['user_auth'])): ?>
                            <a class="me-3 py-2 text-dark text-decoration-none" href="../profile"><?= $_SESSION['user_login'] ;
                            if($_SESSION['system_admin'] == true) {
                                echo " <p class='text-danger'>(Администратор)</p>";
                            }elseif($_SESSION['admin_auth'] == true) {
                                echo " <p class='text-danger'>(Модератор)</p>";
                            }
                            ?></a>
                            <?php 
                           if (isset($user['avatar']) && !empty($user['avatar'])): ?>
                              <img src="<?= '../' . htmlspecialchars($user['avatar']); ?>" alt="Avatar" class="rounded-circle mt-1 mb-1" style="width: 50px; height: 50px; object-fit: cover;">
                           <?php else: ?>
                               <a href="profile"><img src="https://via.placeholder.com/50" alt="Avatar" class="rounded-circle mt-3" style="width: 50px; height: 50px; object-fit: cover;"></a>
                           <?php endif; ?>
                        <?php endif; ?>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</header>
<div class="container mt-5">
    <h1>Чат с <?= $user['Login'] ?></h1>
    
    <div id="chatBox" class="mb-3" style="height: 400px; overflow-y: scroll;" data-user-id="<?= $user['ID'] ?>">
        <?php while ($message = $messages->fetch_assoc()): ?>
            <div class="message <?= $message['sender'] == 'admin' ? 'bg-primary' : 'bg-light' ?> p-2 my-2">
                <strong><?= $message['sender'] ?>:</strong> <?= $message['message'] ?>
            </div>
        <?php endwhile; ?>
    </div>

    <div class="input-group mb-3">
        <input type="text" id="messageInput" class="form-control" placeholder="Введите сообщение">
        <button class="btn btn-success" id="sendMessage">Отправить</button>
    </div>

    <button class="btn btn-danger" id="closeTicket">Закрыть тикет</button>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="../scripts/load.js"></script>
<script src="../scripts/chat.js"></script>
</body>
</html>
