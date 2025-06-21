<?php include 'app/views/shares/header.php'; ?>

<div class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h3 class="card-title text-center mb-0">Đăng nhập</h3>
                </div>
                <div class="card-body">
                    <!-- Hiển thị lỗi nếu có -->
                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
                    <?php endif; ?>
                    
                    <form method="POST" action="/webbanhang/account/checkLogin">
                        <div class="mb-3">
                            <label for="username" class="form-label">Tên đăng nhập</label>
                            <input type="text" id="username" name="username" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Mật khẩu</label>
                            <input type="password" id="password" name="password" class="form-control" required>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Đăng nhập</button>
                        </div>
                        <div class="text-center mt-2">
                            <a href="/webbanhang/account/forgotPassword">Quên mật khẩu?</a>
                        </div>
                    </form>
                     <div class="text-center mt-3">
                        <p>Chưa có tài khoản? <a href="/webbanhang/account/register">Đăng ký ngay</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'app/views/shares/footer.php'; ?>