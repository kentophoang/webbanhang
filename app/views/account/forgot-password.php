<?php include 'app/views/shares/header.php'; ?>
<div class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card">
                <div class="card-header"><h3 class="text-center mb-0">Khôi phục mật khẩu</h3></div>
                <div class="card-body">
                    <?php if (isset($_SESSION['flash_message'])): ?>
                        <div class="alert alert-<?= $_SESSION['flash_message']['type'] ?>"><?= $_SESSION['flash_message']['message'] ?></div>
                        <?php unset($_SESSION['flash_message']); ?>
                    <?php endif; ?>
                    <p>Vui lòng nhập email của bạn. Chúng tôi sẽ gửi một link để đặt lại mật khẩu.</p>
                    <form method="POST" action="/webbanhang/account/sendResetLink">
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" id="email" name="email" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Gửi link khôi phục</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include 'app/views/shares/footer.php'; ?>