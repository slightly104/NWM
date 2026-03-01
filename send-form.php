<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json; charset=UTF-8");

function formatRussianPhone($phone) {
    $cleaned = preg_replace('/[^\d+]/', '', $phone);

    if (!preg_match('/^(\+7|8)/', $cleaned)) {
        return false;
    }

    if (strpos($cleaned, '8') === 0) {
        $cleaned = '+7' . substr($cleaned, 1);
    }

    $digitsOnly = ltrim($cleaned, '+');

    if (strlen($digitsOnly) !== 11) {
        return false;
    }

    return '+7 (' . substr($digitsOnly, 1, 3) . ') ' .
           substr($digitsOnly, 4, 3) . '-' .
           substr($digitsOnly, 7, 2) . '-' .
           substr($digitsOnly, 9, 2);
}

$response = ['success' => false, 'message' => ''];

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Недопустимый метод запроса');
    }

    $name = htmlspecialchars($_POST['name'] ?? '');
    $phone = htmlspecialchars($_POST['phone'] ?? '');
    $message = htmlspecialchars($_POST['message'] ?? '');

    if (empty($name) || empty($phone)) {
        throw new Exception('Заполните обязательные поля');
    }
    if (mb_strlen(trim($name)) < 2) {
        throw new Exception('Имя должно содержать минимум 2 символа');
    }

    $formattedPhone = formatRussianPhone($phone);
    if (!$formattedPhone) {
        throw new Exception('Введите корректный номер телефона (пример: +7 (999) 123-45-67)');
    }

    $to = 'info@northwestmedia.ru';
    $subject = 'Новая заявка с сайта NorthWest Media';
    $subject = mb_encode_mimeheader($subject, 'UTF-8', 'B');
    $from = 'noreply@northwestmedia.ru';

    $body = "Поступила новая заявка с сайта:\n\n";
    $body .= "Имя: $name\n";
    $body .= "Телефон: $formattedPhone\n";
    $body .= "Сообщение: $message\n";

    $headers = "From: $from\r\n";
    $headers .= "Reply-To: $from\r\n";
    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

    if (mail($to, $subject, $body, $headers)) {
        $response['success'] = true;
        $response['message'] = 'Заявка отправлена!';
    } else {
        throw new Exception('Ошибка отправки письма');
    }
} catch (Exception $e) {
    $response['message'] = $e->getMessage();
}

echo json_encode($response, JSON_UNESCAPED_UNICODE);
?>
