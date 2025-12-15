<?php
session_start();
include 'db_connect.php';

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    try {
        $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username = :username");
        $stmt->execute([':username' => $username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            header("Location: index.php");
            exit();
        } else {
            $message = "Tên đăng nhập hoặc mật khẩu không đúng!";
        }
    } catch (PDOException $e) {
        $message = "Lỗi đăng nhập: " . htmlspecialchars($e->getMessage());
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Đăng Nhập</title>
    <style>
        .login_container {
            width: 300px;
            margin: 100px auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-shadow: 2px 2px 12px #aaa;
        }
        .login_container h2 {
            text-align: center;
        }
        .login_container form {
            display: flex;
            flex-direction: column;
        }
        .login_container input[type="text"], 
        .login_container input[type="password"] {
            padding: 8px;
            margin: 0;
            border: 1px solid #ccc;
            border-radius: 3px;
        }
        .login_container input[type="submit"] {
            margin-top: 15px;
            padding: 10px;
            background-color: #930fe6ff;
            color: white;
            border: none;
            border-radius: 3px;
            cursor: pointer;
        }
        .login_container input[type="submit"]:hover {
            background-color: #218838;
        }
        .btn_register {
            text-decoration: none;
            color: #007bff;
        }
        .btn_register:hover {
            text-decoration: underline;
        }
        p {
            text-align: center;
        }
    </style>
    <link rel="stylesheet" href="assets/css/responsive.css">
</head>
<body>
    <h1>Chiều thứ 2 ca 3</h1>
    
    <div class="login_container">
        <h2>Đăng Nhập Hệ Thống 🔑</h2>
        <?php if ($message) echo "<p style='color: red;'>$message</p>"; ?>
        <form method="POST" action="">
            <input type="text" name="username" placeholder="Tên đăng nhập" required><br>
            <input type="password" name="password" placeholder="Mật khẩu" required><br>
            <input type="submit" value="Đăng Nhập">
        </form>
        <p>Chưa có tài khoản? <a href="register.php" class="btn_register">Đăng ký ngay</a></p>
    </div>
</body>
</html>