<?php
include 'auth.php'; // Bắt buộc đăng nhập
include 'db_connect.php';

$questions = [];
$score = 0;

// Truy vấn CHỈ CÂU HỎI của người dùng hiện tại
$sql = "SELECT * FROM questions WHERE user_id = '$current_user_id' ORDER BY RAND()";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $questions[] = $row;
    }
}
$total_questions = count($questions);

// Xử lý Nộp bài
$is_submitted = false;
if ($_SERVER["REQUEST_METHOD"] == "POST" && $total_questions > 0) {
    $is_submitted = true;
    foreach ($questions as $q) {
        $question_id = $q['id'];
        $user_answer = isset($_POST['answer_'.$question_id]) ? strtoupper($_POST['answer_'.$question_id]) : null;
        if ($user_answer == $q['correct_answer']) {
            $score++;
        }
    }
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bài Ôn Thi Trắc Nghiệm</title>
    <style>
        .main_container {
            width: 60%;
            margin: auto;
            font-family: Arial, sans-serif;
        }
        .btn_container {
            margin: 20px 0;
            display: flex;

        }
        .btn_chucNang {
            margin: 10px;
            padding: 10px 15px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
        .btn_chucNang a {
            color: white;
            text-decoration: none;
        }
        form input[type="submit"] {
            padding: 10px 20px;
            background-color: #008CBA;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .btn_lamLai {
            margin-top: 20px;
            padding: 10px 15px;
            background-color: #f44336;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            display: inline-block;
        }
        .btn_lamLai a {
            color: white;
            text-decoration: none;
        }
    </style>
    <link rel="stylesheet" href="assets/css/responsive.css">
</head>
<body>
    <div class="main_container">
        <h1>Bài Thi Trắc Nghiệm</h1>
    <p>Chào <?php echo htmlspecialchars($current_username); ?>! Đây là các câu hỏi trắc nghiệm của bạn.</p>
    <div class="btn_container">
        <div class="btn_chucNang">
        <a href="add_question.php">Thêm Câu Hỏi Mới</a>
    </div>
    <div class="btn_chucNang">
        <a href="logout.php">Đăng Xuất</a>
    </div>
    </div>
    <hr>
    <?php if ($is_submitted): ?>
        <h2>🎉 Kết Quả Ôn Thi</h2>
        <p>Bạn đã trả lời đúng: <?php echo $score; ?>/<?php echo $total_questions; ?> câu.</p>
        <div class="btn_lamLai">
            <a href="index.php">Làm lại bài thi khác</a>
        </div>
    <?php elseif ($total_questions == 0): ?>
        <h2>Không có câu hỏi nào</h2>
        <p>Bạn chưa thêm câu hỏi nào. Vui lòng thêm câu hỏi để bắt đầu ôn tập.</p>
    <?php else: ?>
        <h2>Bắt đầu ôn tập (Tổng <?php echo $total_questions; ?> câu)</h2>
        <form method="POST" action="">
            <?php foreach ($questions as $key => $q): ?>
                <div style="border: 1px solid #ddd; padding: 15px; margin-bottom: 15px;">
                    <p><strong>Câu <?php echo $key + 1; ?>:</strong> <?php echo nl2br(htmlspecialchars($q['question_text'])); ?></p>
                    
                    <label><input type="radio" name="answer_<?php echo $q['id']; ?>" value="A" required> A. <?php echo htmlspecialchars($q['option_a']); ?></label><br>
                    <label><input type="radio" name="answer_<?php echo $q['id']; ?>" value="B"> B. <?php echo htmlspecialchars($q['option_b']); ?></label><br>
                    <label><input type="radio" name="answer_<?php echo $q['id']; ?>" value="C"> C. <?php echo htmlspecialchars($q['option_c']); ?></label><br>
                    <label><input type="radio" name="answer_<?php echo $q['id']; ?>" value="D"> D. <?php echo htmlspecialchars($q['option_d']); ?></label><br>
                </div>
            <?php endforeach; ?>
            <input type="submit" value="Nộp Bài và Xem Kết Quả">
        </form>
    <?php endif; ?>
    </div>
</body>
</html>