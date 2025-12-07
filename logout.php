<?php
session_start();
// Xóa tất cả các biến session và hủy phiên làm việc
$_SESSION = array();
session_destroy();

// Chuyển hướng về trang đăng nhập
header("Location: login.php");
exit();
?>