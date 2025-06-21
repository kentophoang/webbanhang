<?php include 'app/views/shares/header.php'; ?>
<div class="container">
    <h1 class="my-4">Thêm danh mục mới</h1>
    <form action="/webbanhang/category/store" method="POST">
        <div class="mb-3">
            <label for="name" class="form-label">Tên danh mục</label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>
        <button type="submit" class="btn btn-primary">Lưu</button>
        <a href="/webbanhang/category" class="btn btn-secondary">Hủy</a>
    </form>
</div>
<?php include 'app/views/shares/footer.php'; ?>