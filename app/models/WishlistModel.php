<?php
class WishlistModel {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Kiểm tra sản phẩm có trong wishlist của user không
    public function isProductInWishlist($userId, $productId) {
        $query = "SELECT id FROM wishlist WHERE user_id = :user_id AND product_id = :product_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':product_id', $productId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch() !== false;
    }

    // Thêm vào wishlist
    public function addToWishlist($userId, $productId) {
        if ($this->isProductInWishlist($userId, $productId)) {
            return false; // Đã tồn tại
        }
        $query = "INSERT INTO wishlist (user_id, product_id) VALUES (:user_id, :product_id)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':product_id', $productId, PDO::PARAM_INT);
        return $stmt->execute();
    }

    // Xóa khỏi wishlist
    public function removeFromWishlist($userId, $productId) {
        $query = "DELETE FROM wishlist WHERE user_id = :user_id AND product_id = :product_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':product_id', $productId, PDO::PARAM_INT);
        return $stmt->execute();
    }
}