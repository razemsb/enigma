<?php
if(isset($_SESSION['adminin'])) {
    header('Location: ../index.php');
    exit();
}
$user_login = $_SESSION['user_login'];
function writeToLog($message , $user_login){
    $filename = 'logs.txt';
    date_default_timezone_set('Europe/Moscow');
    $timeStamp = date('Y-m-d H:i:s');
    switch($message){
        case "авторизация успех":
            $message  = "Успешная авторизация";
            break;
        case "авторизация неудача":
            $message  = "Неудачная авторизация";
            break;
        case "Регистрация успешна":
            $message  = "Успешная регистрация пользователя";
            break;
        case "Регистрация провалена":
            $message  = "Неудачная регистрация пользователя";
            break;
        case "регистрация провал логин":
            $message  = "Неверный логин";
            break;
        case "загрузка фото неудача":
            $message = "Произошла ошибка при попытке загрузки фотографии";
            break;
        case "загрузка фото успех":
            $message = "Фотография успешно загружена";
            break;
        case "Ошибка подключения к бд":
            $message = "Ошибка соединения к бд";
            break;
        case "Успешное подключение к бд":
            $message = "Успешное соединение с базой данных";
            break;
        case "Выход с аккаунта":
            $message = "Завершил свою сессию";
            break;
        case "удаление фото удача":
            $message = "Завершил свою сессию";
            break;
        case "авторизация успех админ":
            $message = "Администратор авторизовался";
            break;
        case "удаление пользователя удачно":
            $message = "Удаление пользователя удачно";
            break; 
        case "восстановление картинки удачно":
            $message = "Восстановленна картинка";
            break; 
        case "Попытка входа в заблокированный аккаунт":
            $message = "Попытка входа в заблокированный аккаунт";
        default:
            
    }
    $message = "[$timeStamp] - '$user_login' - " . $message . PHP_EOL;
    file_put_contents($filename, $message , FILE_APPEND);
}