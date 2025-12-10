<?php
include 'auth.php';
include 'db_connect.php';

// Lấy danh sách câu hỏi của người dùng
try {
    $stmt = $conn->prepare("SELECT id, question_text, option_a, option_b, option_c, option_d, correct_answer FROM questions WHERE user_id = :uid ORDER BY id DESC");
    $stmt->execute([':uid' => $current_user_id]);
    $questions = $stmt->fetchAll();
} catch (PDOException $e) {
    $questions = [];
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Quản lý Câu Hỏi</title>
    <link rel="stylesheet" href="assets/css/responsive.css">
    <style>
        .container { max-width: 900px; margin: 16px auto; padding: 10px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background: #f2f2f2; }
        .actions a, .actions form { display: inline-block; margin-right: 6px; }
        .btn { padding: 6px 10px; background:#4CAF50;color:#fff;text-decoration:none;border-radius:4px }
        .btn-danger { background:#f44336 }
    </style>
</head>
<body>
    <div class="container">
        <h2>Quản lý Câu Hỏi của bạn</h2>
        <p>Chào <?php echo htmlspecialchars($current_username); ?> — bạn có <?php echo count($questions); ?> câu hỏi.</p>
        <div style="margin-bottom:12px;">
            <a class="btn" href="add_question.php">Thêm Câu Hỏi Mới</a>
            <a class="btn" href="index.php">Quay lại Ôn Thi</a>
            <a class="btn" href="logout.php">Đăng Xuất</a>
        </div>

        <?php if (empty($questions)): ?>
            <p>Chưa có câu hỏi nào.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nội dung</th>
                        <th>Đáp án đúng</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($questions as $q): ?>
                    <tr>
                        <td><?php echo $q['id']; ?></td>
                        <td><?php echo nl2br(htmlspecialchars(mb_strimwidth($q['question_text'],0,160,'...'))); ?></td>
                        <td><?php echo htmlspecialchars($q['correct_answer']); ?></td>
                        <td class="actions">
                            <a class="btn" href="edit_question.php?id=<?php echo $q['id']; ?>">Sửa</a>
                            <form method="POST" action="delete_question.php" style="display:inline;" onsubmit="return confirm('Bạn có chắc muốn xóa câu hỏi này?');">
                                <input type="hidden" name="id" value="<?php echo $q['id']; ?>">
                                <button type="submit" class="btn btn-danger">Xóa</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</body>
</html>
