<?php
session_start();    
include '../database/database.php';

$sql = "SELECT ID, Login, Avatar FROM users WHERE is_admin = 'admin' OR is_admin = 'system_admin' AND ID != " . $_SESSION['user_id'];
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        echo '<li onclick="setContact(' . $row['ID'] . ', \'' . $row['Login'] . '\')">'.'<img src="'.'../'. htmlspecialchars($row['Avatar']) . '" alt="Avatar" class="rounded-circle mt-1 mb-1" style="width: 50px; height: 50px; object-fit: cover;">' .'<p class="me-2">'. htmlspecialchars($row['Login']) . '</p></li>';
    }
} else {
    echo '<p>Нет доступных контактов.</p>';
}
?>
