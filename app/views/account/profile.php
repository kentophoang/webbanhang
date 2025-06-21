<?php include 'app/views/shares/header.php'; ?>

<div class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <!-- Card 1: Form cập nhật thông tin cá nhân -->
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h3 class="card-title text-center mb-0">Thông tin tài khoản</h3>
                </div>
                <div class="card-body">
                    <?php
                    // Hiển thị thông báo (flash message) nếu có
                    if (isset($_SESSION['flash_message'])) {
                        $flash = $_SESSION['flash_message'];
                        echo '<div class="alert alert-' . htmlspecialchars($flash['type']) . '">' . htmlspecialchars($flash['message']) . '</div>';
                        unset($_SESSION['flash_message']); // Xóa thông báo sau khi hiển thị
                    }
                    ?>
                    
                    <form method="POST" action="/webbanhang/account/updateProfile">
                        <div class="mb-3">
                            <label for="username" class="form-label">Tên đăng nhập</label>
                            <input type="text" id="username" class="form-control" value="<?= htmlspecialchars($account->username) ?>" readonly>
                            <small class="form-text text-muted">Tên đăng nhập không thể thay đổi.</small>
                        </div>
                        <div class="mb-3">
                            <label for="name" class="form-label">Họ và tên</label>
                            <input type="text" id="name" name="name" class="form-control" value="<?= htmlspecialchars($account->name) ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" id="email" name="email" class="form-control" value="<?= htmlspecialchars($account->email ?? '') ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="phone" class="form-label">Số điện thoại</label>
                            <input type="tel" id="phone" name="phone" class="form-control" value="<?= htmlspecialchars($account->phone ?? '') ?>">
                        </div>
                        <div class="mb-3">
                            <label for="address" class="form-label">Địa chỉ giao hàng</label>
                            <textarea id="address" name="address" class="form-control" rows="3"><?= htmlspecialchars($account->address ?? '') ?></textarea>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Cập nhật thông tin</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Card 2: Form đổi mật khẩu -->
            <div class="card shadow-sm mt-4">
                <div class="card-header">
                    <h4 class="card-title mb-0">Đổi mật khẩu</h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="/webbanhang/account/changePassword">
                        <div class="mb-3">
                            <label for="current_password" class="form-label">Mật khẩu hiện tại</label>
                            <input type="password" id="current_password" name="current_password" class="form-control" required>
                        </div>
                         <div class="mb-3">
                            <label for="new_password" class="form-label">Mật khẩu mới</label>
                            <input type="password" id="new_password" name="new_password" class="form-control" required>
                        </div>
                         <div class="mb-3">
                            <label for="confirm_password" class="form-label">Xác nhận mật khẩu mới</label>
                            <input type="password" id="confirm_password" name="confirm_password" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-warning">Đổi mật khẩu</button>
                    </form>
                </div>
            </div>
            
            <!-- Card 3: Vùng nguy hiểm - Xóa tài khoản -->
            <div class="card border-danger mt-4">
                <div class="card-header bg-danger text-white">
                    <h4 class="card-title mb-0">Vùng nguy hiểm</h4>
                </div>
                <div class="card-body">
                    <p>Hành động này không thể hoàn tác. Một khi bạn xóa tài khoản, tất cả dữ liệu liên quan sẽ bị xóa vĩnh viễn.</p>
                    <form id="delete-account-form" method="POST" action="/webbanhang/account/deleteAccount">
                        <div class="mb-3">
                            <label for="current_password_for_delete" class="form-label">Để xác nhận, vui lòng nhập mật khẩu hiện tại của bạn:</label>
                            <input type="password" id="current_password_for_delete" name="current_password_for_delete" class="form-control" required>
                        </div>
                        <!-- Nút này sẽ gọi hàm JavaScript để hiển thị hộp thoại xác nhận -->
                        <button type="button" class="btn btn-danger" onclick="confirmAccountDeletion()">Xóa tài khoản của tôi</button>
                    </form>
                </div>
            </div>
            
        </div>
    </div>
</div>

<script>
// Hàm JavaScript để hiển thị một hộp thoại xác nhận trước khi thực hiện hành động nguy hiểm
function confirmAccountDeletion() {
    if (confirm('Bạn có chắc chắn muốn xóa vĩnh viễn tài khoản của mình không? Hành động này không thể được hoàn tác.')) {
        // Nếu người dùng nhấn "OK", form sẽ được gửi đi
        document.getElementById('delete-account-form').submit();
    }
    // Nếu người dùng nhấn "Cancel", không có gì xảy ra
}
</script>

<?php include 'app/views/shares/footer.php'; ?>
