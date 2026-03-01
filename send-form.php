<?php

function validatePhone($phone) {
    // Удаляем все нецифровые символы (кроме + в начале)
    $cleaned = preg_replace('/[^\d+]/', '', $phone);
    
    // Проверяем, что номер начинается с +7 или 8
    if (!preg_match('/^(\+7|8)/', $cleaned)) {
        return false;
    }
    
    // Заменяем 8 на +7 для единообразия
    if (strpos($cleaned, '8') === 0) {
        $cleaned = '+7' . substr($cleaned, 1);
    }
    
    // Убираем + для подсчёта цифр
    $digitsOnly = ltrim($cleaned, '+');
    
    // Проверяем длину (11 цифр для РФ: 7 + 10 цифр)
    if (strlen($digitsOnly) !== 11) {
        return false;
    }  
    return $cleaned;
}

// Отправка формы
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
$validatedPhone = validatePhone($phone);
if (!$validatedPhone) {
    throw new Exception('Введите корректный номер телефона (пример: +7 (999) 123-45-67)');
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
