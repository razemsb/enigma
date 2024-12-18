<?php
require_once('database/database.php');
if (isset($_GET['search'])) {
    $search = $_GET['search'];
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    $query = "SELECT * FROM categories WHERE name LIKE '%$search%'";
    $result = $conn->query($query);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<p>" . htmlspecialchars($row['Name']) . "</p>";
        }
    } else {
        echo "<p>Ничего не найдено.</p>";
    }
    $conn->close();
}
?>
