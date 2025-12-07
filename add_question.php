<?php
include 'auth.php'; // Bắt buộc đăng nhập
include 'db_connect.php';

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Lọc dữ liệu đầu vào để chống XSS
    $question_text = $conn->real_escape_string($_POST['question_text']);
    $option_a = $conn->real_escape_string($_POST['option_a']);
    $option_b = $conn->real_escape_string($_POST['option_b']);
    $option_c = $conn->real_escape_string($_POST['option_c']);
    $option_d = $conn->real_escape_string($_POST['option_d']);
    $correct_answer = strtoupper($conn->real_escape_string($_POST['correct_answer']));
    
    $user_id = $current_user_id; // Lấy ID người dùng từ auth.php

    // Chèn câu hỏi, LUÔN kèm theo user_id
    $stmt = $conn->prepare("INSERT INTO questions (question_text, option_a, option_b, option_c, option_d, correct_answer, user_id) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssi", $question_text, $option_a, $option_b, $option_c, $option_d, $correct_answer, $user_id);

    if ($stmt->execute()) {
        $message = "Thêm câu hỏi thành công! ✅";
    } else {
        $message = "Lỗi: " . $stmt->error;
    }
    $stmt->close();
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Thêm Câu Hỏi</title>
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
    <link rel="stylesheet" href="assets/css/responsive.css">
</head>
<body>
   <div class="themCauHoi_container">
     <h2>Chào <?php echo htmlspecialchars($current_username); ?>! Thêm Câu Hỏi Mới</h2>
     <div class="chucNangKhac_container">
        <div class="btnQuayLai">
            <a href="index.php">Quay lại Ôn Thi</a>
        </div>
        <div class="btnQuayLai">
            <a href="logout.php">Đăng Xuất</a>
        </div>
     </div>
    <?php if ($message) echo "<p style='color:green;'>$message</p>"; ?>
    
    <form method="POST" action="">
        <label>Nội dung Câu hỏi:</label><textarea name="question_text" rows="3" cols="50" required></textarea><br><br>
        <label>Đáp án A:</label><input type="text" name="option_a" required><br>
        <label>Đáp án B:</label><input type="text" name="option_b" required><br>
        <label>Đáp án C:</label><input type="text" name="option_c" required><br>
        <label>Đáp án D:</label><input type="text" name="option_d" required><br><br>
        <label>Đáp án Đúng (A/B/C/D):</label><input type="text" name="correct_answer" maxlength="1" pattern="[A-Da-d]" required><br><br>
        <input type="submit" value="Lưu Câu Hỏi">
    </form>
   </div>
</body>
</html>