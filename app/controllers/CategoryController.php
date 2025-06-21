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
        include 'app/views/category/add.php';
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /webbanhang/category');
            exit();
        }
        $name = trim($_POST['name'] ?? '');
        if (!empty($name)) {
            $this->categoryModel->addCategory($name);
        }
        header('Location: /webbanhang/category');
        exit();
    }

    public function edit($id) {
        $category = $this->categoryModel->getCategoryById($id);
        $specTemplates = $this->categoryModel->getSpecTemplatesByCategoryId($id);
        include 'app/views/category/edit.php';
    }

    /**
     * SỬA LỖI Ở ĐÂY: Hàm cập nhật và chuyển hướng về lại trang edit
     */
    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /webbanhang/category');
            exit();
        }

        // Cập nhật tên danh mục
        $name = trim($_POST['name'] ?? '');
        if (!empty($name)) {
            $this->categoryModel->updateCategory($id, $name);
        }

        // Cập nhật các mẫu thông số
        $specNames = $_POST['specs'] ?? [];
        $this->categoryModel->updateSpecTemplates($id, $specNames);

        // Thêm thông báo thành công vào session
        $_SESSION['flash_message'] = ['type' => 'success', 'message' => 'Đã cập nhật danh mục thành công!'];

        // Chuyển hướng về lại chính trang sửa để xem kết quả
        header('Location: /webbanhang/category/edit/' . $id);
        exit();
    }

    public function delete($id) {
        $this->categoryModel->deleteCategory($id);
        header('Location: /webbanhang/category');
        exit();
    }
}
