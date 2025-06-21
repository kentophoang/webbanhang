<?php include 'app/views/shares/header.php'; ?>

<div class="container mt-4 mb-5">
    <h1>Giỏ hàng của bạn</h1>

    <?php if (empty($cart)): ?>
        <div class="text-center p-5 border rounded-3 bg-light">
            <p class="lead">Giỏ hàng của bạn đang trống.</p>
            <a href="/webbanhang/Product" class="btn btn-primary">Tiếp tục mua sắm</a>
        </div>
    <?php else: ?>
        <div class="row">
            <div class="col-lg-8">
                <?php $subtotal = 0; ?>
                <?php foreach ($cart as $id => $item): ?>
                    <?php
                        $line_total = $item['price'] * $item['quantity'];
                        $subtotal += $line_total;
                    ?>
                    <div class="cart-item-card card mb-3">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-md-2 col-4">
                                    <img src="/webbanhang/<?php echo htmlspecialchars($item['image']); ?>" class="img-fluid rounded" alt="<?php echo htmlspecialchars($item['name']); ?>">
                                </div>
                                <div class="col-md-4 col-8">
                                    <h5 class="mb-1"><?php echo htmlspecialchars($item['name']); ?></h5>
                                    <p class="text-muted mb-0">Giá: <?php echo number_format($item['price'], 0, ',', '.'); ?> VND</p>
                                </div>
                                <div class="col-md-3 col-6 mt-2 mt-md-0">
                                    <div class="quantity-selector">
                                        <button class="btn btn-sm btn-outline-secondary">-</button>
                                        <input type="text" class="form-control form-control-sm text-center" value="<?php echo $item['quantity']; ?>" readonly>
                                        <button class="btn btn-sm btn-outline-secondary">+</button>
                                    </div>
                                </div>
                                <div class="col-md-3 col-6 mt-2 mt-md-0 text-md-end">
                                    <p class="fw-bold mb-1"><?php echo number_format($line_total, 0, ',', '.'); ?> VND</p>
                                    <a href="/webbanhang/Product/removeFromCart/<?php echo $id; ?>" class="text-danger small">Xóa</a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="col-lg-4">
                <div class="summary-card card">
                    <div class="card-body">
                        <h4 class="card-title">Tóm tắt đơn hàng</h4>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Tạm tính</span>
                            <span><?php echo number_format($subtotal, 0, ',', '.'); ?> VND</span>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <span>Phí vận chuyển</span>
                            <span>Miễn phí</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between fw-bold h5">
                            <span>Tổng cộng</span>
                            <span><?php echo number_format($subtotal, 0, ',', '.'); ?> VND</span>
                        </div>
                        <div class="d-grid mt-3">
                            <a href="/webbanhang/Product/checkout" class="btn btn-primary btn-lg">Tiến hành thanh toán</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<style>
    .cart-item-card, .summary-card {
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }
    .summary-card .card-body {
        padding: 1.5rem;
    }
    .quantity-selector {
        display: flex;
        max-width: 120px;
    }
    .quantity-selector .form-control {
        border-left: none;
        border-right: none;
    }
    .quantity-selector .btn {
        border-color: #ced4da;
    }
</style>

<?php include 'app/views/shares/footer.php'; ?>