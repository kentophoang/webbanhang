<?php
// Chỉ cần require BaseController, các file Model sẽ được tự động nạp.
require_once 'app/controllers/BaseController.php';

// class AccountController giờ sẽ kế thừa từ BaseController
class AccountController extends BaseController
{
    private $accountModel;

    public function __construct()
    {
        // Gọi constructor của lớp cha để có kết nối $this->db
        parent::__construct();
        // Sử dụng hàm loadModel để tạo đối tượng AccountModel
        $this->accountModel = $this->loadModel('AccountModel');
    }

    /**
     * Hiển thị trang đăng ký
     */
    public function register()
    {
        include_once 'app/views/account/register.php';
    }

    /**
     * Hiển thị trang đăng nhập
     */
    public function login()
    {
        include_once 'app/views/account/login.php';
    }

    /**
     * Xử lý lưu thông tin đăng ký
     */
    public function save()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = $_POST['username'] ?? '';
            $name = $_POST['fullname'] ?? '';
            $password = $_POST['password'] ?? '';
            $confirmPassword = $_POST['confirmpassword'] ?? '';
            
            $errors = [];
            
            // --- Bắt đầu khối kiểm tra dữ liệu ---
            if (empty($username)) {
                $errors['username'] = "Vui lòng nhập tên đăng nhập!";
            }

            // KIỂM TRA CHẶN USERNAME "ADMIN"
            /*if (strtolower($username) === 'admin') {
                $errors['username_reserved'] = "Tên đăng nhập 'admin' không được phép sử dụng.";
            }*/
            
            if (empty($name)) {
                $errors['fullname'] = "Vui lòng nhập họ và tên!";
            }
            if (empty($password)) {
                $errors['password'] = "Vui lòng nhập mật khẩu!";
            }
            if (strlen($password) < 6) {
                $errors['password_len'] = "Mật khẩu phải có ít nhất 6 ký tự.";
            }
            if ($password != $confirmPassword) {
                $errors['confirmPass'] = "Mật khẩu và xác nhận chưa đúng.";
            }
            // --- Kết thúc khối kiểm tra dữ liệu ---

            // Nếu không có lỗi, tiến hành lưu
            if (empty($errors)) {
                $result = $this->accountModel->save($username, $name, $password);
                if ($result) {
                    header('Location: /webbanhang/account/login');
                    exit();
                } else {
                    $errors['account_exists'] = "Tên đăng nhập này đã có người đăng ký!";
                }
            }
            
            // Nếu có lỗi, gửi lỗi và dữ liệu cũ về view để người dùng sửa
            $old_input = ['username' => $username, 'fullname' => $name];
            include_once 'app/views/account/register.php';
        }
    }

    /**
     * Xử lý đăng nhập
     */
    public function checkLogin()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';

            $account = $this->accountModel->verifyPassword($username, $password);

            if ($account) {
                // Lưu đầy đủ thông tin vào session
                $_SESSION['user_id'] = $account->id;
                $_SESSION['username'] = $account->username;
                $_SESSION['user_role'] = $account->role;
                header('Location: /webbanhang/product');
                exit;
            } else {
                $error = "Tên đăng nhập hoặc mật khẩu không chính xác.";
                include 'app/views/account/login.php';
            }
        }
    }

    /**
     * Xử lý đăng xuất
     */
    public function logout()
    {
        session_unset();
        session_destroy();
        header('Location: /webbanhang/product');
        exit();
    }

    /**
     * Hiển thị trang thông tin cá nhân
     */
    public function profile()
    {
        if (!SessionHelper::isLoggedIn()) {
            header('Location: /webbanhang/account/login');
            exit();
        }
        $account = $this->accountModel->getAccountById($_SESSION['user_id']);
        include 'app/views/account/profile.php';
    }

    /**
     * Xử lý cập nhật thông tin cá nhân
     */
    public function updateProfile()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && SessionHelper::isLoggedIn()) {
            $name = $_POST['name'] ?? '';
            $email = $_POST['email'] ?? '';
            $phone = $_POST['phone'] ?? '';
            $address = $_POST['address'] ?? '';
            $user_id = $_SESSION['user_id'];

            $result = $this->accountModel->updateProfile($user_id, $name, $email, $phone, $address);
            
            $_SESSION['flash_message'] = $result 
                ? ['type' => 'success', 'message' => 'Cập nhật thông tin thành công!']
                : ['type' => 'danger', 'message' => 'Cập nhật thông tin thất bại.'];

            header('Location: /webbanhang/account/profile');
            exit();
        }
        header('Location: /webbanhang/account/login');
        exit();
    }

    /**
     * Xử lý đổi mật khẩu
     */
    public function changePassword()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && SessionHelper::isLoggedIn()) {
            $currentPassword = $_POST['current_password'] ?? '';
            $newPassword = $_POST['new_password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';
            
            $user_id = $_SESSION['user_id'];
            $account = $this->accountModel->getAccountById($user_id);

            if (!$account || !password_verify($currentPassword, $account->password)) {
                 $_SESSION['flash_message'] = ['type' => 'danger', 'message' => 'Mật khẩu hiện tại không đúng.'];
            }
            elseif (empty($newPassword) || $newPassword != $confirmPassword || strlen($newPassword) < 6) {
                 $_SESSION['flash_message'] = ['type' => 'danger', 'message' => 'Mật khẩu mới không hợp lệ hoặc không khớp.'];
            }
            else {
                $this->accountModel->updatePasswordById($user_id, $newPassword);
                $_SESSION['flash_message'] = ['type' => 'success', 'message' => 'Đổi mật khẩu thành công!'];
            }
            
            header('Location: /webbanhang/account/profile');
            exit();
        }
        header('Location: /webbanhang/account/login');
        exit();
    }

    /**
     * Xử lý xóa tài khoản
     */
    public function deleteAccount()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && SessionHelper::isLoggedIn()) {
            $currentPassword = $_POST['current_password_for_delete'] ?? '';
            $userId = $_SESSION['user_id'];
            $account = $this->accountModel->getAccountById($userId);

            if (!$account || !password_verify($currentPassword, $account->password)) {
                $_SESSION['flash_message'] = ['type' => 'danger', 'message' => 'Mật khẩu không đúng. Không thể xóa tài khoản.'];
                header('Location: /webbanhang/account/profile');
                exit();
            }

            if ($this->accountModel->deleteAccountById($userId)) {
                session_unset();
                session_destroy();
                session_start();
                $_SESSION['flash_message'] = ['type' => 'success', 'message' => 'Tài khoản của bạn đã được xóa thành công.'];
                header('Location: /webbanhang/');
                exit();
            } else {
                $_SESSION['flash_message'] = ['type' => 'danger', 'message' => 'Đã có lỗi xảy ra trong quá trình xóa tài khoản.'];
                header('Location: /webbanhang/account/profile');
                exit();
            }
        }
        header('Location: /webbanhang/account/login');
        exit();
    }
    
    // --- CÁC HÀM XỬ LÝ QUÊN MẬT KHẨU ---

    public function forgotPassword()
    {
        include 'app/views/account/forgot-password.php';
    }

    public function sendResetLink()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $email = $_POST['email'] ?? '';
            $account = $this->accountModel->getAccountByEmail($email);

            if ($account) {
                $token = bin2hex(random_bytes(32));
                $this->accountModel->createPasswordResetToken($email, $token);
                
                $reset_link = "http://localhost:90/webbanhang/account/resetPassword/" . $token;
                $message = "Một yêu cầu khôi phục mật khẩu đã được thực hiện. ";
                $message .= "Nếu đây là bạn, hãy nhấn vào link sau để đặt lại mật khẩu (link có hiệu lực trong 1 giờ): <br>";
                $message .= "<a href='$reset_link'>$reset_link</a>";
                
                $_SESSION['flash_message'] = ['type' => 'info', 'message' => $message];
            } else {
                 $_SESSION['flash_message'] = ['type' => 'danger', 'message' => 'Không tìm thấy tài khoản nào với email này.'];
            }
            header('Location: /webbanhang/account/forgotPassword');
            exit();
        }
    }

    public function resetPassword($token)
    {
        $tokenData = $this->accountModel->getResetToken($token);

        if (!$tokenData || (strtotime($tokenData->created_at) < (time() - 3600))) {
            die('Link khôi phục không hợp lệ hoặc đã hết hạn.');
        }
        include 'app/views/account/reset-password.php';
    }

    public function updatePassword()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $token = $_POST['token'] ?? '';
            $password = $_POST['password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';

            $tokenData = $this->accountModel->getResetToken($token);
            if (!$tokenData || (strtotime($tokenData->created_at) < (time() - 3600))) {
                die('Link khôi phục không hợp lệ hoặc đã hết hạn.');
            }

            if (empty($password) || $password != $confirmPassword || strlen($password) < 6) {
                 $_SESSION['flash_message'] = ['type' => 'danger', 'message' => 'Mật khẩu không hợp lệ hoặc không khớp.'];
                 header('Location: /webbanhang/account/resetPassword/' . $token);
                 exit();
            }

            $this->accountModel->updatePasswordByEmail($tokenData->email, $password);
            $this->accountModel->deleteResetToken($token);

            $_SESSION['flash_message'] = ['type' => 'success', 'message' => 'Đặt lại mật khẩu thành công! Bạn có thể đăng nhập ngay bây giờ.'];
            header('Location: /webbanhang/account/login');
            exit();
        }
    }
}
