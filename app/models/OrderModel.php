<?php
class OrderModel
{
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    /**
     * Lấy tất cả các đơn hàng và tính tổng giá trị cho mỗi đơn
     * (SỬA LỖI CUỐI CÙNG: GROUP BY đầy đủ để tương thích mọi phiên bản MySQL)
     */
    public function getAllOrders()
    {
        // Câu lệnh này liệt kê tất cả các cột trong GROUP BY,
        // đảm bảo tương thích với chế độ ONLY_FULL_GROUP_BY.
        $query = "SELECT 
                    o.id, 
                    o.name, 
                    o.phone, 
                    o.address, 
                    o.order_date,
                    COALESCE(SUM(od.quantity * od.price), 0) as total_amount
                  FROM 
                    orders o
                  LEFT JOIN 
                    order_details od ON o.id = od.order_id
                  GROUP BY 
                    o.id, o.name, o.phone, o.address, o.order_date
                  ORDER BY 
                    o.order_date DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * Lấy thông tin chi tiết của một đơn hàng bằng ID
     */
    public function getOrderById($id)
    {
        $query = "SELECT * FROM orders WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    /**
     * Lấy tất cả các sản phẩm trong một đơn hàng cụ thể
     */
    public function getOrderDetailsByOrderId($orderId)
    {
        // JOIN với bảng product để lấy tên và ảnh sản phẩm
        $query = "SELECT od.*, p.name as product_name, p.image as product_image
                  FROM order_details od
                  LEFT JOIN product p ON od.product_id = p.id
                  WHERE od.order_id = ?";
                  
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$orderId]);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * (Chức năng trong tương lai) Cập nhật trạng thái đơn hàng
     */
    public function updateOrderStatus($id, $status)
    {
        // Thêm cột 'status' vào bảng 'orders' nếu bạn muốn dùng chức năng này
        $query = "UPDATE orders SET status = ? WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$status, $id]);
    }
}
