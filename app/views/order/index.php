<?php include 'app/views/shares/header.php'; ?>

<div class="container mt-4">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h1 class="mb-0 h3">Quản lý Đơn hàng</h1>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Mã ĐH</th>
                            <th>Tên khách hàng</th>
                            <th>Điện thoại</th>
                            <th>Địa chỉ</th>
                            <th>Ngày đặt</th>
                            <th class="text-end">Tổng tiền</th>
                            <th>Trạng thái</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($orders)): ?>
                            <tr>
                                <td colspan="7" class="text-center text-muted">Chưa có đơn hàng nào.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($orders as $order): ?>
                            <tr>
                                <td>#<?= $order->id ?></td>
                                <td><?= htmlspecialchars($order->name) ?></td>
                                <td><?= htmlspecialchars($order->phone) ?></td>
                                <td><?= htmlspecialchars($order->address) ?></td>
                                <td><?= date('d/m/Y H:i', strtotime($order->order_date)) ?></td>
                                <td class="text-end fw-bold text-danger">
                                    <?= number_format($order->total_amount ?? 0, 0, ',', '.') ?>đ
                                </td>
                                <td>
                                    <!-- Phần trạng thái này bạn có thể nâng cấp trong tương lai -->
                                    <span class="badge bg-warning">Đang xử lý</span>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include 'app/views/shares/footer.php'; ?>
