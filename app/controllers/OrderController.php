<?php
require_once 'app/controllers/BaseController.php';

class OrderController extends BaseController {
    
    public function __construct() {
        parent::__construct();
        // Chỉ Admin mới được truy cập controller này
        if (!SessionHelper::isAdmin()) {
            header('Location: /webbanhang/product');
            exit();
        }
    }

    public function index()
    {
        // Logic để lấy danh sách đơn hàng sẽ được thêm vào đây trong tương lai
        include 'app/views/order/index.php';
    }
}