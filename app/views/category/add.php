<?php include 'app/views/shares/header.php'; ?>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h3 class="mb-0">Thêm Danh mục mới</h3>
                </div>
                <div class="card-body">
                    <form action="/webbanhang/category/create" method="POST">
                        <div class="mb-3">
                            <label for="name" class="form-label fw-bold">Tên Danh mục</label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="Ví dụ: Điện thoại, Laptop..." required>
                        </div>
                        <div class="mb-3">
                            <label for="parent_id" class="form-label fw-bold">Thuộc danh mục cha</label>
                            <select class="form-select" id="parent_id" name="parent_id">
                                <option value="">-- Là danh mục gốc --</option>
                                <?php foreach ($categories as $cat): ?>
                                    <option value="<?= $cat->id ?>"><?= htmlspecialchars($cat->name) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="d-flex justify-content-end">
                            <a href="/webbanhang/category" class="btn btn-secondary me-2">Hủy</a>
                            <button type="submit" class="btn btn-primary">Lưu</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include 'app/views/shares/footer.php'; ?>