<?php 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once '../database/database.php';
session_start();
if(!$_SESSION['admin_auth'] === true && !$_SESSION['admin_auth_pass'] === true) {
    header('Location: ../index');
    exit();
}
$sql = "SELECT * FROM users WHERE ID = ?";
$stmt = $conn->prepare($sql);   
$stmt->bind_param('i', $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$section = isset($_GET['section']) ? $_GET['section'] : 'none';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../icons/lettering.svg">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/main.css">
    <title>Личный кабинет <?= $_SESSION['user_login'] ?></title>
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
                            <a class="d-flex align-items-center text-decoration-none dropdown-toggle" href="#" id="dropdownUser" data-bs-toggle="dropdown" aria-expanded="false">
                                <span class="fs-5 ms-2 "><?= $_SESSION['user_login'] ?></span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end text-small shadow" aria-labelledby="dropdownUser">
                                <?php if($section != 'none'): ?>
                                <li><a class="dropdown-item me-3 py-2 text-decoration-none" href="?section=none">Профиль</a></li>
                                <?php endif; ?>
                                <li><a class="dropdown-item me-3 py-2 text-decoration-none" href="?section=tickets">Тикеты</a></li>
                            </ul>
                        </div>

                    <nav class="d-inline-flex mt-2 mt-md-0 ms-md-auto">          
                        <a class="me-3 py-2 text-decoration-none" href="../index">Главная</a>
                        <a class="me-3 py-2 text-decoration-none" href="../catalog">Каталог</a>
                        <?php if(isset($_SESSION['user_auth'])): ?>
                            <a class="me-3 py-2 text-decoration-none" href="../profile">Профиль</a>
                            <a class="me-3 py-2 text-decoration-none" href="../auth/logout">Выход</a>
                        <?php else: ?>   
                        <a class="me-3 py-2 text-decoration-none" href="../auth/registration">Регистрация</a>
                        <a class="me-3 py-2 text-decoration-none" href="../auth/login">Вход</a>
                        <?php endif; ?>
                        <?php if($_SESSION['admin_auth'] == True): ?>
                            <a class="me-3 py-2 text-decoration-none" href="admin_login">Админ-панель</a>
                        <?php endif; ?>
                        <?php if(isset($_SESSION['user_auth'])): ?>
                            <a class="me-3 py-2 text-decoration-none" href="../profile"><?= $_SESSION['user_login'] ;
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
<?php if($section == 'none'): ?>
    <div class="container mt-5">
    <h2>Привет <?= $_SESSION['user_login'] ?>!</h2>
    </div>
<?php elseif($section == 'tickets'): ?>
<?php 
    $query_tickets = "SELECT * FROM tickets WHERE taken_by = ?";
    $stmt_tickets = $conn->prepare($query_tickets);
    $stmt_tickets->bind_param('s', $_SESSION['user_login']);
    $stmt_tickets->execute();
    $result_tickets = $stmt_tickets->get_result();
?>
    <div class="container mt-5">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm border-0 rounded-4">
                    <div class="card-body p-5">
                        <h5 class="card-title text-center mb-4">Тикеты</h5>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th scope="col">ID</th>
                                        <th scope="col">Пользователь</th>
                                        <th scope="col">Тема</th>
                                        <th scope="col">Сообщение</th>
                                        <th scope="col">Статус</th>  
                                        <th scope="col">Дата создания</th>
                                        <th scope="col">Чат</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if($result_tickets && $result_tickets->num_rows > 0): ?>
                                    <?php while ($ticket = $result_tickets->fetch_assoc()): ?>
                                        <tr>                      
                                            <td><?= htmlspecialchars($ticket['id']) ?></td>
                                            <td><p>ID: (<?= htmlspecialchars($ticket['user_id']) ?>)</p></td>
                                            <td><?= htmlspecialchars($ticket['title']) ?></td>
                                            <td><?= htmlspecialchars($ticket['description']) ?></td>
                                            <td><?= htmlspecialchars($ticket['status']) ?></td>
                                            <td><?= htmlspecialchars($ticket['created_at']) ?></td>
                                            <td><a href="chat?id=<?= urlencode($ticket['user_id']) ?>" class="btn btn-primary">Перейти в чат с пользователем</a></td>
                                        </tr>
                                    <?php endwhile; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="5">Нет взятых тикетов</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script> 
<script src="../scripts/load.js"></script> 
</body>
</html>