<?php
session_start();
include 'config/db.php';

// Kiểm tra người dùng đã đăng nhập chưa
if (!isset($_SESSION['user_id'])) {
    header("Location: dangnhap.php");
    exit;
}

$role_id = $_SESSION['role_id'];

// Truy vấn thông tin người dùng từ database
$sql = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang Chủ</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <div class="row justify-content-center mt-5">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3>Xin Chào, <?php echo $user['full_name']; ?>!</h3>
                    </div>
                    <div class="card-body">
                        <p>Chào mừng bạn đã đăng nhập thành công.</p>

                        <?php if ($role_id == 1): ?>
                            <h4>Chức Năng Admin</h4>
                            <ul>
                                <li><a href="#">Quản lý người dùng</a></li>
                                <li><a href="#">Quản lý lớp học</a></li>
                            </ul>
                        <?php elseif ($role_id == 2): ?>
                            <h4>Chức Năng Teacher</h4>
                            <ul>
                                <li><a href="#">Quản lý lớp học</a></li>
                                <li><a href="#">Xem danh sách sinh viên</a></li>
                            </ul>
                        <?php else: ?>
                            <h4>Chức Năng Student</h4>
                            <ul>
                                <li><a href="#">Xem điểm</a></li>
                                <li><a href="#">Đăng ký lớp học</a></li>
                            </ul>
                        <?php endif; ?>

                        <a href="dangxuat.php" class="btn btn-danger mt-3">Đăng Xuất</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>