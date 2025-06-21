<?php
class DefaultController {
    public function index() {
        echo '
        <div style="text-align:center; margin-top:50px;">
            <h1>Chào mừng bạn đến với Web Bán Hàng</h1>
            <a href="/webbanhang/Product" style="padding: 10px 20px; background-color: #28a745; color: white; text-decoration: none; border-radius: 5px;">
                Xem danh sách mặt hàng
            </a>
        </div>';
    }
}
