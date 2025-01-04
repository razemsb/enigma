-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Хост: localhost
-- Время создания: Янв 04 2025 г., 10:38
-- Версия сервера: 5.7.24
-- Версия PHP: 7.4.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `enigma-db`
--

-- --------------------------------------------------------

--
-- Структура таблицы `admin`
--

CREATE TABLE `admin` (
  `ID` int(11) NOT NULL,
  `id_admin` int(2) NOT NULL,
  `login_admin` varchar(20) NOT NULL,
  `password_admin` varchar(60) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `admin`
--

INSERT INTO `admin` (`ID`, `id_admin`, `login_admin`, `password_admin`) VALUES
(1, 1, 'razemsb', '$2y$10$xjL5cq2BGIOYVpad75nJ0el5AGtEyA/rRweZTlAQpNcwyoiOh3zBi'),
(2, 7, '2', '$2y$10$xjL5cq2BGIOYVpad75nJ0el5AGtEyA/rRweZTlAQpNcwyoiOh3zBi'),
(3, 8, 'admin_beta', '$2y$10$xjL5cq2BGIOYVpad75nJ0el5AGtEyA/rRweZTlAQpNcwyoiOh3zBi');

-- --------------------------------------------------------

--
-- Структура таблицы `admin_messages`
--

CREATE TABLE `admin_messages` (
  `message_id` int(11) NOT NULL,
  `sender_admin_id` int(11) NOT NULL,
  `receiver_admin_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `sent_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `admin_messages`
--

INSERT INTO `admin_messages` (`message_id`, `sender_admin_id`, `receiver_admin_id`, `message`, `sent_at`) VALUES
(26, 11, 6, 'йоу\n', '2024-12-26 17:36:04'),
(27, 11, 6, 'йоу\n', '2024-12-26 17:40:32'),
(28, 11, 6, 'йоу', '2024-12-26 18:18:34'),
(29, 11, 6, 'йоу', '2024-12-27 19:50:19'),
(30, 11, 6, 'йоу\n', '2025-01-03 16:41:39');

-- --------------------------------------------------------

--
-- Структура таблицы `categories`
--

CREATE TABLE `categories` (
  `ID` int(11) NOT NULL,
  `Name` text NOT NULL,
  `Description` text NOT NULL,
  `category` varchar(30) NOT NULL,
  `Image` varchar(50) NOT NULL,
  `price` int(6) NOT NULL,
  `status` enum('active','note_active') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `categories`
--

INSERT INTO `categories` (`ID`, `Name`, `Description`, `category`, `Image`, `price`, `status`) VALUES
(13, 'Разработка сайтов на заказ', 'Полная разработка сайтов под ключ, включая дизайн, разработку и настройку функционала.', 'Frontend', 'icons/web_devop.png', 20000, 'active'),
(14, 'Создание лендингов', 'Разработка одностраничных сайтов для продвижения продуктов и услуг.', 'Frontend', 'icons/web_lending.jpg', 10000, 'active'),
(15, 'Разработка корпоративных сайтов', 'Создание сайтов для компаний с уникальным дизайном и функционалом.', 'Frontend', 'icons/web_corparate.avif', 25000, 'active'),
(16, 'Интернет-магазины', 'Создание интернет-магазинов с интеграцией с платежными системами и складским учетом.', 'Frontend', 'icons/web_magazine.jpg', 30000, 'active'),
(17, 'Разработка блогов и порталов', 'Создание сайтов для блогеров и медиа-платформ с удобными инструментами для контент-менеджмента.', 'Frontend', 'icons/web_blog.png', 15000, 'active'),
(18, 'Техническая поддержка сайтов', 'Оказание услуг по поддержке и обслуживанию веб-сайтов: обновления, багфиксы, оптимизация.', 'Frontend', 'icons/web_help.jpg', 5000, 'active'),
(19, 'SEO-оптимизация сайтов', 'Услуги по продвижению сайтов в поисковых системах, улучшение видимости и трафика.', 'Frontend', 'icons/web_seo.webp', 12000, 'active'),
(20, 'Веб-дизайн и UI/UX', 'Разработка уникальных и удобных дизайнов для сайтов и приложений, улучшение пользовательского интерфейса.', 'Frontend', 'icons/web_uiux.png', 18000, 'active'),
(21, 'Мобильные версии сайтов', 'Адаптация сайтов под мобильные устройства для улучшения пользовательского опыта и SEO.', 'Frontend', 'icons/web_mobile.jpg', 8000, 'active'),
(22, 'Ремонт и доработка сайтов', 'Корректировка и обновление существующих сайтов: улучшение дизайна, добавление нового функционала.', 'Frontend', 'icons/web_repair.jpg', 12000, 'active'),
(23, 'Разработка REST API', 'Создание REST API для интеграции различных приложений и сервисов.', 'Backend', 'icons/rest_api.jpeg', 15000, 'active'),
(24, 'Оптимизация серверов', 'Настройка и оптимизация серверов для увеличения производительности и безопасности.', 'Backend', 'icons/opt_server.webp', 20000, 'active'),
(25, 'Работа с базами данных', 'Проектирование, настройка и оптимизация баз данных для высоких нагрузок.', 'Backend', 'icons/database.webp', 18000, 'active'),
(26, 'Разработка микросервисов', 'Создание микросервисной архитектуры для сложных приложений.', 'Backend', 'icons/microser.jpg', 25000, 'active'),
(27, 'Облачные решения', 'Настройка облачных платформ, включая AWS, Google Cloud и Azure.', 'Backend', 'icons/cloud.jpg', 22000, 'active');

-- --------------------------------------------------------

--
-- Структура таблицы `chat`
--

CREATE TABLE `chat` (
  `id` int(11) NOT NULL,
  `user_id` int(5) NOT NULL,
  `admin_id` int(5) NOT NULL,
  `message` varchar(255) NOT NULL,
  `sender` varchar(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `chat`
--

INSERT INTO `chat` (`id`, `user_id`, `admin_id`, `message`, `sender`) VALUES
(1, 0, 1, 'гойда', '1'),
(2, 0, 1, 'гойда', '1'),
(3, 0, 1, 'гойда', '1'),
(4, 0, 1, 'соуа2', '1'),
(5, 0, 1, 'гойда славяне', '1');

-- --------------------------------------------------------

--
-- Структура таблицы `orders`
--

CREATE TABLE `orders` (
  `ID` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `total_price` int(11) NOT NULL,
  `products` int(11) NOT NULL,
  `order_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `orders`
--

INSERT INTO `orders` (`ID`, `user_id`, `total_price`, `products`, `order_date`) VALUES
(1, 11, 15000, 17, '2025-01-04 10:16:51'),
(2, 11, 25000, 15, '2025-01-04 10:16:51');

-- --------------------------------------------------------

--
-- Структура таблицы `support_replies`
--

CREATE TABLE `support_replies` (
  `reply_id` int(11) NOT NULL,
  `ticket_id` int(11) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `reply_message` text NOT NULL,
  `reply_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `support_replies`
--

INSERT INTO `support_replies` (`reply_id`, `ticket_id`, `admin_id`, `reply_message`, `reply_date`) VALUES
(1, 1, 11, 'йоу!!', '2024-12-30 12:29:11'),
(2, 2, 11, 'гойда славяне', '2025-01-02 06:36:05'),
(3, 2, 11, 'гойда славяне', '2025-01-02 06:36:39'),
(4, 2, 11, 'гойда славяне', '2025-01-02 06:36:50'),
(5, 2, 11, 'гойда славяне', '2025-01-02 06:37:16'),
(6, 2, 11, 'гойда славяне', '2025-01-02 06:37:46'),
(7, 2, 11, 'гойда славяне', '2025-01-02 06:37:57'),
(8, 2, 11, 'гойда славяне', '2025-01-02 06:38:19');

-- --------------------------------------------------------

--
-- Структура таблицы `support_tickets`
--

CREATE TABLE `support_tickets` (
  `ticket_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `status` enum('open','closed') DEFAULT 'open',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `support_tickets`
--

INSERT INTO `support_tickets` (`ticket_id`, `user_id`, `message`, `status`, `created_at`, `updated_at`) VALUES
(1, 11, 'йоу', 'closed', '2024-12-30 12:28:58', '2024-12-30 12:29:11'),
(2, 11, 'гойда', 'closed', '2025-01-02 06:27:36', '2025-01-02 06:36:05');

-- --------------------------------------------------------

--
-- Структура таблицы `tickets`
--

CREATE TABLE `tickets` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `status` enum('open','in_progress','closed') DEFAULT 'open',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `taken_by` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `tickets`
--

INSERT INTO `tickets` (`id`, `user_id`, `title`, `description`, `status`, `created_at`, `taken_by`) VALUES
(1, 3, 'uiouiou', 'uiouiuoi', 'in_progress', '2024-12-12 16:17:21', 'razemsb');

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `ID` int(11) NOT NULL,
  `Login` varchar(25) NOT NULL,
  `Password` varchar(60) NOT NULL,
  `Email` varchar(50) NOT NULL,
  `avatar` varchar(100) NOT NULL,
  `orders_count` int(11) NOT NULL,
  `date_reg` datetime NOT NULL,
  `is_admin` enum('user','admin','system_admin') NOT NULL,
  `is_active` enum('active','banned') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`ID`, `Login`, `Password`, `Email`, `avatar`, `orders_count`, `date_reg`, `is_admin`, `is_active`) VALUES
(3, 'razer', '$2y$10$AimgRe7hrXDEp1O1g1aJHejPBmZImDQw9GIkzivAOdMtYGOwiyv1C', 'enigma.none@yandex.ru', 'uploads/basic_avatar.webp', 0, '2024-12-05 13:30:38', 'user', 'active'),
(5, '111', '$2y$10$lFu0Cp0dBDpNlIhwWoktn.eIg3GhUdczxdrczgUCLhtA.K7Nr4TFu', '111@gmail.com', 'uploads/basic_avatar.webp', 0, '2024-12-05 15:53:27', 'user', 'active'),
(6, 'minotaur', '$2y$10$vtenF0Y7rRRJZvmIxPzXFedy47OgD1ELMHxkCJcKWOWZ0eDDFrD5a', 'kamikaze_dolbaeb@gmail.com', 'uploads/avatar_6751badd22dc16.22169590.png', 0, '2024-12-05 17:34:21', 'admin', 'active'),
(7, '2', '$2y$10$orw2obV/.p13Ybg7dYlIKeK2G8eG8F.VxQkcHL1Mfbvz9peiLZaOm', 'maxim1xxx678686876687363@gmail.com', 'uploads/basic_avatar.webp', 0, '2024-12-09 13:37:12', 'admin', 'active'),
(8, 'admin_beta', '$2y$10$rxXLmOboyP4HH7P2X16aPenPOYH9QiGSjfePUvgkGa222YiRd5DOW', 'enigma.none6@yandex.ru', 'uploads/basic_avatar.webp', 0, '2024-12-10 22:12:39', 'admin', 'active'),
(9, 'hoi', '$2y$10$Og5O2BIkuNXYVNvceHdjr.a1Nte/Ugi26jpTMUb8bnP2O6qPZ8uCi', 'yijojiijiojiojij@gmail.com', 'uploads/basic_avatar.webp', 0, '2024-12-12 14:41:29', 'user', 'active'),
(10, 'administrator', '$2y$10$EsWUTVPS2gMU0I.EM70SzumTwJ2mTgc80TdeCrrmtkGMddRULOhRW', 'administrator@gmail.com', 'uploads/avatar_67632d09a15607.44720468.jpeg', 0, '2024-12-18 23:11:49', 'admin', 'active'),
(11, 'razemsb', '$2y$10$vtenF0Y7rRRJZvmIxPzXFedy47OgD1ELMHxkCJcKWOWZ0eDDFrD5a', 'maxim1xxx363@gmail.com', 'uploads/avatar_676453ff7a25b3.46530517.jpeg', 1, '2024-11-29 12:15:55', 'system_admin', 'active'),
(40, 'ADMIN', '$2y$10$g515VWTj3UCud50YOZ27RedlOuPhyYZNv50cJplqYuCNxtMEsYjJe', 'zhaba@gmail.com', 'uploads/basic_avatar.webp', 0, '2024-12-05 14:55:06', 'user', 'active'),
(41, 'razems9999999b', '$2y$10$nxMDdO1Op6uA5SegbsFnreXXdUn0hbBfo3Jg.hJ.Ls919yFjfx0Iq', 'maxim1xxx3693@gmail.com', 'uploads/basic_avatar.webp', 0, '2024-12-26 17:51:40', 'user', 'active'),
(42, 'punknotdead', '$2y$10$c3viLdBUui/pg.Ft3q5DNefEcYFvQPOKY.fiiC/sMSkjANj9cEGb.', 'onijnknjk@gmail.com', 'uploads/basic_avatar.webp', 0, '2024-12-26 21:44:00', 'user', 'active');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`ID`);

--
-- Индексы таблицы `admin_messages`
--
ALTER TABLE `admin_messages`
  ADD PRIMARY KEY (`message_id`),
  ADD KEY `sender_admin_id` (`sender_admin_id`),
  ADD KEY `receiver_admin_id` (`receiver_admin_id`);

--
-- Индексы таблицы `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`ID`);

--
-- Индексы таблицы `chat`
--
ALTER TABLE `chat`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`ID`);

--
-- Индексы таблицы `support_replies`
--
ALTER TABLE `support_replies`
  ADD PRIMARY KEY (`reply_id`),
  ADD KEY `ticket_id` (`ticket_id`),
  ADD KEY `admin_id` (`admin_id`);

--
-- Индексы таблицы `support_tickets`
--
ALTER TABLE `support_tickets`
  ADD PRIMARY KEY (`ticket_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Индексы таблицы `tickets`
--
ALTER TABLE `tickets`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `admin`
--
ALTER TABLE `admin`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `admin_messages`
--
ALTER TABLE `admin_messages`
  MODIFY `message_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT для таблицы `categories`
--
ALTER TABLE `categories`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT для таблицы `chat`
--
ALTER TABLE `chat`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT для таблицы `orders`
--
ALTER TABLE `orders`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `support_replies`
--
ALTER TABLE `support_replies`
  MODIFY `reply_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT для таблицы `support_tickets`
--
ALTER TABLE `support_tickets`
  MODIFY `ticket_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `tickets`
--
ALTER TABLE `tickets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `admin_messages`
--
ALTER TABLE `admin_messages`
  ADD CONSTRAINT `admin_messages_ibfk_1` FOREIGN KEY (`sender_admin_id`) REFERENCES `users` (`ID`),
  ADD CONSTRAINT `admin_messages_ibfk_2` FOREIGN KEY (`receiver_admin_id`) REFERENCES `users` (`ID`);

--
-- Ограничения внешнего ключа таблицы `support_replies`
--
ALTER TABLE `support_replies`
  ADD CONSTRAINT `support_replies_ibfk_1` FOREIGN KEY (`ticket_id`) REFERENCES `support_tickets` (`ticket_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `support_replies_ibfk_2` FOREIGN KEY (`admin_id`) REFERENCES `users` (`ID`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `support_tickets`
--
ALTER TABLE `support_tickets`
  ADD CONSTRAINT `support_tickets_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
