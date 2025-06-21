<?php include 'app/views/shares/header.php'; ?>

<div class="container my-5">
    <h1 class="mb-4">So sánh sản phẩm</h1>

    <?php if (empty($productsWithSpecs)): ?>
        <div class="alert alert-warning">Không có sản phẩm nào để so sánh.</div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-bordered comparison-table">
                <!-- Phần Header: Tên và ảnh sản phẩm -->
                <thead>
                    <tr>
                        <th class="spec-key-col">Tính năng</th>
                        <?php foreach ($productsWithSpecs as $product): ?>
                            <th class="product-col text-center">
                                <a href="/webbanhang/product/show/<?= $product->id ?>">
                                    <img src="/webbanhang/<?= htmlspecialchars($product->image ?? 'public/images/default-placeholder.png') ?>" alt="<?= htmlspecialchars($product->name) ?>" class="img-fluid mb-2">
                                    <h6 class="fw-bold"><?= htmlspecialchars($product->name) ?></h6>
                                </a>
                                <p class="text-danger fw-bold"><?= number_format($product->price, 0, ',', '.') ?>đ</p>
                                <a href="/webbanhang/product/removeFromCompare/<?= $product->id ?>" class="btn btn-sm btn-outline-danger">Xóa</a>
                            </th>
                        <?php endforeach; ?>
                    </tr>
                </thead>

                <!-- Phần Body: Các thông số kỹ thuật -->
                <tbody>
                    <?php foreach ($allSpecKeys as $specKey): ?>
                        <tr>
                            <td class="spec-key-col fw-bold bg-light"><?= htmlspecialchars($specKey) ?></td>
                            <?php foreach ($productsWithSpecs as $product): ?>
                                <td>
                                    <?= htmlspecialchars($product->specs[$specKey] ?? '–') ?>
                                </td>
                            <?php endforeach; ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>

                <!-- Phần Footer: Nút Thêm vào giỏ hàng -->
                <tfoot>
                    <tr>
                        <td class="spec-key-col"></td>
                        <?php foreach ($productsWithSpecs as $product): ?>
                            <td class="text-center">
                                <a href="/webbanhang/product/addToCart/<?= $product->id ?>" class="btn btn-primary">Thêm vào giỏ hàng</a>
                            </td>
                        <?php endforeach; ?>
                    </tr>
                </tfoot>
            </table>
        </div>
    <?php endif; ?>
</div>

<style>
.comparison-table th, .comparison-table td {
    vertical-align: middle;
}
.spec-key-col {
    width: 20%;
}
.product-col {
    width: 26%; /* (100-20)/3 */
}
.comparison-table img {
    max-height: 150px;
    object-fit: contain;
}
</style>

<?php include 'app/views/shares/footer.php'; ?>
