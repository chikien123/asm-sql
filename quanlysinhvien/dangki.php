<?php
session_start();
include 'config/db.php'; // Kết nối cơ sở dữ liệu

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Lấy dữ liệu từ form đăng ký
    $username = $_POST['username'];
    $password = $_POST['password'];
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $role = $_POST['role'];

    // Mã hóa mật khẩu trước khi lưu vào cơ sở dữ liệu
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Kiểm tra xem username có tồn tại không
    $checkUsername = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($checkUsername);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $error_message = "Username đã tồn tại!";
    } else {
        // Lấy ID của role từ bảng roles
        $getRoleId = "SELECT id FROM roles WHERE role_name = ?";
        $stmt_role = $conn->prepare($getRoleId);
        $stmt_role->bind_param("s", $role);
        $stmt_role->execute();
        $result_role = $stmt_role->get_result();
        $role_data = $result_role->fetch_assoc();
        $role_id = $role_data['id'];

        // Thực hiện câu lệnh INSERT vào bảng users
        $sql = "INSERT INTO users (username, password, role_id, full_name, email, phone) 
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt_insert = $conn->prepare($sql);
        $stmt_insert->bind_param("ssiiss", $username, $hashed_password, $role_id, $full_name, $email, $phone);

        if ($stmt_insert->execute()) {
            // Đăng ký thành công
            $_SESSION['success_message'] = "Đăng ký thành công! Bạn có thể đăng nhập ngay.";
            header("Location: dangnhap.php"); // Chuyển hướng về trang đăng nhập
            exit;
        } else {
            $error_message = "Đăng ký thất bại. Vui lòng thử lại!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Ký Tài Khoản</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header text-center">
                        <h4>Đăng Ký Tài Khoản</h4>
                    </div>
                    <div class="card-body">
                        <!-- Hiển thị thông báo lỗi -->
                        <?php if (isset($error_message)): ?>
                            <div class="alert alert-danger">
                                <?php echo $error_message; ?>
                            </div>
                        <?php endif; ?>
                        
                        <!-- Hiển thị thông báo thành công -->
                        <?php if (isset($_SESSION['success_message'])): ?>
                            <div class="alert alert-success">
                                <?php echo $_SESSION['success_message']; ?>
                            </div>
                            <?php unset($_SESSION['success_message']); ?>
                        <?php endif; ?>

                        <form method="POST" action="dangki.php">
                            <div class="mb-3">
                                <label for="username" class="form-label">Tên Đăng Nhập</label>
                                <input type="text" class="form-control" id="username" name="username" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Mật khẩu</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <div class="mb-3">
                                <label for="full_name" class="form-label">Họ và Tên</label>
                                <input type="text" class="form-control" id="full_name" name="full_name" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            <div class="mb-3">
                                <label for="phone" class="form-label">Số điện thoại</label>
                                <input type="text" class="form-control" id="phone" name="phone" required>
                            </div>
                            <div class="mb-3">
                                <label for="role" class="form-label">Chọn Vai Trò</label>
                                <select class="form-select" id="role" name="role" required>
                                    <option value="admin">Admin</option>
                                    <option value="teacher">Teacher</option>
                                    <option value="student">Student</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Đăng Ký</button>
                        </form>
                        <div class="mt-3 text-center">
                            <a href="dangnhap.php">Đã có tài khoản? Đăng nhập ngay</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>