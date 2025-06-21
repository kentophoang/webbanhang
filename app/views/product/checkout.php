<?php include 'app/views/shares/header.php'; ?>

<div class="container mt-4 mb-5">
    <div class="row">
        <div class="col-lg-7">
            <?php if (isset($account) && !empty($account->phone) && !empty($account->address)): ?>
                
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h3 class="card-title mb-4">Xác nhận thông tin giao hàng</h3>
                        <div class="mb-3">
                            <h6 class="mb-0">Họ và tên:</h6>
                            <p><?php echo htmlspecialchars($account->name); ?></p>
                        </div>
                        <div class="mb-3">
                            <h6 class="mb-0">Số điện thoại:</h6>
                            <p><?php echo htmlspecialchars($account->phone); ?></p>
                        </div>
                        <div class="mb-3">
                            <h6 class="mb-0">Giao đến địa chỉ:</h6>
                            <p><?php echo nl2br(htmlspecialchars($account->address)); ?></p>
                        </div>
                        <a href="/webbanhang/account/profile" class="btn btn-sm btn-outline-secondary">Thay đổi thông tin</a>
                    </div>
                </div>

            <?php else: ?>

                <div class="card shadow-sm">
                    <div class="card-body">
                        <h3 class="card-title mb-4">Thông tin giao hàng</h3>
                        <form id="guest-checkout-form" method="POST" action="/webbanhang/Product/processCheckout">
                            <div class="mb-3">
                                <label for="name" class="form-label">Họ và tên</label>
                                <input type="text" id="name" name="name" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="phone" class="form-label">Số điện thoại</label>
                                <input type="tel" id="phone" name="phone" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="address" class="form-label">Địa chỉ</label>
                                <textarea id="address" name="address" class="form-control" rows="3" required></textarea>
                            </div>
                        </form>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <div class="col-lg-5">
            <div class="card order-summary-card shadow-sm">
                <div class="card-body">
                    <h3 class="card-title mb-4">Đơn hàng của bạn</h3>
                    <ul class="list-group list-group-flush">
                        <?php $subtotal = 0; ?>
                        <?php foreach ($_SESSION['cart'] as $id => $item): ?>
                            <?php
                                $line_total = $item['price'] * $item['quantity'];
                                $subtotal += $line_total;
                            ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <?php echo htmlspecialchars($item['name']); ?>
                                    <small class="d-block text-muted">Số lượng: <?php echo $item['quantity']; ?></small>
                                </div>
                                <span><?php echo number_format($line_total, 0, ',', '.'); ?>đ</span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                    <hr>
                    <div class="d-flex justify-content-between fw-bold h4">
                        <span>Tổng cộng</span>
                        <span><?php echo number_format($subtotal, 0, ',', '.'); ?>đ</span>
                    </div>
                    
                    <div class="d-grid mt-4">
                        <?php if (isset($account) && !empty($account->phone) && !empty($account->address)): ?>
                             <form method="POST" action="/webbanhang/Product/processCheckout">
                                <button type="submit" class="btn btn-primary btn-lg w-100">Xác nhận và Đặt hàng</button>
                            </form>
                        <?php else: ?>
                            <button type="submit" form="guest-checkout-form" class="btn btn-primary btn-lg w-100">Hoàn tất đơn hàng</button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'app/views/shares/footer.php'; ?>