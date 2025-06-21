<?php include 'app/views/shares/header.php'; ?>
<div class="container mt-5">
    <h1>Chỉnh sửa Danh mục</h1>
    
    <?php
    if (isset($_SESSION['flash_message'])) {
        $flash = $_SESSION['flash_message'];
        echo '<div class="alert alert-' . htmlspecialchars($flash['type']) . '">' . htmlspecialchars($flash['message']) . '</div>';
        unset($_SESSION['flash_message']);
    }
    ?>

    <form action="/webbanhang/category/update/<?= $category->id ?>" method="POST">
        <div class="mb-3">
            <label for="name" class="form-label">Tên Danh mục</label>
            <input type="text" class="form-control" id="name" name="name" value="<?= htmlspecialchars($category->name) ?>" required>
        </div>

        <div class="mb-4">
            <label for="parent_id" class="form-label fw-bold">Thuộc danh mục cha</label>
            <select class="form-select" id="parent_id" name="parent_id">
                <option value="">-- Là danh mục gốc --</option>
                <?php foreach ($categories as $cat): ?>
                    <?php if ($cat->id != $category->id): // Ngăn chọn chính nó làm cha ?>
                        <option value="<?= $cat->id ?>" <?= ($cat->id == $category->parent_id) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($cat->name) ?>
                        </option>
                    <?php endif; ?>
                <?php endforeach; ?>
            </select>
        </div>

        <fieldset class="border p-3 mb-4">
            <legend class="w-auto h5">Mẫu thông số kỹ thuật</legend>
            <div id="specs-container">
                <?php foreach ($specTemplates as $spec): ?>
                <div class="input-group mb-2">
                    <input type="text" class="form-control" name="specs[]" value="<?= htmlspecialchars($spec->spec_name) ?>" placeholder="Tên thông số (VD: RAM)">
                    <button class="btn btn-outline-danger" type="button" onclick="removeSpec(this)">Xóa</button>
                </div>
                <?php endforeach; ?>
            </div>
            <button class="btn btn-success mt-2" type="button" onclick="addSpec()">Thêm thông số</button>
        </fieldset>

        <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
        <a href="/webbanhang/category" class="btn btn-secondary">Hủy</a>
    </form>
</div>

<script>
function addSpec() {
    const container = document.getElementById('specs-container');
    const newSpec = document.createElement('div');
    newSpec.className = 'input-group mb-2';
    newSpec.innerHTML = `
        <input type="text" class="form-control" name="specs[]" placeholder="Tên thông số (VD: RAM)">
        <button class="btn btn-outline-danger" type="button" onclick="removeSpec(this)">Xóa</button>
    `;
    container.appendChild(newSpec);
}

function removeSpec(button) {
    button.parentElement.remove();
}
</script>
<?php include 'app/views/shares/footer.php'; ?>