<?php
class CategoryModel {
    private $conn;
    private $table_name = "category";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getCategories() {
        $query = "SELECT 
                    c.id, 
                    c.name, 
                    c.parent_id, 
                    p_parent.name as parent_name,
                    COUNT(prod.id) as product_count
                  FROM 
                    category c
                  LEFT JOIN 
                    product prod ON c.id = prod.category_id
                  LEFT JOIN 
                    category p_parent ON c.parent_id = p_parent.id
                  GROUP BY 
                    c.id, c.name, c.parent_id, p_parent.name
                  ORDER BY 
                    c.parent_id ASC, c.name ASC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
    
    public function getHierarchicalCategories() {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY parent_id, name";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $allCategories = $stmt->fetchAll(PDO::FETCH_OBJ);
        
        $nested = [];
        $indexed = [];

        foreach ($allCategories as $category) {
            $indexed[$category->id] = $category;
            $indexed[$category->id]->children = [];
        }

        foreach ($indexed as $id => $category) {
            if (isset($category->parent_id) && isset($indexed[$category->parent_id])) {
                $indexed[$category->parent_id]->children[] =& $indexed[$id];
            } else {
                $nested[] =& $indexed[$id];
            }
        }
        return $nested;
    }

    public function getCategoryById($id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public function addCategory($name, $parentId = null) {
        $query = "INSERT INTO " . $this->table_name . " (name, parent_id) VALUES (?, ?)";
        $stmt = $this->conn->prepare($query);
        $parentId = !empty($parentId) ? $parentId : null;
        return $stmt->execute([trim($name), $parentId]);
    }

    public function updateCategory($id, $name, $parentId = null) {
        $query = "UPDATE " . $this->table_name . " SET name = ?, parent_id = ? WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $parentId = !empty($parentId) ? $parentId : null;
        return $stmt->execute([trim($name), $parentId, $id]);
    }

    public function deleteCategory($id) {
        // Cần xem xét việc xử lý các danh mục con trước khi xóa
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$id]);
    }
    
    public function getSpecTemplatesByCategoryId($categoryId) {
        $query = "SELECT * FROM category_spec_templates WHERE category_id = ? ORDER BY id";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$categoryId]);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function updateSpecTemplates($categoryId, $specNames) {
        $deleteQuery = "DELETE FROM category_spec_templates WHERE category_id = ?";
        $this->conn->prepare($deleteQuery)->execute([$categoryId]);

        if (!empty($specNames)) {
            $insertQuery = "INSERT INTO category_spec_templates (category_id, spec_name) VALUES (?, ?)";
            $insertStmt = $this->conn->prepare($insertQuery);

            foreach ($specNames as $name) {
                if (!empty(trim($name))) {
                    $insertStmt->execute([$categoryId, trim($name)]);
                }
            }
        }
        return true;
    }
}