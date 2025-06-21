<?php
class AccountModel
{
    private $conn;
    private $table_name = "account";

    public function __construct($db)
    {
        $this->conn = $db;
    }

    /**
     * Lấy thông tin tài khoản bằng username
     */
    public function getAccountByUsername($username)
    {
        $query = "SELECT * FROM " . $this->table_name . " WHERE username = :username";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_OBJ);
        return $result;
    }

    /**
     * Tạo tài khoản mới (đã sửa lỗi bảo mật và logic)
     */
    public function save($username, $name, $password, $role = "user")
    {
        // === CẢI TIẾN: Kiểm tra xem username đã tồn tại chưa ===
        if ($this->getAccountByUsername($username)) {
            // Trả về false nếu username đã được sử dụng
            return false;
        }

        $query = "INSERT INTO " . $this->table_name . " (name, username, password, role)
                  VALUES (:name, :username, :password, :role)";
        
        $stmt = $this->conn->prepare($query);

        // Làm sạch dữ liệu
        $name = htmlspecialchars(strip_tags($name));
        $username = htmlspecialchars(strip_tags($username));
        $role = htmlspecialchars(strip_tags($role));
        
        // === SỬA LỖI BẢO MẬT: Băm mật khẩu trước khi lưu ===
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Gán dữ liệu vào câu lệnh
        $stmt->bindParam(':name', $name); // SỬA LỖI LOGIC: Thêm name
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $hashed_password); // Lưu mật khẩu đã được băm
        $stmt->bindParam(':role', $role);

        // Thực thi câu lệnh
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
    // Dán 2 hàm mới này vào bên trong class AccountModel

    /**
     * Lấy thông tin tài khoản bằng ID
     */
    public function getAccountById($id)
    {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    /**
     * Cập nhật thông tin cá nhân (tên, điện thoại, địa chỉ)
     */
    public function updateProfile($id, $name, $email, $phone, $address)
    {
        // THÊM: Cập nhật cả trường 'email'
        $query = "UPDATE " . $this->table_name . " 
                  SET name = :name, email = :email, phone = :phone, address = :address 
                  WHERE id = :id";
                  
        $stmt = $this->conn->prepare($query);

        // Làm sạch dữ liệu
        $name = htmlspecialchars(strip_tags($name));
        $email = htmlspecialchars(strip_tags($email)); // THÊM DÒNG NÀY
        $phone = htmlspecialchars(strip_tags($phone));
        $address = htmlspecialchars(strip_tags($address));
        $id = intval($id);

        // Gán dữ liệu
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email); // THÊM DÒNG NÀY
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':address', $address);
        $stmt->bindParam(':id', $id);

        // Thực thi
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
    // Dán các hàm mới này vào file AccountModel.php

    public function getAccountByEmail($email)
    {
        $query = "SELECT * FROM " . $this->table_name . " WHERE email = :email";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public function createPasswordResetToken($email, $token)
    {
        // Xóa token cũ của email này nếu có
        $this->conn->prepare("DELETE FROM password_resets WHERE email = :email")->execute(['email' => $email]);
        
        $query = "INSERT INTO password_resets (email, token) VALUES (:email, :token)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':token', $token);
        return $stmt->execute();
    }

    public function getResetToken($token)
    {
        $query = "SELECT * FROM password_resets WHERE token = :token";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':token', $token);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_OBJ);
    }
    
    public function updatePasswordByEmail($email, $newPassword)
    {
        $hashed_password = password_hash($newPassword, PASSWORD_DEFAULT);
        $query = "UPDATE " . $this->table_name . " SET password = :password WHERE email = :email";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':password', $hashed_password);
        $stmt->bindParam(':email', $email);
        return $stmt->execute();
    }

    public function deleteResetToken($token)
    {
        $query = "DELETE FROM password_resets WHERE token = :token";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':token', $token);
        return $stmt->execute();
    }
    public function updatePasswordById($id, $newPassword)
    {
        $hashed_password = password_hash($newPassword, PASSWORD_DEFAULT);
        $query = "UPDATE " . $this->table_name . " SET password = :password WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':password', $hashed_password);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
    /**
     * Xóa một tài khoản khỏi database dựa trên ID
     */
    public function deleteAccountById($id)
    {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        
        // Trả về true nếu thực thi thành công
        return $stmt->execute();
    }

    /**
     * CẢI TIẾN: Thêm hàm kiểm tra mật khẩu khi đăng nhập
     */
    public function verifyPassword($username, $password)
    {
        $account = $this->getAccountByUsername($username);

        // Nếu không tìm thấy tài khoản, hoặc mật khẩu không khớp
        if (!$account || !password_verify($password, $account->password)) {
            return false;
        }

        // Đăng nhập thành công, trả về thông tin tài khoản
        return $account;
    }
    public function getAccounts()
    {
        // Sắp xếp theo ID giảm dần để tài khoản mới nhất lên đầu
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY id DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

}