<?php
require_once 'app/controllers/BaseController.php';

class CategoryController extends BaseController {
    private $categoryModel;

    public function __construct() {
        parent::__construct();
        $this->categoryModel = $this->loadModel('CategoryModel');

        if (!SessionHelper::isAdmin()) {
            header('Location: /webbanhang');
            exit();
        }
    }

    public function index() {
        $categories = $this->categoryModel->getCategories();
        include 'app/views/category/index.php';
    }

    public function add() {
        $categories = $this->categoryModel->getCategories();
        include 'app/views/category/add.php';
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /webbanhang/category');
            exit();
        }
        $name = trim($_POST['name'] ?? '');
        $parentId = $_POST['parent_id'] ?? null;

        if (!empty($name)) {
            $this->categoryModel->addCategory($name, $parentId);
        }
        header('Location: /webbanhang/category');
        exit();
    }

    public function edit($id) {
        $category = $this->categoryModel->getCategoryById($id);
        $specTemplates = $this->categoryModel->getSpecTemplatesByCategoryId($id);
        $categories = $this->categoryModel->getCategories();
        include 'app/views/category/edit.php';
    }

    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /webbanhang/category');
            exit();
        }

        $name = trim($_POST['name'] ?? '');
        $parentId = $_POST['parent_id'] ?? null;
        if (!empty($name)) {
            $this->categoryModel->updateCategory($id, $name, $parentId);
        }

        $specNames = $_POST['specs'] ?? [];
        $this->categoryModel->updateSpecTemplates($id, $specNames);

        $_SESSION['flash_message'] = ['type' => 'success', 'message' => 'Đã cập nhật danh mục thành công!'];

        header('Location: /webbanhang/category/edit/' . $id);
        exit();
    }

    public function delete($id) {
        $this->categoryModel->deleteCategory($id);
        header('Location: /webbanhang/category');
        exit();
    }
}