<?php
require_once 'app/controllers/BaseController.php';

class ProductController extends BaseController {
    private $productModel;
    private $categoryModel;
    private $accountModel;
    private $wishlistModel;

    public function __construct() {
        parent::__construct();
        $this->productModel = $this->loadModel('ProductModel');
        $this->categoryModel = $this->loadModel('CategoryModel');
        $this->accountModel = $this->loadModel('AccountModel');
        $this->wishlistModel = $this->loadModel('WishlistModel');
    }

    // --- LOGIC HIỂN THỊ DANH SÁCH SẢN PHẨM ---

    private function listProducts($baseFilters = []) {
        $filtersFromUrl = [
            'price_from' => $_GET['price_from'] ?? null,
            'price_to' => $_GET['price_to'] ?? null,
            'sort_by' => $_GET['sort_by'] ?? 'newest',
        ];
        $allFilters = array_merge($baseFilters, array_filter($filtersFromUrl));
        $products = $this->productModel->getFilteredProducts($allFilters);
        $categories = $this->categoryModel->getCategories();
        include 'app/views/product/list.php';
    }

    public function index() {
        $this->listProducts();
    }

    public function category($id) {
        $this->listProducts(['category_id' => $id]);
    }

    public function search() {
        $this->listProducts(['search_term' => $_GET['q'] ?? '']);
    }

    // --- CÁC CHỨC NĂNG SẢN PHẨM CHUNG ---

    public function show($id) {
        $product = $this->productModel->getProductById($id);
        if ($product) {
            $productImages = $this->productModel->getImagesByProductId($id);
            $productSpecs = $this->productModel->getSpecificationsByProductId($id);
            $relatedProducts = $this->productModel->getRelatedProducts($id, $product->category_id);
            $isInWishlist = SessionHelper::isLoggedIn() ? $this->wishlistModel->isProductInWishlist($_SESSION['user_id'], $id) : false;
            include 'app/views/product/show.php';
        } else {
            die("Không tìm thấy sản phẩm.");
        }
    }

    // --- CÁC CHỨC NĂNG QUẢN TRỊ SẢN PHẨM ---

    public function manage() {
        if (!SessionHelper::isAdmin()) {
            header('Location: /webbanhang/product');
            exit();
        }
        $products = $this->productModel->getProducts();
        include 'app/views/product/manage.php';
    }

    public function add() {
        if (!SessionHelper::isAdmin()) {
            header('Location: /webbanhang/product');
            exit();
        }
        $categories = $this->categoryModel->getCategories();
        include 'app/views/product/add.php';
    }

    public function getSpecTemplatesForCategory($categoryId) {
        if (empty($categoryId)) {
            echo '<div class="alert alert-warning">Vui lòng chọn một danh mục.</div>';
            exit();
        }

        $specTemplates = $this->categoryModel->getSpecTemplatesByCategoryId($categoryId);

        if (empty($specTemplates)) {
            echo '
                <div class="alert alert-info">Danh mục này chưa có mẫu thông số kỹ thuật nào.</div>
                <input type="hidden" name="description" value="">
            ';
            exit();
        }

        echo '<h4>Thông số kỹ thuật</h4><hr>';
        foreach ($specTemplates as $template) {
            $specName = htmlspecialchars($template->spec_name);
            echo "
            <div class='mb-3'>
                <label for='spec_{$specName}' class='form-label'>{$specName}</label>
                <input type='text' id='spec_{$specName}' name='specs[{$specName}]' class='form-control'>
            </div>
            ";
        }
        exit();
    }

    public function save() {
        if (!SessionHelper::isAdmin() || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /webbanhang/product');
            exit();
        }
        
        $name = trim($_POST['name'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $price = floatval($_POST['price'] ?? 0);
        $category_id = intval($_POST['category_id'] ?? 0);

        try {
            $image = "";
            if (!empty($_FILES['image']['name']) && $_FILES['image']['error'] === 0) {
                $image = $this->uploadImage($_FILES['image']);
            }

            $newProductId = $this->productModel->addProduct($name, $description, $price, $category_id, $image);

            if ($newProductId) {
                $specsData = $_POST['specs'] ?? [];
                if (!empty($specsData)) {
                    $this->productModel->updateSpecifications($newProductId, $specsData);
                }
                
                header('Location: /webbanhang/product/manage');
                exit();
            } else {
                 throw new Exception("Thêm sản phẩm chính thất bại.");
            }

        } catch (Exception $e) {
            $errors = ['general' => $e->getMessage()];
            $categories = $this->categoryModel->getCategories();
            include 'app/views/product/add.php';
        }
    }

    public function edit($id) {
        if (!SessionHelper::isAdmin()) {
            header('Location: /webbanhang/product');
            exit();
        }
        $product = $this->productModel->getProductById($id);
        if ($product) {
            $categories = $this->categoryModel->getCategories();
            $specTemplates = $this->categoryModel->getSpecTemplatesByCategoryId($product->category_id);
            $currentSpecsRaw = $this->productModel->getSpecificationsByProductId($id);
            $currentSpecs = [];
            foreach ($currentSpecsRaw as $spec) {
                $currentSpecs[$spec->spec_key] = $spec->spec_value;
            }
            include 'app/views/product/edit.php';
        } else {
            die("Không tìm thấy sản phẩm.");
        }
    }

    public function update() {
        if (!SessionHelper::isAdmin() || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /webbanhang/product');
            exit();
        }
        $id = intval($_POST['id']);
        $name = trim($_POST['name']);
        $description = trim($_POST['description']);
        $price = floatval($_POST['price']);
        $category_id = intval($_POST['category_id']);
        $image = $_POST['existing_image'] ?? '';
        try {
            if (!empty($_FILES['image']['name']) && $_FILES['image']['error'] === 0) {
                $image = $this->uploadImage($_FILES['image']);
            }
            $this->productModel->updateProduct($id, $name, $description, $price, $category_id, $image);
            $specsData = $_POST['specs'] ?? [];
            $this->productModel->updateSpecifications($id, $specsData);
            header('Location: /webbanhang/product/manage');
            exit();
        } catch (Exception $e) {
            die("Lỗi cập nhật: " . $e->getMessage());
        }
    }

    public function delete($id) {
        if (!SessionHelper::isAdmin()) {
            header('Location: /webbanhang/product');
            exit();
        }
        if ($this->productModel->deleteProduct($id)) {
            header('Location: /webbanhang/product/manage');
            exit();
        } else {
            die("Có lỗi xảy ra khi xóa sản phẩm.");
        }
    }

    private function uploadImage($file) {
        $target_dir = "uploads/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        $imageFileType = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
        if (!getimagesize($file["tmp_name"])) {
            throw new Exception("Tập tin không phải hình ảnh.");
        }
        if ($file["size"] > 10 * 1024 * 1024) {
            throw new Exception("Hình ảnh quá lớn (giới hạn 10MB).");
        }
        if (!in_array($imageFileType, $allowed_types)) {
            throw new Exception("Chỉ cho phép định dạng JPG, JPEG, PNG, GIF.");
        }
        $new_filename = uniqid() . '.' . $imageFileType;
        $new_target_file = $target_dir . $new_filename;
        if (!move_uploaded_file($file["tmp_name"], $new_target_file)) {
            throw new Exception("Tải ảnh thất bại.");
        }
        return $new_target_file;
    }

    // --- CÁC HÀM XỬ LÝ GIỎ HÀNG VÀ THANH TOÁN ---

    public function addToCart($id) {
        $product = $this->productModel->getProductById($id);
        if (!$product) {
            header('Location: /webbanhang/product');
            exit();
        }
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
        $id = intval($id);
        if (isset($_SESSION['cart'][$id])) {
            $_SESSION['cart'][$id]['quantity']++;
        } else {
            $_SESSION['cart'][$id] = [
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => 1,
                'image' => $product->image
            ];
        }
        header('Location: /webbanhang/product/cart');
        exit();
    }

    public function removeFromCart($id) {
        if (isset($_SESSION['cart'][intval($id)])) {
            unset($_SESSION['cart'][intval($id)]);
        }
        header('Location: /webbanhang/product/cart');
        exit();
    }

    public function cart() {
        $cart = $_SESSION['cart'] ?? [];
        include 'app/views/product/cart.php';
    }

    public function checkout() {
        if (empty($_SESSION['cart'])) {
            header('Location: /webbanhang/product');
            exit();
        }
        $account = null;
        if (SessionHelper::isLoggedIn()) {
            $account = $this->accountModel->getAccountById($_SESSION['user_id']);
        }
        include 'app/views/product/checkout.php';
    }

    public function processCheckout() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_SESSION['cart'])) {
            header('Location: /webbanhang/product');
            exit();
        }

        try {
            $this->db->beginTransaction();
            $user_id = null;
            
            if (SessionHelper::isLoggedIn()) {
                $user_id = $_SESSION['user_id'];
                $account = $this->accountModel->getAccountById($user_id);
                if (empty($account->phone) || empty($account->address)) {
                    $_SESSION['flash_message'] = ['type' => 'danger', 'message' => 'Vui lòng cập nhật số điện thoại và địa chỉ trước khi thanh toán!'];
                    header('Location: /webbanhang/account/profile');
                    exit();
                }
                $name = $account->name;
                $phone = $account->phone;
                $address = $account->address;
            } else {
                $name = trim($_POST['name']);
                $phone = trim($_POST['phone']);
                $address = trim($_POST['address']);
            }

            $orderQuery = "INSERT INTO orders (user_id, name, phone, address) VALUES (?, ?, ?, ?)";
            $orderStmt = $this->db->prepare($orderQuery);
            $orderStmt->execute([$user_id, $name, $phone, $address]);
            $order_id = $this->db->lastInsertId();

            $detailsQuery = "INSERT INTO order_details (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)";
            $detailsStmt = $this->db->prepare($detailsQuery);
            foreach ($_SESSION['cart'] as $product_id => $item) {
                $detailsStmt->execute([$order_id, intval($product_id), $item['quantity'], $item['price']]);
            }

            $this->db->commit();
            unset($_SESSION['cart']);
            $_SESSION['flash_message'] = ['type' => 'success', 'message' => 'Đặt hàng thành công!'];
            header('Location: /webbanhang/order/index');
            exit();

        } catch (Exception $e) {
            $this->db->rollBack();
            die("Lỗi khi xử lý đơn hàng: " . $e->getMessage());
        }
    }
    
    public function orderConfirmation() {
        include 'app/views/product/orderConfirmation.php';
    }

    // --- CÁC HÀM XỬ LÝ SO SÁNH SẢN PHẨM ---

    public function addToCompare($id) {
        if (!isset($_SESSION['compare_list'])) {
            $_SESSION['compare_list'] = [];
        }
        $product = $this->productModel->getProductById($id);
        if (!$product) {
            header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? '/webbanhang/product'));
            exit();
        }
        if (!empty($_SESSION['compare_list'])) {
            $firstProductId = array_key_first($_SESSION['compare_list']);
            $firstProduct = $this->productModel->getProductById($firstProductId);
            if ($firstProduct->category_id != $product->category_id) {
                $_SESSION['compare_list'] = [];
                $_SESSION['flash_message'] = ['type' => 'info', 'message' => 'Bạn chỉ có thể so sánh các sản phẩm trong cùng một danh mục. Danh sách so sánh đã được làm mới.'];
            }
        }
        if (count($_SESSION['compare_list']) < 3) {
            $_SESSION['compare_list'][$id] = $product->name;
        } else {
            $_SESSION['flash_message'] = ['type' => 'warning', 'message' => 'Bạn chỉ có thể so sánh tối đa 3 sản phẩm.'];
        }
        header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? '/webbanhang/product'));
        exit();
    }

    public function removeFromCompare($id) {
        if (isset($_SESSION['compare_list'][intval($id)])) {
            unset($_SESSION['compare_list'][intval($id)]);
        }
        header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? '/webbanhang/product'));
        exit();
    }

    public function compare() {
        $productIds = array_keys($_SESSION['compare_list'] ?? []);
        if (count($productIds) < 2) {
             $_SESSION['flash_message'] = ['type' => 'info', 'message' => 'Bạn cần chọn ít nhất 2 sản phẩm để so sánh.'];
            header('Location: /webbanhang/product');
            exit();
        }
        $products = $this->productModel->getProductsByIds($productIds);
        $allSpecKeys = [];
        $productsWithSpecs = [];
        foreach ($products as $product) {
            $specs = $this->productModel->getSpecificationsByProductId($product->id);
            $product->specs = [];
            foreach ($specs as $spec) {
                $product->specs[$spec->spec_key] = $spec->spec_value;
                if (!in_array($spec->spec_key, $allSpecKeys)) {
                    $allSpecKeys[] = $spec->spec_key;
                }
            }
            $productsWithSpecs[] = $product;
        }
        include 'app/views/product/compare.php';
    }
}