<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once '../database/database.php';
session_start();
if(!$_SESSION['admin_auth'] === true && !$_SESSION['admin_auth_pass'] === true) {
    $_SESSION['error'] = 'none_admin_rule';
    header('Location: ../error.php');
    exit();
}
if (isset($_POST['submit_admin_reply'])) {

    $ticket_id = $_POST['ticket_id'];
    $admin_reply = mysqli_real_escape_string($conn, $_POST['admin_reply']);
    $admin_id = $_SESSION['user_id'];
    $ticket_status = $_POST['ticket_status']; 

    $query = "INSERT INTO support_replies (ticket_id, admin_id, reply_message) 
              VALUES ('$ticket_id', '$admin_id', '$admin_reply')";
    if (mysqli_query($conn, $query)) {

        $update_status_query = "UPDATE support_tickets SET status = '$ticket_status' WHERE ticket_id = '$ticket_id'";
        mysqli_query($conn, $update_status_query);
    } else {
        echo "Ошибка при отправке ответа: " . mysqli_error($conn);
    }
}

$query = "SELECT * FROM support_tickets ORDER BY created_at DESC";
$result = mysqli_query($conn, $query);
$section = isset($_GET['section']) ? $_GET['section'] : 'none';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ticket_id'])) {
    $ticketId = (int)$_POST['ticket_id'];

    $updateQuery = "UPDATE tickets SET status = 'in_progress', taken_by = ? WHERE id = ?";
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param('si', $_SESSION['user_login'], $ticketId);

    if ($stmt->execute()) {
        echo "<div class='alert alert-success alert-dismissible fade show' role='alert' id='ticket_taken'>Ответ на тикет #$ticketId был успешно отвравлен<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button></div>";
        echo "<script>setTimeout(function(){ document.getElementById('ticket_taken').classList.remove('show'); }, 1500);</script>";
    } else {
        echo "<p class='alert alert-danger'>Ошибка обновления тикета: " . $stmt->error . "</p>";
    }
}
$sql = "SELECT * FROM users WHERE ID = ?";
$stmt = $conn->prepare($sql);   
$stmt->bind_param('i', $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/admin.css">
    <link rel="shortcut icon" type="image/x-icon" href="../icons/lettering.svg">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/main.css">
    <title>Enigma | Админ панель</title>
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
                            <li class="list-group-item d-flex justify-content-between align-items-center"> 
                                <a href="../index" class="text-decoration-none">На главную</a>
                            </li>
                            <?php if($_SESSION['admin_auth'] == true): ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <a href="admin_profile" class="text-decoration-none">Личный кабинет</a>
                            </li>
                            <?php endif; ?>  
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <a href="../support" class="text-decoration-none">Поддержка</a>
                            </li>
                            <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                Админ панель
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                <li><a class="dropdown-item <?= ($section == 'none') ? 'active' : '' ?>" href="?section=none">Статистика</a></li>
                                <li><a class="dropdown-item <?= ($section == 'users') ? 'active' : '' ?>" href="?section=users">Пользователи</a></li>
                                <?php if($_SESSION['system_admin'] == true): ?>
                                <li><a class="dropdown-item <?= ($section == 'admin') ? 'active' : '' ?>" href="?section=admin">Администраторы</a></li>
                                <?php endif; ?>
                                <li><a class="dropdown-item <?= ($section == 'products') ? 'active' : '' ?>" href="?section=products">Товары</a></li>
                                <li><a class="dropdown-item <?= ($section == 'tickets') ? 'active' : '' ?>" href="?section=support_tickets">Тикеты 
                                    <?php
                                        $ticket_count = $conn->query("SELECT COUNT(*) FROM support_tickets WHERE status = 'open'")->fetch_row()[0];
                                        if ($ticket_count > 0) {
                                            echo '<span class="badge bg-danger rounded-pill">' . $ticket_count . '</span>';
                                        }
                                    ?>
                                </a></li>
                                <li><a class="dropdown-item <?= ($section == 'work_tickets') ? 'active' : '' ?>" href="?section=work_tickets">Ответы</a></li>
                                <li><a class="dropdown-item <?= ($section == 'messages') ? 'active' : '' ?>" href="?section=admin_messages">Сообщения</a></li>
                            </ul>
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
<main>
<?php if($section == 'none'): ?>
        <div class="container mt-4">
            <h1 class="mb-4">Статистика</h1>
            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h2 class="card-title" style="text-align: center; color: #007bff">Пользователи</h2>
                        </div>
                        <div class="card-body">
                            <p class="card-text">Количество пользователей: <?php echo $conn->query("SELECT COUNT(*) FROM users")->fetch_row()[0]; ?></p>
                        </div>
                    </div>  
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h2 class="card-title" style="text-align: center; color: #007bff">Товары</h2>
                        </div>
                        <div class="card-body">
                            <p class="card-text">Количество товаров: <?php echo $conn->query("SELECT COUNT(*) FROM categories")->fetch_row()[0]; ?></p>
                        </div>
                    </div>  
                </div>
            </div>
        </div>

<?php elseif($section == 'users'): ?>
        <div class="container mt-4">
            <h1 class="mb-4">Пользователи</h1>
            <div class="table-responsive">
                <table class="table table-hover table-striped">
                    <thead class="table-success">
                        <tr>    
                            <th scope="col">Аватар</th>
                            <th scope="col">ID</th>  
                            <th scope="col">Логин</th>
                            <th scope="col">Email</th>
                            <th scope="col">Дата регистрации</th>
                            <th scope="col">Статус</th>
                            <th scope="col">Админ</th>
                            <th scope="col">Действия</th>
                            <th scope="col">Действия</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $query = "SELECT * FROM users WHERE is_admin = 'user'";
                        $result = $conn->query($query);
                        while ($row = $result->fetch_assoc()) {
                            echo '<tr>';
                            echo '<td>' . '<img src="'.'../'. htmlspecialchars($row['avatar']) . '" alt="Avatar" class="rounded-circle mt-1 mb-1" style="width: 50px; height: 50px; object-fit: cover;">' . '</td>';
                            echo '<td><p>' . htmlspecialchars($row['ID']) . '</p></td>';
                            echo '<td><p>' . htmlspecialchars($row['Login']) . '</p></td>';
                            echo '<td><p>' . htmlspecialchars($row['Email']) . '</p></td>';
                            echo '<td><p>' . date('d.m.Y в H:i', strtotime(htmlspecialchars($row['date_reg']))) . '</p></td>';
                            echo '<td>' . ($row['is_active'] == 'active' ? '<p class="text-success fw-bold">Активен</p>' : '<p class="text-danger fw-bold">Не активен</p>') . '</td>';
                            echo '<td>' . ($row['is_admin'] == '1' ? '<p class="text-success fw-bold">Да</p>' : '<p class="text-danger fw-bold">Нет</p>') . '</td>';
                            echo '<td><a href="user?id=' . urlencode($row['ID']) . '" class="btn btn-sm btn-primary">Просмотреть</a></td>';
                            if(htmlspecialchars($row['is_active']) == 'banned') {
                                echo '<td><a href="recovery_user?id=' . urlencode($row['ID']) . '" class="btn btn-sm btn-success ms-1">Разбанить</a></td>';
                            }else {
                                echo '<td><a href="ban_user?id=' . urlencode($row['ID']) . '" class="btn btn-sm btn-danger ms-1">Забанить</a></td>';
                            };
                            echo '</td>';
                            echo '</tr>';
                        }
                        $result->close();
                        $conn->close();
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php elseif($section == 'products'): ?>
        <div class="container mt-4">
            <h1 class="mb-4">Товары</h1>
            <div class="table-responsive">
                <table class="table table-hover table-striped">
                    <thead class="table-success">
                        <tr>    
                            <th scope="col">ID</th>
                            <th scope="col">Название</th>  
                            <th scope="col">Описание</th>
                            <th scope="col">Цена</th>
                            <th scope="col">Статус</th>
                            <th scope="col">Действия</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $query = "SELECT * FROM categories";
                        $result = $conn->query($query);
                        while ($row = $result->fetch_assoc()) {
                            echo '<tr>';
                            echo '<td><p>' . htmlspecialchars($row['ID']) . '</p></td>';   
                            echo '<td><p>' . htmlspecialchars($row['Name']) . '</p></td>';
                            echo '<td><p>' . htmlspecialchars($row['Description']) . '</p></td>';
                            echo '<td><p>' . htmlspecialchars($row['price']) . '</p></td>';
                            echo '<td><p>' . ($row['status'] == 'active' ? 'Активен' : 'Не активен') . '</td>';
                            echo '<td><a class="btn btn-sm btn-primary">Просмотреть</a>';
                            if(htmlspecialchars($row['status']) == 'active') {
                                echo '<a href="off_product?id=' . urlencode($row['ID']) . '" class="btn btn-sm btn-danger mt-1">Заблокировать</a>';
                            }else {
                                echo '<a href="recovery_product?id=' . urlencode($row['ID']) . '" class="btn btn-sm btn-success mt-1">Разблокировать</a>';
                            };
                            echo '</td>';
                            echo '</tr>';
                        }
                        $result->close();
                        $conn->close();
                        ?>
                    </tbody>    
                </table>
            </div>
        </div>
    <?php elseif($section == 'admin'): ?>
        <div class="container mt-4">
            <h1 class="mb-4">Администраторы</h1>
            <div class="table-responsive">
                <table class="table table-hover table-striped">
                    <thead class="table-success">
                        <tr>    
                            <th scope="col">Аватар</th>
                            <th scope="col">ID</th>
                            <th scope="col">Логин</th>  
                            <th scope="col">Email</th>
                            <th scope="col">Дата регистрации</th>
                            <th scope="col">Действия</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $query = "SELECT * FROM users WHERE is_admin = 'admin'";
                        $result = $conn->query($query);
                        while ($row = $result->fetch_assoc()) {
                            echo '<tr>';
                            echo '<td>' . '<img src="'.'../'. htmlspecialchars($row['avatar']) . '" alt="Avatar" class="rounded-circle mt-1 mb-1" style="width: 50px; height: 50px; object-fit: cover;">' . '</td>';
                            echo '<td><p>' . htmlspecialchars($row['ID']) . '</p></td>';   
                            echo '<td><p>' . htmlspecialchars($row['Login']) . '</p></td>';
                            echo '<td><p>' . htmlspecialchars($row['Email']) . '</p></td>';
                            echo '<td><p>' . date('d.m.Y в H:i', strtotime(htmlspecialchars($row['date_reg']))) . '</p></td>';
                            echo '<td><a href="user?id=' . urlencode($row['ID']) . '" class="btn btn-sm btn-primary">Просмотреть</a></td>';
                            echo '</tr>';
                        }
                        $result->close();
                        $conn->close();
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php elseif($section == 'support_tickets'): ?>
        <div class="container mt-5">
    <h2>Чат поддержки</h2>
    <table class="table table-striped">
        <thead>
            <tr>
                <th scope="col">ID Тикета</th>
                <th scope="col">Пользователь</th>
                <th scope="col">Сообщение</th>
                <th scope="col">Статус</th>
                <th scope="col">Ответить</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $query = "SELECT * FROM support_tickets";
            $result = $conn->query($query);
            ?>
            <?php while ($ticket = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><p><?= $ticket['ticket_id'] ?></p></td>
                    <td>
                        <?php
                        $user_query = "SELECT ID,Login FROM users WHERE ID =  ". $ticket['user_id'];
                        $user_result = mysqli_query($conn, $user_query);
                        $user = mysqli_fetch_assoc($user_result);
                        echo $user['Login']."<br>"."<p>ID:(".$user['ID'].")</p>";
                        ?>
                    </td>
                    <td><p><?= nl2br(htmlspecialchars($ticket['message'])) ?></p></td>
                    <td><p><?= $ticket['status'] === 'open' ? '<p class="text-success fw-bold">Открыт</p>' : '<p class="text-danger fw-bold">Закрыт</p>' ?></p></td>
                    <td>
                        <?php if($ticket['status'] === 'closed'): ?>
                            <a class="btn btn-danger fw-bold" disabled>Закрыт</a>
                        <?php else: ?>
                        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#replyModal<?= $ticket['ticket_id'] ?>">Ответить</button>
                        <?php endif; ?>
                    </td>
                </tr>

                <div class="modal fade" id="replyModal<?= $ticket['ticket_id'] ?>" tabindex="-1" aria-labelledby="replyModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="replyModalLabel">Ответ на тикет #<?= $ticket['ticket_id'] ?></h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
                            </div>
                            <div class="modal-body">
                                <form action="" method="POST">
                                    <input type="hidden" name="ticket_id" value="<?= $ticket['ticket_id'] ?>">
                                    <div class="mb-3">
                                        <label for="admin_reply" class="form-label">Ваш ответ</label>
                                        <textarea name="admin_reply" id="admin_reply" class="form-control" rows="5" required></textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label for="ticket_status" class="form-label">Статус тикета</label>
                                        <select name="ticket_status" id="ticket_status" class="form-select">
                                            <option value="open" <?= $ticket['status'] === 'open' ? 'selected' : '' ?>>Открыт</option>
                                            <option value="closed" <?= $ticket['status'] === 'closed' ? 'selected' : '' ?>>Закрыт</option>
                                        </select>
                                    </div>
                                    <button type="submit" name="submit_admin_reply" class="btn btn-primary">Отправить ответ</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>
<?php elseif($section == 'admin_messages'): ?>
    <div class="messaging-container">
    <div class="contacts">
        <h2>Контакты</h2>
        <ul id="contacts-list">

        </ul>
    </div>

<div class="chat">
        <div class="chat-header">
            <span id="chat-contact-name">Выберите контакт</span>
            <span id="chat-time"></span>
        </div>
        <div class="chat-box" id="chat-box">

        </div>
        <div class="message-input">
            <textarea id="message" placeholder="Введите сообщение..."></textarea>
            <button onclick="sendMessage()">Отправить</button>
        </div>
    </div>
</div>
<?php elseif($section == 'work_tickets'): ?>
<?php 
    $query_work = "SELECT * FROM tickets WHERE status = 'open'";
    $result_work = $conn->query($query_work);
?>
   <div class="container py-5">
        <h1 class="mb-4">Тикеты для разработки</h1>

        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Название</th>
                    <th>Описание</th>
                    <th>Статус</th>
                    <th>Действие</th>
                </tr>
            </thead>
            <tbody>
                <?php if($result_work->num_rows > 0): ?>
                <?php while ($work = $result_work->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($work['id']) ?></td>
                        <td><?= htmlspecialchars($work['title']) ?></td>
                        <td><?= htmlspecialchars($work['description']) ?></td>
                        <td><?= htmlspecialchars($work['status']) ?></td>
                        <td>
                            <form method="post" class="d-inline">
                                <input type="hidden" name="ticket_id" value="<?= htmlspecialchars($work['id']) ?>">
                                <button type="submit" class="btn btn-primary btn-sm">Взять в разработку</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5">Нет открытых тикетов</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    
    
<?php endif; ?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="../scripts/section.js"></script>
<script src="../scripts/load.js"></script>
<script src="../scripts/message.js"></script>
</body>
</html>