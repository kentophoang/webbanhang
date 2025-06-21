<?php
class SessionHelper
{
    /**
     * Kiểm tra xem người dùng đã đăng nhập chưa.
     * Trả về true nếu có 'user_id' trong session.
     */
    public static function isLoggedIn()
    {
        // Bạn có thể dùng 'user_id' hoặc 'username', miễn là nó được thiết lập khi đăng nhập thành công.
        return isset($_SESSION['user_id']); 
    }

    /**
     * Kiểm tra xem người dùng có phải là Admin không.
     * Trả về true nếu đã đăng nhập VÀ có vai trò là 'admin'.
     */
    public static function isAdmin()
    {
        return self::isLoggedIn() && isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
    }
}