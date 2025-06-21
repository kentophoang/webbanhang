<?php include 'app/views/shares/header.php'; ?>

<div class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h3 class="text-center mb-0">Đặt lại mật khẩu</h3>
                </div>
                <div class="card-body">
                     <?php 
                     // Hiển thị thông báo lỗi nếu có
                     if (isset($_SESSION['flash_message'])): ?>
                        <div class="alert alert-<?= $_SESSION['flash_message']['type'] ?>"><?= $_SESSION['flash_message']['message'] ?></div>
                        <?php unset($_SESSION['flash_message']); ?>
                    <?php endif; ?>

                    <form method="POST" action="/webbanhang/account/updatePassword">
                        <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
                        
                        <div class="mb-3">
                            <label for="password" class="form-label">Mật khẩu mới</label>
                            <input type="password" id="password" name="password" class="form-control" required>
                        </div>
                         <div class="mb-3">
                            <label for="confirm_password" class="form-label">Xác nhận mật khẩu mới</label>
                            <input type="password" id="confirm_password" name="confirm_password" class="form-control" required>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Lưu mật khẩu mới</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'app/views/shares/footer.php'; ?>