<?php
require_once 'app/helpers/SessionHelper.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Web Bán Hàng</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <style>
        body {
            padding-top: 135px; 
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        .main-content {
            flex: 1;
        }
        .header-top {
            background-color: #f8f9fa;
            font-size: 0.85rem;
            padding: 0.5rem 1rem;
        }
        .header-main {
            padding: 1rem 1rem;
            background-color: #fff;
        }
        .header-actions .nav-link, .header-actions .dropdown-toggle {
            font-size: 1.1rem;
            color: #495057;
        }
        .header-actions .bi {
            font-size: 1.5rem;
            vertical-align: middle;
        }
        .search-form {
            width: 100%;
            max-width: 500px;
        }
        .navbar-main {
            background-color: #fff;
            box-shadow: 0 2px 4px rgba(0,0,0,.05);
        }
        .sticky-header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1030;
        }
    </style>
</head>
<body>

<header class="sticky-header">
    <div class="header-top border-bottom d-none d-md-block">
        <div class="container-fluid d-flex justify-content-between">
            <span>Chào mừng đến với Web Bán Hàng!</span>
            <span><i class="bi bi-telephone-fill"></i> Hotline: 1800 1234</span>
        </div>
    </div>
    
    <div class="header-main border-bottom">
        <div class="container-fluid d-flex justify-content-between align-items-center">
            <a class="navbar-brand fs-4 fw-bold" href="/webbanhang/Product">Web Bán Hàng</a>
            
            <form class="d-flex search-form mx-4" action="/webbanhang/product/search" method="GET">
                <input class="form-control me-2" type="search" name="q" placeholder="Bạn tìm gì hôm nay?" aria-label="Search" required>
                <button class="btn btn-primary" type="submit"><i class="bi bi-search"></i></button>
            </form>
            
            <div class="header-actions d-flex align-items-center">
                <?php if (SessionHelper::isLoggedIn()): ?>
                    <div class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-person-circle"></i> <span><?php echo htmlspecialchars($_SESSION['username']); ?></span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="/webbanhang/account/profile">Thông tin tài khoản</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="/webbanhang/account/logout">Đăng xuất</a></li>
                        </ul>
                    </div>
                <?php else: ?>
                    <a class="nav-link" href="/webbanhang/account/login">
                        <i class="bi bi-person-circle"></i> <span class="d-none d-lg-inline">Đăng nhập</span>
                    </a>
                <?php endif; ?>

                <a class="nav-link position-relative ms-3" href="/webbanhang/Product/cart">
                    <i class="bi bi-cart"></i>
                    <?php 
                        $cart_item_count = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;
                        if ($cart_item_count > 0): 
                    ?>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                            <?php echo $cart_item_count; ?>
                        </span>
                    <?php endif; ?>
                </a>
            </div>
        </div>
    </div>
    
    <nav class="navbar navbar-expand-lg navbar-main">
        <div class="container-fluid">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav" aria-controls="mainNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="mainNav">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="/webbanhang/Product/">Trang chủ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/webbanhang/product/category/1">Điện thoại</a>
                    </li>
                     <li class="nav-item">
                        <a class="nav-link" href="/webbanhang/product/category/2">Laptop</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/webbanhang/product/category/3">Khác</a>
                    </li>
                    <?php if (SessionHelper::isAdmin()): ?>
                        <li class="nav-item dropdown">
                             <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Quản lý
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="/webbanhang/product/manage">Quản lý sản phẩm</a></li>
                                <li><a class="dropdown-item" href="/webbanhang/order/index">Quản lý đơn hàng</a></li>
                                <li><a class="dropdown-item" href="/webbanhang/category">Quản lý danh mục</a></li>
                                <li><a class="dropdown-item" href="/webbanhang/user">Quản lý khách hàng</a></li>
                            </ul>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>
</header>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<div class="container mt-4 main-content">