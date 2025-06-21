<?php include 'app/views/shares/header.php'; ?>
<style>
/* General Layout */
body {
    background-color: #f8f9fa;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    margin: 0;
    padding: 0;
}

.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 40px 20px;
}

/* Headings */
h1 {
    font-size: 2.5rem;
    font-weight: 700;
    color: #212529;
    margin-bottom: 35px;
    text-align: center;
    text-transform: uppercase;
    letter-spacing: 1.5px;
    background: linear-gradient(90deg, #007bff, #00c4b4);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

/* Table Styling */
.table-container {
    background: linear-gradient(145deg, #ffffff, #f1f3f5);
    border: 1px solid rgba(0, 123, 255, 0.1);
    border-radius: 15px;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    padding: 30px;
}

.table {
    margin-bottom: 0;
}

.table thead {
    background: linear-gradient(90deg, #007bff, #00c4b4);
    color: #ffffff;
}

.table thead th {
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    padding: 15px;
}

.table tbody tr {
    transition: all 0.3s ease;
}

.table tbody tr:hover {
    background-color: #e9ecef;
    transform: translateY(-2px);
}

.table tbody td {
    padding: 15px;
    vertical-align: middle;
    color: #495057;
}

/* Button Styling */
.btn-success {
    background-color: #28a745;
    border-color: #28a745;
    padding: 10px 20px;
    font-weight: 600;
    border-radius: 10px;
    transition: all 0.3s ease;
    letter-spacing: 0.5px;
}

.btn-success:hover {
    background-color: #218838;
    border-color: #218838;
    transform: translateY(-3px);
    box-shadow: 0 6px 15px rgba(40, 167, 69, 0.3);
}

.btn-info {
    background-color: #17a2b8;
    border-color: #17a2b8;
    padding: 8px 16px;
    font-weight: 600;
    border-radius: 10px;
    transition: all 0.3s ease;
}

.btn-info:hover {
    background-color: #138496;
    border-color: #138496;
    transform: translateY(-3px);
    box-shadow: 0 6px 15px rgba(23, 162, 184, 0.3);
}

.btn-warning {
    background-color: #ffc107;
    border-color: #ffc107;
    padding: 8px 16px;
    font-weight: 600;
    border-radius: 10px;
    transition: all 0.3s ease;
}

.btn-warning:hover {
    background-color: #e0a800;
    border-color: #e0a800;
    transform: translateY(-3px);
    box-shadow: 0 6px 15px rgba(255, 193, 7, 0.3);
}

.btn-danger {
    background-color: #dc3545;
    border-color: #dc3545;
    padding: 8px 16px;
    font-weight: 600;
    border-radius: 10px;
    transition: all 0.3s ease;
}

.btn-danger:hover {
    background-color: #c82333;
    border-color: #c82333;
    transform: translateY(-3px);
    box-shadow: 0 6px 15px rgba(220, 53, 69, 0.3);
}

/* Responsive Design */
@media (max-width: 768px) {
    .container {
        padding: 30px 15px;
    }

    h1 {
        font-size: 2rem;
    }

    .table-container {
        padding: 20px;
    }

    .table thead th,
    .table tbody td {
        font-size: 0.9rem;
        padding: 10px;
    }

    .btn-success, .btn-info, .btn-warning, .btn-danger {
        width: 100%;
        margin-bottom: 10px;
        margin-right: 0;
    }
}
</style>

<div class="container">
    <h1>Danh sách người dùng</h1>
    <a href="/webbanhang/User/add" class="btn btn-success mb-3">Thêm người dùng mới</a>
    <div class="table-container">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tên người dùng</th>
                    <th>Email</th>
                    <th>Họ tên</th>
                    <th>Ngày tạo</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($user->id, ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo htmlspecialchars($user->username, ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo htmlspecialchars($user->email, ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo htmlspecialchars($user->full_name, ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo htmlspecialchars($user->created_at, ENT_QUOTES, 'UTF-8'); ?></td>
                        <td>
                            <a href="/webbanhang/User/show/<?php echo $user->id; ?>" class="btn btn-info btn-sm">Xem</a>
                            <a href="/webbanhang/User/edit/<?php echo $user->id; ?>" class="btn btn-warning btn-sm">Sửa</a>
                            <a href="/webbanhang/User/delete/<?php echo $user->id; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc chắn muốn xóa người dùng này?');">Xóa</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php include 'app/views/shares/footer.php'; ?>