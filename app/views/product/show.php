<?php include 'app/views/shares/header.php'; ?>

<div class="container my-5">
    <div class="row">
        <!-- Cột bên trái: Thư viện ảnh và Thông số chính -->
        <div class="col-lg-7">
            <!-- Thư viện ảnh -->
            <div class="product-gallery mb-4">
                <div class="main-image-container border rounded-3 mb-3">
                    <img src="/webbanhang/<?= htmlspecialchars($product->image ?? ($productImages[0]->image_url ?? 'public/images/default-placeholder.png')) ?>" id="mainProductImage" class="img-fluid w-100">
                </div>
                <div class="thumbnail-scroll">
                    <!-- Ảnh đại diện -->
                    <?php if ($product->image): ?>
                        <img src="/webbanhang/<?= htmlspecialchars($product->image) ?>" class="thumbnail-item active" onclick="changeImage(this)">
                    <?php endif; ?>
                    <!-- Các ảnh phụ -->
                    <?php foreach ($productImages as $img): ?>
                        <img src="/webbanhang/<?= htmlspecialchars($img->image_url) ?>" class="thumbnail-item" onclick="changeImage(this)">
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Thông số kỹ thuật -->
            <div class="product-specs-container">
                <h3 class="mb-3">Thông số kỹ thuật</h3>
                <table class="table table-bordered">
                    <tbody>
                        <?php foreach ($productSpecs as $spec): ?>
                        <tr>
                            <th class="bg-light" style="width: 35%;"><?= htmlspecialchars($spec->spec_key) ?></th>
                            <td><?= htmlspecialchars($spec->spec_value) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Cột bên phải: Thông tin mua hàng và So sánh -->
        <div class="col-lg-5">
            <div class="product-info-sticky">
                <h1 class="product-title h2"><?= htmlspecialchars($product->name) ?></h1>
                
                <div class="price-box my-3">
                    <span class="product-price-detail h3 text-danger fw-bold">
                        <?= number_format($product->price, 0, ',', '.') ?>đ
                    </span>
                </div>

                <div class="d-grid gap-2">
                    <form action="/webbanhang/Product/addToCart/<?= $product->id ?>" method="POST" class="d-grid">
                         <button type="submit" class="btn btn-danger btn-lg">
                            MUA NGAY
                         </button>
                    </form>
                    <div class="d-grid gap-2" style="grid-template-columns: 1fr 1fr;">
                        <a href="/webbanhang/wishlist/toggle/<?= $product->id ?>" class="btn btn-outline-danger d-flex align-items-center justify-content-center">
                            <i class="bi <?= $isInWishlist ? 'bi-heart-fill' : 'bi-heart' ?> me-2"></i> Yêu thích
                        </a>
                        
                        <!-- SỬA LẠI Ở ĐÂY: Nút So sánh động -->
                        <?php
                        $is_in_compare = isset($_SESSION['compare_list'][$product->id]);
                        ?>
                        <a href="/webbanhang/product/<?= $is_in_compare ? 'removeFromCompare' : 'addToCompare' ?>/<?= $product->id ?>" class="btn <?= $is_in_compare ? 'btn-success' : 'btn-outline-secondary' ?> d-flex align-items-center justify-content-center">
                            <i class="bi bi-bar-chart-fill me-2"></i> <?= $is_in_compare ? 'Đã thêm để so sánh' : 'So sánh' ?>
                        </a>
                    </div>
                </div>
                
                <div class="product-policy-box mt-4 border rounded p-3">
                    <h6 class="fw-bold">Chính sách bán hàng</h6>
                    <ul>
                        <li><i class="bi bi-check-circle-fill text-success"></i> Cam kết 100% hàng chính hãng</li>
                        <li><i class="bi bi-truck text-success"></i> Giao hàng miễn phí toàn quốc</li>
                        <li><i class="bi bi-arrow-repeat text-success"></i> Lỗi 1 đổi 1 trong 30 ngày</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Phần sản phẩm liên quan -->
    <div class="related-products mt-5">
        <h3 class="mb-3">Sản phẩm tương tự</h3>
        <div class="row">
             <?php foreach ($relatedProducts as $related): ?>
                <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                    <div class="card h-100 product-card">
                        <a href="/webbanhang/Product/show/<?= $related->id ?>">
                            <img src="/webbanhang/<?= htmlspecialchars($related->image ?? 'public/images/default-placeholder.png') ?>" class="card-img-top product-image" alt="<?= htmlspecialchars($related->name) ?>">
                        </a>
                        <div class="card-body">
                            <h5 class="card-title">
                                <a href="/webbanhang/Product/show/<?= $related->id ?>" class="product-name-link">
                                    <?= htmlspecialchars($related->name) ?>
                                </a>
                            </h5>
                            <p class="product-price fw-bold text-danger"><?= number_format($related->price, 0, ',', '.') ?>đ</p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<!-- CSS và JavaScript tùy chỉnh -->
<style>
    /* Tổng quan */
    body { background-color: #f5f5f7; }
    .product-title { font-weight: 600; }
    
    /* Thư viện ảnh */
    .main-image-container img {
        width: 100%;
        height: auto;
        max-height: 500px;
        object-fit: contain;
    }
    .thumbnail-scroll {
        display: flex;
        overflow-x: auto;
        padding-bottom: 10px;
    }
    .thumbnail-item {
        flex: 0 0 80px; /* Không co lại, không giãn ra, rộng 80px */
        width: 80px;
        height: 80px;
        object-fit: cover;
        cursor: pointer;
        border: 2px solid #ddd;
        border-radius: 8px;
        margin-right: 10px;
        transition: border-color 0.3s;
    }
    .thumbnail-item:hover, .thumbnail-item.active {
        border-color: #dc3545; /* Màu đỏ của nút Mua ngay */
    }
    /* Cột thông tin bên phải */
    .product-info-sticky {
        position: -webkit-sticky; /* Dành cho Safari */
        position: sticky;
        top: 150px; /* Khoảng cách từ top khi dính lại */
    }
    .product-policy-box ul {
        list-style-type: none;
        padding-left: 0;
    }
    .product-policy-box ul li {
        margin-bottom: 0.5rem;
    }

    /* Sản phẩm liên quan */
    .product-card { border: 1px solid #e0e0e0; transition: all 0.3s ease; }
    .product-card:hover { transform: translateY(-5px); box-shadow: 0 8px 16px rgba(0,0,0,0.1); }
    .product-image { padding: 10px; height: 180px; object-fit: contain; }
    .product-name-link { color: #333; text-decoration: none; }
    .product-name-link:hover { color: #0d6efd; }
</style>

<script>
// JavaScript để đổi ảnh
function changeImage(thumbnail) {
    const mainImage = document.getElementById('mainProductImage');
    mainImage.src = thumbnail.src;

    document.querySelectorAll('.thumbnail-item').forEach(img => img.classList.remove('active'));
    thumbnail.classList.add('active');
}
</script>

<?php include 'app/views/shares/footer.php'; ?>
