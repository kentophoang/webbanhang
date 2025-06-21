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
                            <!-- Cột thông tin chung -->
                            <div class="col-md-6">
                                <h4>Thông tin chung</h4>
                                <hr>
                                <div class="mb-3">
                                    <label for="name" class="form-label">Tên sản phẩm</label>
                                    <input type="text" id="name" name="name" class="form-control" value="<?= htmlspecialchars($product->name) ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="price" class="form-label">Giá</label>
                                    <input type="number" id="price" name="price" class="form-control" value="<?= htmlspecialchars($product->price) ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="category_id" class="form-label">Danh mục</label>
                                    <select id="category_id" name="category_id" class="form-select" required>
                                        <?php foreach ($categories as $category): ?>
                                            <option value="<?= $category->id ?>" <?= ($product->category_id == $category->id) ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($category->name) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="description" class="form-label">Mô tả</label>
                                    <textarea id="description" name="description" class="form-control" rows="5"><?= htmlspecialchars($product->description) ?></textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="image" class="form-label">Hình ảnh đại diện</label>
                                    <input type="file" id="image" name="image" class="form-control">
                                    <input type="hidden" name="existing_image" value="<?= htmlspecialchars($product->image) ?>">
                                    <?php if ($product->image): ?>
                                        <img src="/webbanhang/<?= htmlspecialchars($product->image) ?>" alt="Current Image" class="img-thumbnail mt-2" style="max-width: 150px;">
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- Cột thông số kỹ thuật động -->
                            <div class="col-md-6">
                                <h4>Thông số kỹ thuật</h4>
                                <hr>
                                <!-- SỬA LỖI Ở ĐÂY: đổi $template->spec_key thành $template->spec_name -->
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
