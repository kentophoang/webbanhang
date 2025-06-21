<?php
require_once 'app/config/database.php';
require_once 'app/models/ProductModel.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $database = new Database();
    $db = $database->getConnection();
    $productModel = new ProductModel($db);

    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $category_id = $_POST['category_id'];

    if ($productModel->addProduct($name, $description, $price, $category_id)) {
        header("Location: app/views/product/list.php");
    } else {
        echo "Thêm sản phẩm thất bại.";
    }
}
?>