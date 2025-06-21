<?php include 'app/views/shares/header.php'; ?>

<div class="container mt-4">
    <h1 class="mb-4">Quản lý Khách hàng</h1>

    <?php
    // Hiển thị thông báo (flash message) nếu có
    if (isset($_SESSION['flash_message'])) {
        $flash = $_SESSION['flash_message'];
        echo '<div class="alert alert-' . htmlspecialchars($flash['type']) . '">' . htmlspecialchars($flash['message']) . '</div>';
        unset($_SESSION['flash_message']);
    }
    ?>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Họ và tên</th>
                            <th>Tên đăng nhập</th>
                            <th>Email</th>
                            <th>Vai trò</th>
                            <th style="width: 100px;">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?= $user->id ?></td>
                            <td><?= htmlspecialchars($user->name) ?></td>
                            <td><?= htmlspecialchars($user->username) ?></td>
                            <td><?= htmlspecialchars($user->email ?? 'Chưa có') ?></td>
                            <td>
                                <?php if($user->role == 'admin'): ?>
                                    <span class="badge bg-success">Admin</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">User</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <!-- Không cho phép xóa chính mình -->
                                <?php if($user->id != $_SESSION['user_id']): ?>
                                    <a href="/webbanhang/user/destroy/<?= $user->id ?>" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc chắn muốn xóa người dùng này?');">Xóa</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include 'app/views/shares/footer.php'; ?>
