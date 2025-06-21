<?php
class ProductModel
{
    private $conn;
    private $table_name = "product";

    public function __construct($db)
    {
        $this->conn = $db;
    }

    /**
     * ===================================================================
     * CÁC HÀM LẤY DỮ LIỆU SẢN PHẨM (READ)
     * ===================================================================
     */

    public function getFilteredProducts($filters = [])
    {
        $query = "SELECT p.*, c.name as category_name FROM " . $this->table_name . " p LEFT JOIN category c ON p.category_id = c.id WHERE 1=1";
        $params = [];

        if (!empty($filters['category_id'])) {
            $query .= " AND p.category_id = :category_id";
            $params[':category_id'] = $filters['category_id'];
        }
        if (!empty($filters['price_from'])) {
            $query .= " AND p.price >= :price_from";
            $params[':price_from'] = $filters['price_from'];
        }
        if (!empty($filters['price_to'])) {
            $query .= " AND p.price <= :price_to";
            $params[':price_to'] = $filters['price_to'];
        }
        if (!empty($filters['search_term'])) {
            $query .= " AND (p.name LIKE :search_term OR p.description LIKE :search_term)";
            $params[':search_term'] = '%' . $filters['search_term'] . '%';
        }
        if (!empty($filters['sort_by'])) {
            switch ($filters['sort_by']) {
                case 'price_asc': $query .= " ORDER BY p.price ASC"; break;
                case 'price_desc': $query .= " ORDER BY p.price DESC"; break;
                default: $query .= " ORDER BY p.id DESC"; break;
            }
        } else {
            $query .= " ORDER BY p.id DESC";
        }

        $stmt = $this->conn->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function getProducts() {
        return $this->getFilteredProducts([]);
    }

    public function getProductsByCategory($categoryId) {
        return $this->getFilteredProducts(['category_id' => $categoryId]);
    }

    public function getProductById($id) {
        $sql = "SELECT p.*, c.name AS category_name FROM product p LEFT JOIN category c ON p.category_id = c.id WHERE p.id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public function getProductsByIds(array $ids) {
        if (empty($ids)) return [];
        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $query = "SELECT p.*, c.name AS category_name FROM product p LEFT JOIN category c ON p.category_id = c.id WHERE p.id IN ($placeholders)";
        $stmt = $this->conn->prepare($query);
        $stmt->execute($ids);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
    
    public function getRelatedProducts($productId, $categoryId, $limit = 4) {
        $query = "SELECT * FROM product WHERE category_id = :category_id AND id != :product_id LIMIT :limit";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':category_id', $categoryId, PDO::PARAM_INT);
        $stmt->bindParam(':product_id', $productId, PDO::PARAM_INT);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
    
    public function getImagesByProductId($productId) {
        $query = "SELECT * FROM product_images WHERE product_id = :product_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':product_id', $productId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function getSpecificationsByProductId($productId) {
        $query = "SELECT spec_key, spec_value FROM product_specifications WHERE product_id = :product_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':product_id', $productId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * ===================================================================
     * CÁC HÀM GHI DỮ LIỆU SẢN PHẨM (CREATE, UPDATE, DELETE)
     * ===================================================================
     */

    public function addProduct($name, $description, $price, $category_id, $image = null)
    {
        $query = "INSERT INTO " . $this->table_name . " (name, description, price, category_id, image) VALUES (:name, :description, :price, :category_id, :image)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':category_id', $category_id);
        $stmt->bindParam(':image', $image);
        
        if ($stmt->execute()) {
            return $this->conn->lastInsertId();
        }
        
        return false;
    }

    public function updateProduct($id, $name, $description, $price, $category_id, $image = null)
    {
        $query = "UPDATE " . $this->table_name . " SET name = :name, description = :description, price = :price, category_id = :category_id, image = :image WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':category_id', $category_id);
        $stmt->bindParam(':image', $image);
        return $stmt->execute();
    }
    
    public function updateSpecifications($productId, $specs) {
        // Bước 1: Xóa hết tất cả thông số cũ của sản phẩm này
        $deleteQuery = "DELETE FROM product_specifications WHERE product_id = ?";
        $this->conn->prepare($deleteQuery)->execute([$productId]);

        // Bước 2: Thêm lại các thông số mới từ form
        if (!empty($specs)) {
            $insertQuery = "INSERT INTO product_specifications (product_id, spec_key, spec_value) VALUES (?, ?, ?)";
            $insertStmt = $this->conn->prepare($insertQuery);

            foreach ($specs as $key => $value) {
                // Chỉ thêm nếu giá trị của thông số không bị để trống
                if (!empty(trim($value))) {
                    $insertStmt->execute([$productId, $key, trim($value)]);
                }
            }
        }
        return true;
    }

    public function deleteProduct($id)
    {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}