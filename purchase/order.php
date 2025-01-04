<?php 
session_start();
require_once '../database/database.php';
require_once '../tcpdf/tcpdf.php';

if (!isset($_SESSION['user_auth'])) {
    header('Location: auth/login');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['total_price']) && isset($_SESSION['cart']) && isset($_POST['name']) && isset($_POST['phone']) && isset($_POST['email'])) {
    $login = $_SESSION['user_login'];
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    
    if (count($_SESSION['cart']) == 0) {
        header('Location: ../cart');
        exit();
    }

    $user_id = $_SESSION['user_id'];
    $total_price = $_POST['total_price'];
    $products = array_keys($_SESSION['cart']);
    $products_list = implode(', ', $products);

    try {

        $conn->begin_transaction();
        $pdf = new TCPDF();
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Enigma-Hub');
        $pdf->SetTitle('Чек заказа');
        $pdf->SetMargins(15, 15, 15);
        $pdf->AddPage();
        $pdf->SetFont('dejavusans', 'B', 18);
        $pdf->SetTextColor(50, 50, 50);
        $pdf->Cell(0, 10, 'Чек на заказ №' . $order_id, 0, 1, 'C');
        $pdf->Ln(5);
        $pdf->SetFont('dejavusans', '', 12);
        $pdf->SetTextColor(100, 100, 100);
        $pdf->MultiCell(0, 10, 'ООО "Enigma-Hub"', 0, 'C');
        $pdf->MultiCell(0, 10, 'Дата заказа: ' . date('d.m.Y H:i:s'), 0, 'C');
        $pdf->Ln(10);
        $pdf->SetFillColor(230, 230, 230);
        $pdf->SetFont('dejavusans', 'B', 14);
        $pdf->Cell(0, 10, 'Информация о клиенте', 0, 1, 'L', true);
        $pdf->Ln(2);
        $pdf->SetFont('dejavusans', '', 12);
        $pdf->MultiCell(0, 8, "Имя клиента: $name", 0, 'L');
        $pdf->MultiCell(0, 8, "Логин: $login", 0, 'L');
        $pdf->MultiCell(0, 8, "Телефон: $phone", 0, 'L');
        $pdf->MultiCell(0, 8, "Email: $email", 0, 'L');
        $pdf->Ln(10);
        $pdf->SetFont('dejavusans', 'B', 12);
        $pdf->SetFillColor(220, 220, 220);
        $pdf->Cell(80, 10, 'Название товара:', 1, 0, 'C', true);
        $pdf->Cell(40, 10, 'Цена', 1, 0, 'C', true);
        $pdf->Cell(40, 10, 'Количество', 1, 0, 'C', true);
        $pdf->Cell(30, 10, 'Итого', 1, 1, 'C', true);
        
        $total = 0;
        $pdf->SetFont('dejavusans', '', 12);
        $product_ids = implode(',', $products);
        $sql = "SELECT ID, Name, price FROM categories WHERE ID IN ($product_ids)";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $title = $row['Name'];
                $price = $row['price'];
                $quantity = 1; 
                $subtotal = $price * $quantity;
                $total += $subtotal;

                $pdf->Cell(80, 10, $title, 1, 0, 'L');
                $pdf->Cell(40, 10, "$price руб.", 1, 0, 'R');
                $pdf->Cell(40, 10, 1, 1, 0, 'C');
                $pdf->Cell(30, 10, "$subtotal руб.", 1, 1, 'R');
            }
        } else {
            $pdf->MultiCell(0, 10, 'Ошибка: не удалось получить данные о продуктах.', 0, 'C');
        }
        $pdf->Ln(10);
        $pdf->SetFont('dejavusans', 'B', 12);
        $pdf->Cell(160, 10, 'Итоговая сумма:', 0, 0, 'R');
        $pdf->SetTextColor(0, 100, 0);
        $pdf->Cell(30, 10, "$total руб.", 1, 1, 'R');
        $pdf->Ln(10);
        $pdf->SetTextColor(50, 50, 50);
        $pdf->SetFont('dejavusans', '', 12);
        $pdf->MultiCell(0, 10, 'Спасибо за ваш заказ!', 0, 'C');
        $pdf->Ln(5);
        $pdf->SetFont('dejavusans', 'I', 10);
        $pdf->MultiCell(0, 10, 'Этот чек является подтверждением вашего заказа.', 0, 'C');
        $pdf->Ln(15);
        $pdf->SetFont('dejavusans', 'B', 14);
        $pdf->Cell(0, 10, 'ООО "Enigma-Hub"', 0, 1, 'C');
        
        $sql = "INSERT INTO orders (user_id, total_price, products, Name, Phone, Email) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('iissss', $user_id, $total_price, $products_list, $name, $phone, $email);
        $stmt->execute();

        $order_id = $conn->insert_id;

        $file_name = "check_order_$order_id.pdf";
        $file_path = dirname(__DIR__) . '/temp/' . $file_name;
        $pdf->Output($file_path, 'F');

        $sql2 = "UPDATE users SET orders_count = orders_count + 1 WHERE ID = ?";
        $stmt = $conn->prepare($sql2);
        $stmt->bind_param('i', $user_id);
        $stmt->execute();

        unset($_SESSION['cart']);
        $conn->commit();

        header('Location: order_success.php?file=' . urlencode($file_path));
        exit();
    } catch (Exception $e) {
        $conn->rollback();
        error_log("Ошибка транзакции: " . $e->getMessage());
        header('Location: ../cart?error=transaction_failed');
        exit();
    }
}
?>
