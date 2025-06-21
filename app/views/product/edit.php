<?php include 'app/views/shares/header.php'; ?>

<div class="container mt-4 mb-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h3 class="card-title mb-0">Chỉnh sửa sản phẩm: <?= htmlspecialchars($product->name) ?></h3>
                </div>
                <div class="card-body">
                    <form method="POST" action="/webbanhang/product/update" enctype="multipart/form-data">
                        <input type="hidden" name="id" value="<?= $product->id ?>">
                        
                        <div class="row">
                            <div class="col-md-6">
                                <h4>Thông tin chung</h4>
                                <hr>
                                </div>

                            <div class="col-md-6">
                                <h4>Thông số kỹ thuật</h4>
                                <hr>
                                <?php if (empty($specTemplates)): ?>
                                    <div class="alert alert-info">Chưa có mẫu thông số nào cho danh mục này. Bạn có thể thêm chúng trong phần <a href="/webbanhang/category/edit/<?=$product->category_id?>">quản lý danh mục</a>.</div>
                                <?php else: ?>
                                    <?php foreach ($specTemplates as $template): ?>
                                        <div class="mb-3">
                                            <label for="spec_<?= htmlspecialchars($template->spec_name) ?>" class="form-label"><?= htmlspecialchars($template->spec_name) ?></label>
                                            <input 
                                                type="text" 
                                                id="spec_<?= htmlspecialchars($template->spec_name) ?>" 
                                                name="specs[<?= htmlspecialchars($template->spec_name) ?>]"
                                                class="form-control"
                                                value="<?= htmlspecialchars($currentSpecs[$template->spec_name] ?? '') ?>">
                                        </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end mt-4">
                            <a href="/webbanhang/Product" class="btn btn-secondary me-2">Hủy</a>
                            <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'app/views/shares/footer.php'; ?>