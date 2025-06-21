<?php include 'app/views/shares/header.php'; ?>

<!-- Bắt đầu một hàng (row) để chứa cả hai cột -->
<div class="row">

    <!-- Cột 1: Thanh bên cho bộ lọc (Sidebar) được bọc trong form -->
    <form action="" method="GET" id="filter-form" class="col-lg-3">
        
        <!-- Trường ẩn để giữ lại tham số tìm kiếm (nếu có) khi lọc -->
        <?php if (isset($_GET['q'])): ?>
            <input type="hidden" name="q" value="<?= htmlspecialchars($_GET['q']) ?>">
        <?php endif; ?>

        <div class="sidebar-filters">
            <!-- Lọc theo Danh mục (các link này hoạt động riêng biệt) -->
            <div class="card mb-4">
                <div class="card-header"><h5 class="mb-0">Danh mục sản phẩm</h5></div>
                <div class="list-group list-group-flush">
                    <a href="/webbanhang/product" class="list-group-item list-group-item-action">Tất cả sản phẩm</a>
                    <?php if (isset($categories)) {
                        foreach ($categories as $category): ?>
                            <a href="/webbanhang/product/category/<?= $category->id ?>" class="list-group-item list-group-item-action">
                                <?= htmlspecialchars($category->name) ?>
                            </a>
                    <?php endforeach; }?>
                </div>
            </div>

            <!-- Lọc theo Giá -->
            <div class="card">
                <div class="card-header"><h5 class="mb-0">Lọc theo giá</h5></div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="price_from" class="form-label">Từ</label>
                        <input type="number" class="form-control" name="price_from" id="price_from" placeholder="0đ" value="<?= htmlspecialchars($_GET['price_from'] ?? '') ?>">
                    </div>
                    <div class="mb-3">
                        <label for="price_to" class="form-label">Đến</label>
                        <input type="number" class="form-control" name="price_to" id="price_to" placeholder="10,000,000đ" value="<?= htmlspecialchars($_GET['price_to'] ?? '') ?>">
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Áp dụng</button>
                </div>
            </div>
        </div>
    </form>

    <!-- Cột 2: Hiển thị sản phẩm -->
    <div class="col-lg-9">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="mb-0">Tất cả sản phẩm</h3>
            <div class="d-flex align-items-center">
                <label for="sort-by" class="form-label me-2 mb-0">Sắp xếp:</label>
                <!-- Thẻ select này sẽ gửi dữ liệu thông qua thẻ form của sidebar -->
                <select class="form-select form-select-sm" name="sort_by" id="sort-by" form="filter-form" onchange="this.form.submit()" style="width: auto;">
                    <option value="newest" <?= ($_GET['sort_by'] ?? 'newest') == 'newest' ? 'selected' : '' ?>>Mới nhất</option>
                    <option value="price_asc" <?= ($_GET['sort_by'] ?? '') == 'price_asc' ? 'selected' : '' ?>>Giá: Thấp đến cao</option>
                    <option value="price_desc" <?= ($_GET['sort_by'] ?? '') == 'price_desc' ? 'selected' : '' ?>>Giá: Cao đến thấp</option>
                </select>
            </div>
        </div>

        <div class="row">
            <?php if (empty($products)): ?>
                <div class="col-12"><div class="alert alert-info">Không tìm thấy sản phẩm nào.</div></div>
            <?php else: ?>
                <?php foreach ($products as $product): ?>
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="card h-100 product-card">
                            <a href="/webbanhang/Product/show/<?= $product->id ?>">
                                <img src="/webbanhang/<?= htmlspecialchars($product->image ?? 'public/images/default-placeholder.png') ?>" class="card-img-top product-image" alt="<?= htmlspecialchars($product->name) ?>">
                            </a>
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title">
                                    <a href="/webbanhang/Product/show/<?= $product->id ?>" class="product-name-link">
                                        <?= htmlspecialchars($product->name) ?>
                                    </a>
                                </h5>
                                <p class="product-price fw-bold text-danger"><?= number_format($product->price, 0, ',', '.') ?>đ</p>
                                <div class="mt-auto">
                                    <a href="/webbanhang/Product/addToCart/<?= $product->id ?>" class="btn btn-primary w-100">Thêm vào giỏ</a>
                                    <?php if (SessionHelper::isAdmin()): ?>
                                        <div class="admin-actions mt-2 text-center">
                                            <a href="/webbanhang/Product/edit/<?= $product->id ?>" class="btn btn-sm btn-outline-secondary">Sửa</a>
                                            <a href="/webbanhang/Product/delete/<?= $product->id ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Bạn có chắc chắn muốn xóa?');">Xóa</a>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <!-- Phân trang -->
        <nav aria-label="Page navigation" class="mt-4">
            <ul class="pagination justify-content-center">
                <li class="page-item disabled"><a class="page-link" href="#">Trước</a></li>
                <li class="page-item active"><a class="page-link" href="#">1</a></li>
                <li class="page-item"><a class="page-link" href="#">2</a></li>
                <li class="page-item"><a class="page-link" href="#">3</a></li>
                <li class="page-item"><a class="page-link" href="#">Sau</a></li>
            </ul>
        </nav>
    </div>
</div>

<style>
/* CSS cho card sản phẩm */
.product-card { border: 1px solid #e9ecef; transition: all 0.3s ease; border-radius: 0.5rem; }
.product-card:hover { transform: translateY(-5px); box-shadow: 0 8px 16px rgba(0,0,0,0.1); }
.product-image { padding: 1rem; height: 220px; object-fit: contain; background-color: #fff; }
.product-name-link { color: #212529; text-decoration: none; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; min-height: 48px; }
.product-name-link:hover { color: #0d6efd; }
</style>

<?php include 'app/views/shares/footer.php'; ?>
