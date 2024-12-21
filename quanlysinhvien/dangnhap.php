<?php
session_start();
include 'config/db.php';

// Kiểm tra nếu người dùng đã đăng nhập rồi thì chuyển hướng đến trang chính
if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Truy vấn kiểm tra tài khoản người dùng
    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        // Kiểm tra mật khẩu
        if (password_verify($password, $user['password'])) {
            // Lưu thông tin đăng nhập vào session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role_id'] = $user['role_id'];
            
            // Chuyển hướng người dùng đến trang chủ dựa trên vai trò
            if ($_SESSION['role_id'] == 1) {
                header("Location: index.php?role=admin");
            } elseif ($_SESSION['role_id'] == 2) {
                header("Location: index.php?role=teacher");
            } else {
                header("Location: index.php?role=student");
            }
            exit;
        } else {
            $error = "Mật khẩu sai.";
        }
    } else {
        $error = "Tài khoản không tồn tại.";
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Nhập</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <div class="row justify-content-center mt-5">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3>Đăng Nhập</h3>
                    </div>
                    <div class="card-body">
                        <?php if (isset($error)): ?>
                            <div class="alert alert-danger"><?php echo $error; ?></div>
                        <?php endif; ?>
                        <form action="dangnhap.php" method="POST">
                            <div class="mb-3">
                                <label for="username" class="form-label">Tên Đăng Nhập</label>
                                <input type="text" class="form-control" id="username" name="username" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Mật Khẩu</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Đăng Nhập</button>
                        </form>
                        <div class="mt-3">
                            <a href="dangki.php">Đăng Ký Tài Khoản</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>