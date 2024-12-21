<?php
session_start();
session_unset();
session_destroy();

// Chuyển hướng về trang đăng nhập sau khi đăng xuất
header("Location: dangnhap.php");
exit;
?>