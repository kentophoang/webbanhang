<?php include 'app/views/shares/header.php'; ?>

<div class="container mt-4 mb-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">Quản lý Sản phẩm</h1>
        <a href="/webbanhang/product/add" class="btn btn-primary">
            <i class="bi bi-plus-circle me-2"></i>Thêm sản phẩm mới
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-light text-center">
                        <tr>
                            <th style="width: 5%;">ID</th>
                            <th style="width: 10%;">Hình ảnh</th>
                            <th>Tên sản phẩm</th>
                            <th style="width: 15%;">Giá</th>
                            <th style="width: 15%;">Danh mục</th>
                            <th style="width: 15%;">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($products)): ?>
                            <tr>
                                <td colspan="6" class="text-center text-muted">Chưa có sản phẩm nào.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($products as $product): ?>
                            <tr>
                                <td class="text-center"><?= $product->id ?></td>
                                <td class="text-center">
                                    <img src="/webbanhang/<?= htmlspecialchars($product->image ?? 'public/images/default-placeholder.png') ?>" alt="<?= htmlspecialchars($product->name) ?>" style="width: 70px; height: 70px; object-fit: contain;">
                                </td>
                                <td><?= htmlspecialchars($product->name) ?></td>
                                <td class="text-danger fw-bold">
                                    <?= number_format($product->price, 0, ',', '.') ?>đ
                                </td>
                                <td><?= htmlspecialchars($product->category_name ?? 'Chưa phân loại') ?></td>
                                <td class="text-center">
                                    <a href="/webbanhang/product/edit/<?= $product->id ?>" class="btn btn-sm btn-warning mb-1" title="Sửa">
                                        <i class="bi bi-pencil-square"></i> Sửa
                                    </a>
                                    <a href="/webbanhang/product/delete/<?= $product->id ?>" class="btn btn-sm btn-danger mb-1" title="Xóa" onclick="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này không?');">
                                        <i class="bi bi-trash"></i> Xóa
                                    </a>
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