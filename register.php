<?php
include 'db_connect.php';

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        $message = "Vui lòng nhập đầy đủ Tên đăng nhập và Mật khẩu.";
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        try {
            // Kiểm tra username đã tồn tại chưa
            $check_stmt = $conn->prepare("SELECT id FROM users WHERE username = :username");
            $check_stmt->execute([':username' => $username]);

            if ($check_stmt->rowCount() > 0) {
                $message = "Tên đăng nhập đã tồn tại!";
            } else {
                $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (:username, :password)");
                $stmt->execute([':username' => $username, ':password' => $hashed_password]);
                $message = "Đăng ký thành công! Bạn có thể <a href='login.php'>Đăng nhập</a> ngay.";
            }
        } catch (PDOException $e) {
            $message = "Lỗi đăng ký: " . htmlspecialchars($e->getMessage());
        }
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Đăng Ký</title>
    <style>
        .register_container {
            width: 300px;
            margin: 100px auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-shadow: 2px 2px 12px rgba(0,0,0,0.1);
            text-align: center;
        }
        .register_container input[type="text"], 
        .register_container input[type="password"] {
            width: 90%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 3px; 
        }
        .register_container input[type="submit"] {
            padding: 10px 20px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 3px;
            cursor: pointer;
        }
        .register_container input[type="submit"]:hover {
            background-color: #218838;
        }
        .btn_login {
            text-decoration: none;
            color: #007bff;
        }
        .btn_login:hover {
            text-decoration: underline;
        }
    </style>
    <link rel="stylesheet" href="assets/css/responsive.css">
</head>
<body>
    <div class="register_container">
        <h2>Đăng Ký Tài Khoản Mới 📝</h2>
        <?php if ($message) echo "<p style='color: green;'>$message</p>"; ?>
        <form method="POST" action="">
        <input type="text" name="username" required placeholder="Tên đăng nhập"><br>
        <input type="password" name="password" required placeholder="Mật khẩu"><br>
        <input type="submit" value="Đăng Ký">
    </form>
    <p>Đã có tài khoản? <a href="login.php" class="btn_login">Đăng nhập</a></p>
    </div>
</body>
</html>