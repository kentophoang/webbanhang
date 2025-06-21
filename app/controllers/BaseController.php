<?php
// Nạp file cấu hình database một lần ở đây
require_once 'app/config/database.php';
require_once 'app/helpers/SessionHelper.php';

class BaseController {
    protected $db;

    public function __construct() {
        $this->db = (new Database())->getConnection();
    }

    // Hàm này giúp nạp các model một cách dễ dàng
    protected function loadModel($modelName) {
        // Autoloader trong index.php sẽ tự động nạp file model
        // khi chúng ta gọi 'new $modelName()'
        return new $modelName($this->db);
    }
}