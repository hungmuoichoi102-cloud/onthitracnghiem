<?php
// Bắt đầu hoặc khôi phục phiên làm việc (session)
session_start();

// Kiểm tra nếu người dùng chưa đăng nhập, chuyển hướng về trang đăng nhập
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Lấy ID và Username của người dùng đang đăng nhập
$current_user_id = $_SESSION['user_id'];
$current_username = $_SESSION['username'];
?>