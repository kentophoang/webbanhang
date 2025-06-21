<?php include 'app/views/shares/header.php'; ?>

<style>
/* CSS cho thumbnail */
.thumbnail-scroll {
    display: flex;
    overflow-x: auto;
    padding-bottom: 10px;
    margin-top: 10px;
}
.thumbnail-item {
    flex: 0 0 80px;
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
    border-color: #dc3545;
}

/* CSS cho cột thông tin dính lại khi cuộn */
.product-info-sticky {
    position: -webkit-sticky;
    position: sticky;
    top: 150px;
}

/* CSS cho phần chọn phiên bản */
.variant-group { margin-bottom: 1rem; }
.variant-group-label { font-weight: 600; margin-bottom: 0.5rem; display: block; }
.variant-btn {
    display: inline-block;
    padding: 0.5rem 1rem;
    border: 1px solid #ced4da;
    border-radius: 8px;
    margin-right: 0.5rem;
    margin-bottom: 0.5rem;
    cursor: pointer;
    text-decoration: none;
    color: #212529;
    background-color: #fff;
    transition: all 0.2s ease-in-out;
}
.variant-btn:hover { border-color: #0d6efd; }
.variant-btn.active {
    border-color: #0d6efd;
    background-color: #e7f1ff;
    color: #0d6efd;
    font-weight: 500;
}

/* CSS cho hộp khuyến mãi */
.promo-box {
    border: 2px dashed #dc3545;
    background-color: #fff5f5;
    border-radius: 8px;
    padding: 1rem;
    margin-top: 1.5rem;
}
.promo-box-header { font-weight: bold; color: #dc3545; margin-bottom: 0.75rem; }
.promo-box ul { padding-left: 1.25rem; margin-bottom: 0; }
.promo-box ul li { margin-bottom: 0.5rem; }

/* CSS cho khu vực chọn số lượng */
.quantity-selector { display: flex; max-width: 150px; }
.quantity-selector .form-control { text-align: center; border-left: none; border-right: none; }
.quantity-selector .btn { border-color: #ced4da; }
</style>


<div class="container my-5">
    <div class="row">
        <div class="col-lg-7">
            <div class="main-image-container border rounded-3 mb-3">
                <a id="mainImageLink" href="/webbanhang/<?= htmlspecialchars($product->image ?? 'public/images/default-placeholder.png') ?>" data-fancybox="gallery">
                    <img src="/webbanhang/<?= htmlspecialchars($product->image ?? 'public/images/default-placeholder.png') ?>" id="mainProductImage" class="img-fluid w-100" style="cursor: pointer;">
                </a>
            </div>

            <div class="thumbnail-scroll">
                <?php if ($product->image): ?>
                    <a href="/webbanhang/<?= htmlspecialchars($product->image) ?>" data-fancybox="gallery" class="d-none"></a>
                    <img src="/webbanhang/<?= htmlspecialchars($product->image) ?>" class="thumbnail-item active" onclick="changeImage(this)">
                <?php endif; ?>
                <?php foreach ($productImages as $img): ?>
                    <a href="/webbanhang/<?= htmlspecialchars($img->image_url) ?>" data-fancybox="gallery" class="d-none"></a>
                    <img src="/webbanhang/<?= htmlspecialchars($img->image_url) ?>" class="thumbnail-item" onclick="changeImage(this)">
                <?php endforeach; ?>
            </div>
            
            <?php if (!empty($productSpecs)): ?>
            <div class="product-specs-container mt-5">
                <h3 class="mb-3">Thông số kỹ thuật</h3>
                <table class="table table-bordered table-striped">
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
            <?php endif; ?>
        </div>

        <div class="col-lg-5">
            <div class="product-info-sticky">
                <h1 class="product-title h2"><?= htmlspecialchars($product->name) ?></h1>
                
                <div class="variants-container mt-4">
                    <div class="variant-group">
                        <span class="variant-group-label">Dung lượng:</span>
                        <a href="#" class="variant-btn active">128GB</a>
                        <a href="#" class="variant-btn">256GB</a>
                        <a href="#" class="variant-btn">512GB</a>
                    </div>
                    <div class="variant-group">
                        <span class="variant-group-label">Màu sắc:</span>
                        <a href="#" class="variant-btn active">Xanh</a>
                        <a href="#" class="variant-btn">Hồng</a>
                        <a href="#" class="variant-btn">Vàng</a>
                        <a href="#" class="variant-btn">Đen</a>
                    </div>
                </div>

                <div class="price-box my-3">
                    <span class="product-price-detail h3 text-danger fw-bold">
                        <?= number_format($product->price, 0, ',', '.') ?>đ
                    </span>
                </div>

                <div class="promo-box">
                    <div class="promo-box-header"><i class="bi bi-gift-fill me-2"></i>Khuyến mãi đặc biệt</div>
                    <ul>
                        <li>Giảm ngay 500.000đ khi thanh toán qua VNPAY.</li>
                        <li>Tặng ốp lưng và dán màn hình trị giá 300.000đ.</li>
                        <li>Thu cũ đổi mới - Trợ giá đến 2 triệu đồng.</li>
                    </ul>
                </div>
                
                <form action="/webbanhang/Product/addToCart/<?= $product->id ?>" method="POST" class="mt-4">
                    <div class="mb-3">
                        <label for="quantity" class="form-label fw-bold">Số lượng:</label>
                        <div class="quantity-selector">
                            <button type="button" class="btn btn-outline-secondary" onclick="changeQuantity(-1)">-</button>
                            <input type="number" id="quantity" name="quantity" class="form-control" value="1" min="1">
                            <button type="button" class="btn btn-outline-secondary" onclick="changeQuantity(1)">+</button>
                        </div>
                    </div>
                    
                    <div class="d-grid gap-2">
                         <button type="submit" class="btn btn-danger btn-lg">
                            <i class="bi bi-cart-plus-fill me-2"></i>THÊM VÀO GIỎ HÀNG
                         </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="related-products mt-5">
        </div>
</div>

<script>
// Hàm thay đổi ảnh chính khi click vào thumbnail
function changeImage(thumbnail) {
    const mainImage = document.getElementById('mainProductImage');
    const mainImageLink = document.getElementById('mainImageLink');
    const newSrc = thumbnail.src;

    mainImage.src = newSrc;
    mainImageLink.href = newSrc; // Cập nhật link cho Fancybox

    document.querySelectorAll('.thumbnail-item').forEach(img => img.classList.remove('active'));
    thumbnail.classList.add('active');
}

// Hàm thay đổi số lượng
function changeQuantity(amount) {
    const quantityInput = document.getElementById('quantity');
    let currentValue = parseInt(quantityInput.value);
    let newValue = currentValue + amount;
    if (newValue < 1) {
        newValue = 1;
    }
    quantityInput.value = newValue;
}

// Khởi tạo các thư viện và hiệu ứng khi trang tải xong
document.addEventListener("DOMContentLoaded", function() {
    // Khởi tạo Fancybox
    Fancybox.bind("[data-fancybox]", {
      // Tùy chọn Fancybox nếu cần
    });

    // Xử lý giao diện cho các nút chọn phiên bản
    const variantButtons = document.querySelectorAll('.variant-btn');
    variantButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const siblings = this.parentElement.querySelectorAll('.variant-btn');
            siblings.forEach(sibling => sibling.classList.remove('active'));
            this.classList.add('active');
        });
    });
});
</script>

<?php include 'app/views/shares/footer.php'; ?>