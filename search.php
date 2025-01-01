<?php
require_once 'database/database.php';

if (isset($_GET['search']) && !empty($_GET['search'])) {
    $searchTerm = $conn->real_escape_string($_GET['search']);
    $query = "SELECT * FROM categories WHERE Name LIKE '%$searchTerm%' AND status = 'active'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
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
    } else {
        echo '<div class="col-12 text-center text-muted">Ничего не найдено</div>';
    }
}
?>
