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
    max-width: 800px;
    margin: 0 auto;
    padding: 40px 20px;
}

/* Form Container */
.form-container {
    background: linear-gradient(145deg, #ffffff, #f1f3f5);
    border-radius: 15px;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    padding: 35px;
    margin-bottom: 25px;
    border: 1px solid rgba(0, 123, 255, 0.1);
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

/* Form Styling */
.form-group {
    margin-bottom: 30px;
}

.form-group label {
    font-weight: 600;
    color: #212529;
    margin-bottom: 12px;
    display: block;
    font-size: 1.2rem;
    letter-spacing: 0.5px;
}

.form-control {
    border-radius: 10px;
    border: 1px solid #ced4da;
    padding: 14px;
    font-size: 1.1rem;
    transition: all 0.3s ease;
    background-color: #fff;
    box-shadow: inset 0 2px 5px rgba(0, 0, 0, 0.05);
}

.form-control:focus {
    border-color: #007bff;
    box-shadow: 0 0 10px rgba(0, 123, 255, 0.3);
    outline: none;
}

/* Button Styling */
.btn-primary {
    background-color: #007bff;
    border-color: #007bff;
    padding: 14px 35px;
    font-weight: 600;
    border-radius: 10px;
    font-size: 1.2rem;
    transition: all 0.3s ease;
    letter-spacing: 0.5px;
}

.btn-primary:hover {
    background-color: #0056b3;
    border-color: #0056b3;
    transform: translateY(-3px);
    box-shadow: 0 6px 15px rgba(0, 123, 255, 0.3);
}

.btn-secondary {
    background-color: #6c757d;
    border-color: #6c757d;
    padding: 14px 35px;
    font-weight: 600;
    border-radius: 10px;
    font-size: 1.2rem;
    transition: all 0.3s ease;
    letter-spacing: 0.5px;
}

.btn-secondary:hover {
    background-color: #5a6268;
    border-color: #5a6268;
    transform: translateY(-3px);
    box-shadow: 0 6px 15px rgba(108, 117, 125, 0.3);
}

/* Alert Styling */
.alert-danger {
    background-color: #fff1f1;
    border: 1px solid #ffd4d4;
    color: #dc3545;
    border-radius: 10px;
    padding: 20px;
    margin-bottom: 30px;
    box-shadow: 0 4px 15px rgba(220, 53, 69, 0.1);
}

.alert-danger ul {
    margin: 0;
    padding-left: 25px;
}

/* Responsive Design */
@media (max-width: 768px) {
    .container {
        padding: 30px 15px;
    }

    h1 {
        font-size: 2rem;
    }

    .form-container {
        padding: 25px;
    }

    .btn-primary, .btn-secondary {
        width: 100%;
        padding: 14px;
    }
}
</style>

<div class="container">
    <h1>Thêm người dùng mới</h1>
    <div class="form-container">
        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
        <form method="POST" action="/webbanhang/User/save">
            <div class="form-group">
                <label for="username">Tên người dùng:</label>
                <input type="text" id="username" name="username" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="password">Mật khẩu:</label>
                <input type="password" id="password" name="password" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="full_name">Họ tên:</label>
                <input type="text" id="full_name" name="full_name" class="form-control">
            </div>
            <button type="submit" class="btn btn-primary">Thêm người dùng</button>
        </form>
    </div>
    <a href="/webbanhang/User" class="btn btn-secondary mt-2">Quay lại danh sách</a>
</div>
<?php include 'app/views/shares/footer.php'; ?>