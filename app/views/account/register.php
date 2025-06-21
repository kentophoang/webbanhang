<?php include 'app/views/shares/header.php'; ?>

<div class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h3 class="card-title text-center mb-0">Đăng ký tài khoản</h3>
                </div>
                <div class="card-body">
                    <?php if (isset($errors) && !empty($errors)): ?>
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                <?php foreach ($errors as $error): ?>
                                    <li><?php echo htmlspecialchars($error); ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                    
                    <form method="POST" action="/webbanhang/account/save">
                        <div class="mb-3">
                            <label for="fullname" class="form-label">Họ và tên</label>
                            <input type="text" id="fullname" name="fullname" class="form-control" value="<?= htmlspecialchars($old_input['fullname'] ?? '') ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="username" class="form-label">Tên đăng nhập</label>
                             <input type="text" id="username" name="username" class="form-control" value="<?= htmlspecialchars($old_input['username'] ?? '') ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Mật khẩu</label>
                            <input type="password" id="password" name="password" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="confirmpassword" class="form-label">Xác nhận mật khẩu</label>
                            <input type="password" id="confirmpassword" name="confirmpassword" class="form-control" required>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Đăng ký</button>
                        </div>
                    </form>
                    <div class="text-center mt-3">
                        <p>Đã có tài khoản? <a href="/webbanhang/account/login">Đăng nhập ngay</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'app/views/shares/footer.php'; ?>