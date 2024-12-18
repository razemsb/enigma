<?php
session_start();
include 'database/database.php';

if($_SESSION['user_auth'] != true) {
    header('Location: index.php');
    exit(); 
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_ticket'])) {
    $message = mysqli_real_escape_string($conn, $_POST['message']);
    if (!empty($message)) {
        $query = "INSERT INTO support_tickets (user_id, message, status) VALUES ($user_id, '$message', 'open')";
        mysqli_query($conn, $query);
    }
    header("Location: support.php");
    exit();
}
$sql = "SELECT * FROM users WHERE ID = ?";
$stmt = $conn->prepare($sql);   
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$tickets_query = "SELECT 
    t.ticket_id,
    t.user_id,
    t.message AS message,
    t.status AS ticket_status,
    t.created_at AS created_at,
    t.updated_at AS updated_at,
    r.reply_id,
    r.reply_message AS reply_message,
    r.reply_date AS reply_date
FROM 
    support_tickets t
LEFT JOIN 
    support_replies r ON t.ticket_id = r.ticket_id
ORDER BY 
    t.created_at DESC, r.reply_date DESC;
    ";
$tickets_result = mysqli_query($conn, $tickets_query);
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Техническая поддержка</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="shortcut icon" type="image/x-icon" href="icons/lettering.svg">
    <link rel="stylesheet" href="css/main.css">
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
    <h2>Техническая поддержка</h2>

    <h4>Оставить новый тикет</h4>
    <form method="POST" action="support.php">
        <div class="mb-3">
            <label for="message" class="form-label">Сообщение:</label>
            <textarea name="message" id="message" class="form-control" rows="4" required></textarea>
        </div>
        <button type="submit" name="submit_ticket" class="btn btn-primary">Отправить тикет</button>
    </form>

    <hr>

    <h4>Ваши тикеты</h4>
    <?php if (mysqli_num_rows($tickets_result) > 0): ?>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th scope="col">ID Тикета</th>
                    <th scope="col">Сообщение</th>
                    <th scope="col">Статус</th>
                    <th scope="col">Дата создания</th>
                    <th scope="col">Ответ администратора</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($ticket = mysqli_fetch_assoc($tickets_result)): ?>
                    <tr>
                        <td><?= $ticket['ticket_id'] ?></td>
                        <td><?= nl2br(htmlspecialchars($ticket['message'])) ?></td>
                        <td><?= $ticket['status'] === 'open' ? 'Открыт' : 'Закрыт' ?></td>
                        <td><?= $ticket['created_at'] ?></td>
                        <td>
                            <?php if ($ticket['reply_message']): ?>
                                <strong>Ответ:</strong>
                                <p><?= nl2br(htmlspecialchars($ticket['reply_message'])) ?></p>
                                <small>Ответ дан: <?= $ticket['reply_date'] ?> <?=
                                
                                $admin_id = $ticket['admin_id'];
                                $sql = "SELECT * FROM users WHERE ID = ?";
                                $stmt = $conn->prepare($sql);   
                                $stmt->bind_param('i', $admin_id);
                                $stmt->execute();
                                $result = $stmt->get_result();
                                $admin = $result->fetch_assoc();
                                echo $admin['Login'];
                                ?>    
                               </small>
                            <?php else: ?>
                                <p>Ответа пока нет.</p>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>У вас нет тикетов.</p>
    <?php endif; ?>
</div>
<script src="scripts/load.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
