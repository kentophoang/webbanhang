<?php
require_once 'app/controllers/BaseController.php';

class WishlistController extends BaseController {
    private $wishlistModel;

    public function __construct() {
        parent::__construct();
        $this->wishlistModel = $this->loadModel('WishlistModel');

        // Yêu cầu người dùng phải đăng nhập để sử dụng các chức năng này
        if (!SessionHelper::isLoggedIn()) {
            header('Location: /webbanhang/account/login');
            exit();
        }
    }

    // Thêm/Xóa sản phẩm khỏi wishlist
    public function toggle($productId) {
        $userId = $_SESSION['user_id'];
        
        // === THÊM KHỐI KIỂM TRA NÀY ĐỂ TĂNG TÍNH AN TOÀN ===
        // Nạp AccountModel để có thể kiểm tra
        $accountModel = $this->loadModel('AccountModel');
        if (!$accountModel->getAccountById($userId)) {
            // Nếu user ID trong session không tồn tại trong DB (do session cũ hoặc user đã bị xóa),
            // hãy tự động đăng xuất họ và yêu cầu đăng nhập lại.
            session_unset();
            session_destroy();
            header('Location: /webbanhang/account/login');
            exit();
        }
        // === KẾT THÚC KHỐI KIỂM TRA ===

        if ($this->wishlistModel->isProductInWishlist($userId, $productId)) {
            // Nếu đã có, thì xóa đi
            $this->wishlistModel->removeFromWishlist($userId, $productId);
        } else {
            // Nếu chưa có, thì thêm vào
            $this->wishlistModel->addToWishlist($userId, $productId);
        }
        
        // Quay trở lại trang sản phẩm vừa rồi
        header('Location: /webbanhang/product/show/' . $productId);
        exit();
    }
}