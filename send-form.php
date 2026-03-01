<?php
// Настройки
$to = 'info@northwestmedia.ru';
$subject = 'Новая заявка с сайта NorthWest Media';
$from = 'info@northwestmedia.ru';

// Получаем данные из формы
$name = htmlspecialchars($_POST['name'] ?? '');
$phone = htmlspecialchars($_POST['phone'] ?? '');
$message = htmlspecialchars($_POST['message'] ?? '');

// Формируем тело письма
$body = "Поступила новая заявка с сайта:\n\n";
$body .= "Имя: $name\n";
$body .= "Телефон: $phone\n";
$body .= "Сообщение: $message\n";

// Заголовки
$headers = "From: $from\r\n";
$headers .= "Reply-To: $from\r\n";
$headers .= "Content-Type: text/plain; charset=utf-8\r\n";

// Отправляем письмо
if (mail($to, $subject, $body, $headers)) {
    echo json_encode(['success' => true, 'message' => 'Заявка отправлена!']);
} else {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Ошибка отправки. Попробуйте позже.']);
}
?>
