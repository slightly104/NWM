<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

// Инициализация ответа
$response = ['success' => false, 'message' => ''];

// Получение данных формы
$name = htmlspecialchars($_POST['name'] ?? '');
$phone = htmlspecialchars($_POST['phone'] ?? '');
$message = htmlspecialchars($_POST['message'] ?? '');

// Валидация
if (empty($name) || empty($phone)) {
    throw new Exception('Заполните обязательные поля');
}

// Настройки почты
$to = 'info@northwestmedia.ru';
$subject = 'Новая заявка с сайта NorthWest Media';
$subject = mb_encode_mimeheader($subject, 'UTF-8', 'B');
$from = 'noreply@northwestmedia.ru';

// Тело письма
$body = "Поступила новая заявка с сайта:\n\n";
$body .= "Имя: $name\n";
$body .= "Телефон: $phone\n";
$body .= "Сообщение: $message\n";

// Заголовки
$headers = "From: $from\r\n";
$headers .= "Reply-To: $from\r\n";
$headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
    
// Отправка письма
if (mail($to, $subject, $body, $headers)) {
    $response['success'] = true;
    $response['message'] = 'Заявка отправлена!';
} else {
    throw new Exception('Ошибка отправки письма');
    }

echo json_encode($response, JSON_UNESCAPED_UNICODE);
?>
