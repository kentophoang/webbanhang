<?php include 'app/views/shares/header.php'; ?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h3 class="mb-0">Quản lý Danh mục</h3>
                    <a href="/webbanhang/category/add" class="btn btn-light">
                        <i class="bi bi-plus-circle"></i> Thêm Danh mục mới
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 10%;">ID</th>
                                    <th>Tên Danh mục</th>
                                    <th style="width: 20%;">Số sản phẩm</th>
                                    <th style="width: 15%;">Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($categories as $category): ?>
                                <tr>
                                    <td><?= $category->id ?></td>
                                    <td><?= htmlspecialchars($category->name) ?></td>
                                    <td>
                                        <span class="badge bg-secondary"><?= $category->product_count ?> sản phẩm</span>
                                    </td>
                                    <td>
                                        <a href="/webbanhang/category/edit/<?= $category->id ?>" class="btn btn-sm btn-warning" title="Sửa">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        <a href="/webbanhang/category/delete/<?= $category->id ?>" class="btn btn-sm btn-danger" title="Xóa" onclick="return confirm('Bạn có chắc chắn muốn xóa danh mục này? Tất cả sản phẩm thuộc danh mục này cũng sẽ bị ảnh hưởng.');">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'app/views/shares/footer.php'; ?>
