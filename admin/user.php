<?php
require_once '../database/database.php';
session_start();
if (!$_SESSION['admin_auth'] === true && !$_SESSION['admin_auth_pass'] === true) {
    header('Location: ../index.php');
    exit();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/admin.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Управление пользователями</title>
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
                        <a class="me-3 py-2 text-dark text-decoration-none" href="admin_panel">Назад</a>
                        <a class="me-3 py-2 text-dark text-decoration-none" href="../index">Главная</a>
                        <a class="me-3 py-2 text-dark text-decoration-none" href="../catalog">Каталог</a>
                        <?php if(isset($_SESSION['user_auth'])): ?>
                            <a class="me-3 py-2 text-dark text-decoration-none" href="../profile">Профиль</a>
                            <a class="me-3 py-2 text-dark text-decoration-none" href="logout_admin">Выход</a>
                        <?php else: ?>   
                        <a class="me-3 py-2 text-dark text-decoration-none" href="../auth/register">Регистрация</a>
                        <a class="me-3 py-2 text-dark text-decoration-none" href="../auth/login">Вход</a>
                        <?php endif; ?>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</header>
<div class="container mt-5">
    <?php
    if (isset($_GET['id'])) {
        if(!$_SESSION['system_admin'] === true) {
        $query = "SELECT * FROM users WHERE ID = ? AND is_admin != 'admin' AND is_admin != 'system_admin'";
        }else {
        $query = "SELECT * FROM users WHERE ID = ?";
        }
        $stmt = $conn->prepare($query);
        $stmt->bind_param('i', $_GET['id']);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            ?>
            <div class="card">
                <div class="card-header text-center text-primary fw-bold">
                    Информация о пользователе
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <tr>
                            <img src="<?php echo '../' . htmlspecialchars($user['avatar']); ?>" alt="Avatar" class="rounded-circle mt-1 mb-1" style="width: 100px; height: 100px; object-fit: cover;">
                            <?php if ($user['is_admin'] == 'system_admin'):?>

                            <?php else: ?>
                            <a class="btn btn-danger ms-4" href="delete_image.php?id=<?= urlencode($user['ID']) ?>">Удалить аватар</a>
                            <?php endif; ?>
                        </tr>
                        <tr>
                            <th>ID</th>
                            <td><?= htmlspecialchars($user['ID']) ?></td>
                        </tr>
                        <tr>
                            <th>Логин</th>
                            <td><?= htmlspecialchars($user['Login']) ?></td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td><?= htmlspecialchars($user['Email']) ?></td>
                        </tr>
                        <tr>
                            <th>Дата регистрации</th>
                            <td><?= htmlspecialchars($user['date_reg']) ?></td>
                        </tr>
                        <tr>
                            <th>Количество заказов</th>
                            <td><?= htmlspecialchars($user['orders_count']) ?></td>
                        </tr>
                        <tr>
                            <th>Активен</th>
                            <td><?= $user['is_active'] == 'active' ? 'Да' : 'Нет' ?></td>
                        </tr>
                        <tr>
                            <th>Админ</th>
                            <?php if ($user['is_admin'] !== 'system_admin'):?>
                            <td><?= $user['is_admin'] == 'admin' ? 'Администратор' : 'Пользователь' ?></td>
                            <?php else: ?>
                            <td>Системный администратор</td>
                            <?php endif; ?>
                        </tr>
                        <?php if ($user['is_admin'] == 'system_admin'):?>
                        <?php else: ?>
                        <tr>
                            <th>Действия</th>
                            <td>
                                <?php if ($user['is_active'] == 'banned') { ?>
                                    <a href="recovery_user?id=<?= urlencode($user['ID']) ?>" class="btn btn-success">Разбанить</a>
                                <?php } else { ?>
                                    <a href="ban_user?id=<?= urlencode($user['ID']) ?>" class="btn btn-danger">Забанить</a>
                                <?php } ?>
                            </td>
                        </tr>
                        <?php endif; ?>
                    </table>
                    <?php if ($user['is_admin'] == '1'):?>
                    <a href="admin_panel?section=admin" class="btn btn-primary btn-back">Назад к администраторам</a>
                    <?php else: ?>
                    <a href="admin_panel?section=users" class="btn btn-primary btn-back">Назад к пользователям</a>
                    <?php endif; ?>
                </div>
            </div>
            <?php
        } else {
            echo '<div class="alert alert-danger" role="alert">Пользователь не найден.</div>';
            echo '<a href="admin_panel?section=users" class="btn btn-primary btn-back">Назад к пользователям</a>';
        }
    }
    ?>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="scripts/section.js"></script>
</body>
</html>