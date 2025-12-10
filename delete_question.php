<?php
include 'auth.php';
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: manage_questions.php');
    exit();
}

$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
if ($id <= 0) {
    header('Location: manage_questions.php');
    exit();
}

try {
    // Kiểm tra quyền sở hữu
    $check = $conn->prepare("SELECT id FROM questions WHERE id = :id AND user_id = :uid");
    $check->execute([':id' => $id, ':uid' => $current_user_id]);
    if ($check->rowCount() === 0) {
        // Không tồn tại hoặc không phải của user
        header('Location: manage_questions.php');
        exit();
    }

    $del = $conn->prepare("DELETE FROM questions WHERE id = :id");
    $del->execute([':id' => $id]);
} catch (PDOException $e) {
    // lỗi thì cũng quay về trang quản lý
}

header('Location: manage_questions.php');
exit();
