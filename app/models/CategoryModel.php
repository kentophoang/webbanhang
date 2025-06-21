<?php
class CategoryModel {
    private $conn;
    private $table_name = "category";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getCategories() {
        $query = "SELECT c.*, COUNT(p.id) as product_count
                  FROM category c
                  LEFT JOIN product p ON c.id = p.category_id
                  GROUP BY c.id, c.name
                  ORDER BY c.name ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function getCategoryById($id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public function addCategory($name) {
        $query = "INSERT INTO " . $this->table_name . " (name) VALUES (?)";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([trim($name)]);
    }

    public function updateCategory($id, $name) {
        $query = "UPDATE " . $this->table_name . " SET name = ? WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([trim($name), $id]);
    }

    public function deleteCategory($id) {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$id]);
    }
    
    // --- CÁC HÀM CHO MẪU THÔNG SỐ ---

    public function getSpecTemplatesByCategoryId($categoryId) {
        $query = "SELECT * FROM category_spec_templates WHERE category_id = ? ORDER BY id";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$categoryId]);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * SỬA LỖI Ở ĐÂY: Hàm cập nhật mẫu thông số đã được tối ưu
     */
    public function updateSpecTemplates($categoryId, $specNames) {
        // B1: Xóa tất cả các mẫu cũ của danh mục này
        $deleteQuery = "DELETE FROM category_spec_templates WHERE category_id = ?";
        $this->conn->prepare($deleteQuery)->execute([$categoryId]);

        // B2: Thêm lại các mẫu mới từ form (nếu có)
        if (!empty($specNames)) {
            $insertQuery = "INSERT INTO category_spec_templates (category_id, spec_name) VALUES (?, ?)";
            $insertStmt = $this->conn->prepare($insertQuery);

            foreach ($specNames as $name) {
                // Chỉ thêm nếu tên không rỗng
                if (!empty(trim($name))) {
                    $insertStmt->execute([$categoryId, trim($name)]);
                }
            }
        }
        return true;
    }
}
