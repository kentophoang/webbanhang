<?php
require_once 'app/controllers/BaseController.php';

class UserController extends BaseController {
    private $accountModel;

    public function __construct() {
        parent::__construct();
        $this->accountModel = $this->loadModel('AccountModel');

        // BẢO MẬT: Đảm bảo chỉ có Admin mới được truy cập controller này
        if (!SessionHelper::isAdmin()) {
            header('Location: /webbanhang');
            exit();
        }
    }

    /**
     * Hiển thị trang danh sách tất cả người dùng
     */
    public function index() {
        $users = $this->accountModel->getAccounts();
        include 'app/views/user/index.php';
    }

    /**
     * Xử lý việc xóa một tài khoản
     */
    public function destroy($id) {
        // AN TOÀN: Không cho phép admin tự xóa tài khoản của chính mình
        if ($id == $_SESSION['user_id']) {
            $_SESSION['flash_message'] = ['type' => 'warning', 'message' => 'Bạn không thể tự xóa tài khoản của mình.'];
            header('Location: /webbanhang/user');
            exit();
        }

        if ($this->accountModel->deleteAccountById($id)) {
            $_SESSION['flash_message'] = ['type' => 'success', 'message' => 'Đã xóa tài khoản thành công.'];
        } else {
            $_SESSION['flash_message'] = ['type' => 'danger', 'message' => 'Có lỗi xảy ra, không thể xóa tài khoản.'];
        }
        
        header('Location: /webbanhang/user');
        exit();
    }
}
