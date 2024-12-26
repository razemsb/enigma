<?php
require_once 'database/database.php';
session_start();
if(!isset($_SESSION['user_auth'])) {
    header('Location: auth/login.html');
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
    <title>Enigma | Каталог</title>
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
    <h1 class="text-center mb-4 fw-bold text-primary">Каталог товаров</h1>
    <form method="GET" action="" class="mb-4">
        <div class="row">
            <div class="col-md-6 offset-md-3">
            <div class="d-flex align-items-center">
    <div class="dropdown me-2">
        <button class="btn btn-secondary dropdown-toggle align-items-center text-decoration-none" type="button" id="categoryDropdown" data-bs-toggle="dropdown" aria-expanded="false">
            Выберите категорию
        </button>
        <ul class="dropdown-menu" aria-labelledby="categoryDropdown">
            <li><a class="dropdown-item" href="catalog">Все категории</a></li>
            <?php
            $catQuery = "SELECT DISTINCT category FROM categories";
            $catResult = $conn->query($catQuery);
            while ($cat = $catResult->fetch_assoc()) {
                $selected = (isset($_GET['category']) && $_GET['category'] == $cat['category']) ? 'active' : '';
                echo "<li><a class='dropdown-item $selected' href='catalog?category=" . htmlspecialchars($cat['category']) . "'>" . htmlspecialchars($cat['category']) . "</a></li>";
            }
            ?>
        </ul>
    </div>
    <!--
    <input type="text" id="search" placeholder="Поиск по названию..." class="form-control my-3" name="search">
     <div id="results"></div>
        -->
</div>
<!--
<?php if(isset($_GET['search']) && !empty($_GET['search'])): ?>
    <a href="catalog.php" class="btn btn-danger">Сбросить поиск</a>
<?php endif; ?>
-->

            </div>
        </div>
    </form>
    <div class="row">
        <?php
        if(isset($_GET['category']) && !empty($_GET['category'])) {
            $selectedCategory = $conn->real_escape_string($_GET['category']);
            $query = "SELECT * FROM categories WHERE category = '$selectedCategory' AND status = 'active'";
            $result = $conn->query($query);
            if ($result->num_rows > 0) {
                $perpage = 6;
                $page = (isset($_GET['page'])) ? (int)$_GET['page'] : 1;
                $start = ($page - 1) * $perpage;
                $query = "SELECT * FROM categories WHERE category = '$selectedCategory' AND status = 'active' LIMIT $start, $perpage";
                $result = $conn->query($query);
                while ($row = $result->fetch_assoc()) {
                    ?>
                    <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
                        <div class="card h-100 shadow-sm">
                            <img src="<?= htmlspecialchars($row['Image']) ?>" style="width: 100%; max-height: 200px; border-top-left-radius: 12px; border-top-right-radius: 16px" alt="<?= htmlspecialchars($row['Name']) ?>">
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title"><?= htmlspecialchars($row['Name']) ?></h5>
                                <p class="card-text"><?= htmlspecialchars($row['Description']) ?></p>
                                <p class="card-text fw-bold">Цена: <?= number_format($row['price'], 0, ',', ' ') ?> ₽</p>
                                <a href="product.php?id=<?= $row['ID'] ?>" class="btn btn-primary mt-auto">Подробнее</a>
                            </div>
                        </div>
                    </div>
                    <?php
                }
                $total = $conn->query("SELECT COUNT(*) FROM categories WHERE category = '$selectedCategory' AND status = 'active'")->fetch_assoc()['COUNT(*)'];
                $pages = ceil($total / $perpage);
                if ($pages > 1) {
                    echo '<div class="col-12 text-center my-4">';
                    echo '<nav aria-label="Page navigation example">';
                    echo '<ul class="pagination justify-content-center">';
                    for ($i = 1; $i <= $pages; $i++) {
                        $active = ($i == $page) ? 'active' : '';
                        echo '<li class="page-item ' . $active . '"><a class="page-link" href="catalog.php?category=' . htmlspecialchars($selectedCategory) . '&page=' . $i . '">' . $i . '</a></li>';
                    }
                    echo '</ul>';
                    echo '</nav>';
                    echo '</div>';
                }

            } else {
                echo '<div class="col-12 text-center text-muted">Нет услуг в этой категории</div>';
            }
        } else {
            echo '<div class="col-12 text-center text-muted">Пожалуйста, выберите категорию для просмотра услуг</div>';
        }
        ?>
    </div>
</div>
<!--<script>
    $(document).ready(function() {
    $('#search').on('input', function() {
        var query = $(this).val();
        if (query.length > 2) { 
            $.ajax({
                url: 'search.php',
                method: 'GET',
                data: { search: query },
                success: function(response) {
                    $('#results').html(response);
                }
            });
        } else {
            $('#results').empty(); 
        }
    });
});

</script>-->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="scripts/load.js"></script>
<script src="scripts/search.js"></script>
</body>
</html>
