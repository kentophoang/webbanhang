<?php include 'app/views/shares/header.php'; ?>

<div class="container mt-4 mb-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title mb-0">Thêm sản phẩm mới</h3>
                </div>
                <div class="card-body">
                    <?php if (isset($errors) && !empty($errors)): ?>
                        <div class="alert alert-danger">
                            <ul>
                                <?php foreach ($errors as $error): ?>
                                    <li><?php echo htmlspecialchars($error); ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="/webbanhang/Product/save" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="name" class="form-label">Tên sản phẩm</label>
                            <input type="text" id="name" name="name" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Mô tả</label>
                            <textarea id="description" name="description" class="form-control" rows="5"></textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="price" class="form-label">Giá</label>
                                <div class="input-group">
                                    <input type="number" id="price" name="price" class="form-control" required step="1000" min="0">
                                    <span class="input-group-text">VND</span>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="category_id" class="form-label">Danh mục</label>
                                <select id="category_id" name="category_id" class="form-select" required>
                                    <option value="">-- Chọn danh mục --</option>
                                    <?php foreach ($categories as $category): ?>
                                        <option value="<?php echo $category->id; ?>">
                                            <?php echo htmlspecialchars($category->name); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="image" class="form-label">Hình ảnh sản phẩm</label>
                            <input type="file" id="image" name="image" class="form-control">
                        </div>

                        <div class="d-flex justify-content-end">
                            <a href="/webbanhang/Product" class="btn btn-secondary me-2">Hủy</a>
                            <button type="submit" class="btn btn-primary">Lưu sản phẩm</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'app/views/shares/footer.php'; ?>