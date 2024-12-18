<?php
$user = "root";
$password = "root";
$host = "localhost";
$db = "enigma-db";

$conn = new mysqli($host, $user, $password, $db);

if ($conn->connect_error) {
    $_SESSION['error'] = 'db_error, ' . $conn->connect_error;
    header('Location: error.php');
    die("Ошибка соединения: ".$conn->connect_error);
}
?>