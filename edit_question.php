<?php
include 'auth.php';
include 'db_connect.php';

$message = '';

// ID câu hỏi cần edit
$id = isset($_GET['id']) ? intval($_GET['id']) : (isset($_POST['id']) ? intval($_POST['id']) : 0);
if ($id <= 0) {
    header('Location: manage_questions.php');
    exit();
}

// Nếu POST -> cập nhật
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $question_text = trim($_POST['question_text']);
    $option_a = trim($_POST['option_a']);
    $option_b = trim($_POST['option_b']);
    $option_c = trim($_POST['option_c']);
    $option_d = trim($_POST['option_d']);
    $correct_answer = strtoupper(trim($_POST['correct_answer']));

    try {
        // đảm bảo câu hỏi thuộc về user hiện tại
        $check = $conn->prepare("SELECT id FROM questions WHERE id = :id AND user_id = :uid");
        $check->execute([':id' => $id, ':uid' => $current_user_id]);
        if ($check->rowCount() === 0) {
            $message = 'Không tìm thấy câu hỏi hoặc bạn không có quyền chỉnh sửa.';
        } else {
            $upd = $conn->prepare("UPDATE questions SET question_text = :q, option_a = :a, option_b = :b, option_c = :c, option_d = :d, correct_answer = :correct WHERE id = :id");
            $upd->execute([
                ':q' => $question_text,
                ':a' => $option_a,
                ':b' => $option_b,
                ':c' => $option_c,
                ':d' => $option_d,
                ':correct' => $correct_answer,
                ':id' => $id,
            ]);
            header('Location: manage_questions.php');
            exit();
        }
    } catch (PDOException $e) {
        $message = 'Lỗi: ' . htmlspecialchars($e->getMessage());
    }
}

// Lấy thông tin câu hỏi để hiển thị form
try {
    $stmt = $conn->prepare("SELECT * FROM questions WHERE id = :id AND user_id = :uid");
    $stmt->execute([':id' => $id, ':uid' => $current_user_id]);
    $question = $stmt->fetch();
    if (!$question) {
        header('Location: manage_questions.php');
        exit();
    }
} catch (PDOException $e) {
    $question = null;
    $message = 'Lỗi: ' . htmlspecialchars($e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sửa Câu Hỏi</title>
    <link rel="stylesheet" href="assets/css/responsive.css">
    <style>
        .themCauHoi_container {
            width: 90%;
            max-width: 900px;
            margin: 12px auto;
            padding: 16px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #f9f9f9;
            box-sizing: border-box;
        }
        .chucNangKhac_container {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin: 12px 0;
        }
        .btnQuayLai {
            margin: 0;
            padding: 10px 15px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
        .btnQuayLai a {
            color: white;
            text-decoration: none;
            display: block;
        }
        form label {
            display: block;
            width: auto;
            margin-bottom: 6px;
        }
        .form-row {
            margin-bottom: 12px;
        }
        form input[type="text"], form textarea {
            width: 100%;
            padding: 8px;
            margin: 0;
            margin-bottom: 6px;
            box-sizing: border-box;
        }
        form input[type="submit"] {
            padding: 10px 20px;
            background-color: #008CBA;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
            box-sizing: border-box;
        }
        @media (min-width: 600px) {
            form input[type="submit"] { width: auto; }
            .themCauHoi_container { width: 60%; }
            form label { display: inline-block; width: 150px; vertical-align: top; }
            form input[type="text"], form textarea { width: calc(100% - 160px); }
            .form-row { display: flex; align-items: flex-start; gap: 10px; }
        }
    </style>
</head>
<body>
    <div class="themCauHoi_container">
        <h2>Chào <?php echo htmlspecialchars($current_username); ?>! Sửa Câu Hỏi Số <?php echo $id; ?></h2>
        <div class="chucNangKhac_container">
            <div class="btnQuayLai"><a href="index.php">Quay lại Ôn Thi</a></div>
            <div class="btnQuayLai"><a href="manage_questions.php">Quản lý Câu Hỏi</a></div>
            <div class="btnQuayLai"><a href="logout.php">Đăng Xuất</a></div>
        </div>
        <?php if ($message) echo "<p style='color:red;'>" . htmlspecialchars($message) . "</p>"; ?>

        <form method="POST" action="">
            <input type="hidden" name="id" value="<?php echo $id; ?>">

            <div class="form-row">
                <label>Nội dung Câu hỏi:</label>
                <textarea name="question_text" rows="4" required><?php echo htmlspecialchars($question['question_text']); ?></textarea>
            </div>

            <div class="form-row">
                <label>Đáp án A:</label>
                <input type="text" name="option_a" value="<?php echo htmlspecialchars($question['option_a']); ?>" required>
            </div>

            <div class="form-row">
                <label>Đáp án B:</label>
                <input type="text" name="option_b" value="<?php echo htmlspecialchars($question['option_b']); ?>" required>
            </div>

            <div class="form-row">
                <label>Đáp án C:</label>
                <input type="text" name="option_c" value="<?php echo htmlspecialchars($question['option_c']); ?>" required>
            </div>

            <div class="form-row">
                <label>Đáp án D:</label>
                <input type="text" name="option_d" value="<?php echo htmlspecialchars($question['option_d']); ?>" required>
            </div>

            <div class="form-row">
                <label>Đáp án Đúng (A/B/C/D):</label>
                <input type="text" name="correct_answer" maxlength="1" pattern="[A-Da-d]" value="<?php echo htmlspecialchars($question['correct_answer']); ?>" required>
            </div>

            <input type="submit" value="Lưu thay đổi">
        </form>
    </div>
</body>
</html>
