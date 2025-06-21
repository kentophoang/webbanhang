<?php
// Luôn bắt đầu session ở file đầu vào duy nhất này.
session_start();

/**
 * CẢI TIẾN: Autoloader phiên bản mới, rõ ràng và dễ bảo trì hơn.
 * Nó sẽ tự động nạp các file Controller, Model, và Helper khi chúng được gọi.
 */
spl_autoload_register(function ($className) {
    $file = null;

    // Kiểm tra xem tên class có chứa chữ 'Controller' không
    if (strpos($className, 'Controller') !== false) {
        $file = 'app/controllers/' . $className . '.php';
    } 
    // Nếu không, kiểm tra xem có phải là 'Model' không
    elseif (strpos($className, 'Model') !== false) {
        $file = 'app/models/' . $className . '.php';
    } 
    // Nếu không, kiểm tra xem có phải là 'Helper' không
    elseif (strpos($className, 'Helper') !== false) {
        $file = 'app/helpers/' . $className . '.php';
    }

    // Nếu tìm thấy đường dẫn và file tồn tại, hãy nạp nó vào
    if ($file && file_exists($file)) {
        require_once $file;
    }
});

// Phân tích URL
$url = $_GET['url'] ?? '';
$url = rtrim($url, '/');
$url = filter_var($url, FILTER_SANITIZE_URL);
$url = explode('/', $url);

// Xác định Controller, Action và Tham số
// Nếu URL trống, mặc định sẽ gọi đến ProductController
$controllerName = isset($url[0]) && !empty($url[0]) ? ucfirst($url[0]) . 'Controller' : 'ProductController';

// Nếu không có action, mặc định là 'index'
$action = isset($url[1]) && !empty($url[1]) ? $url[1] : 'index';

// Các phần còn lại của URL là tham số
$params = array_slice($url, 2);

// Kiểm tra sự tồn tại của Controller và Action
if (class_exists($controllerName)) {
    $controller = new $controllerName();

    if (method_exists($controller, $action)) {
        // Gọi action với các tham số tương ứng
        call_user_func_array([$controller, $action], $params);
    } else {
        // Xử lý không tìm thấy action
        die('Action not found: ' . htmlspecialchars($action));
    }
} else {
    // Xử lý không tìm thấy controller
    die('Controller not found: ' . htmlspecialchars($controllerName));
}