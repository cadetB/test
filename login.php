<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (isset($_SESSION['student_id'])) {
    session_unset();
    session_destroy();
    session_start();
}


if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$error_message = "";

$servername = "localhost";
$username = "root";
$password = "1234";
$dbname = "GhHj";
$port = 3306;

$conn = new mysqli($servername, $username, $password, $dbname, $port);
$conn->set_charset("utf8mb4");

if ($conn->connect_error) {
    die("데이터베이스 연결 실패: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("CSRF 토큰 검증 실패");
    }

    if (isset($_POST['action']) && $_POST['action'] === "login") {
        $student_id = $_POST['student_id'] ?? '';
        $password = $_POST['password'] ?? '';

        $stmt = $conn->prepare("SELECT student_id, name, password, role FROM users WHERE student_id = ?");
        if (!$stmt) {
            die("쿼리 준비 실패: " . $conn->error);
        }
        $stmt->bind_param("s", $student_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            if ($password === $user['password']) {
                $_SESSION['student_id'] = $user['student_id'];
                $_SESSION['name'] = $user['name'];
                $_SESSION['role'] = $user['role'];

                if ($user['role'] === '관리자') {
                    header("Location: view_someone.php");
                } else {
                    header("Location: select_category.php");
                }
                exit();
            } else {
                $error_message = "비밀번호가 일치하지 않습니다.";
            }
        } else {
            $error_message = "등록되지 않은 교번입니다.";
        }
        $stmt->close();
    } elseif (isset($_POST['action']) && $_POST['action'] === "register") {
        header("Location: index.php");
        exit();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>로그인</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            margin: 20px; 
            background-color: #f4f4f4; 
        }
        .container {
            max-width: 400px;
            margin: 50px auto;
            padding: 20px;
            background-color: white;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h1 { 
            color: #0000FF; 
            text-align: center; 
            margin-bottom: 30px; 
        }
        form { 
            max-width: 400px; 
            margin: auto; 
        }
        label { 
            display: block; 
            margin: 10px 0 5px; 
            color: #333; 
        }
        input { 
            width: 100%; 
            padding: 8px; 
            margin-bottom: 15px; 
            border: 1px solid #ddd; 
            border-radius: 4px; 
            box-sizing: border-box; 
        }
        .button-group { 
            display: flex; 
            gap: 10px; 
            margin-top: 20px; 
        }
        button { 
            flex: 1; 
            padding: 10px 20px; 
            background-color: #0000FF; 
            color: white; 
            border: none; 
            border-radius: 4px; 
            cursor: pointer; 
        }
        button:hover { 
            background-color: #000099; 
        }
        .error { 
            color: red; 
            text-align: center; 
            margin-bottom: 15px; 
        }
        .top-right-link {
            position: absolute;
            top: 20px;
            right: 20px;
        }
        .top-right-link a {
            text-decoration: none;
            color: #0000FF;
            font-size: 16px;
        }
        .top-right-link a:hover {
            color: #000099;
            text-decoration: underline;
        }
    </style>
</head>
<body>  
    <div class="top-right-link">
        <form method="POST" style="display:inline;">
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
            <button type="submit" name="action" value="register" style="background: none; border: none; color: #0000FF; cursor: pointer; font-size: 16px; padding: 0;">
                회원가입
            </button>
        </form>
    </div>

    <div class="container">
        <h1>로그인</h1>
        <?php if ($error_message): ?>
            <p class="error"><?php echo htmlspecialchars($error_message); ?></p>
        <?php endif; ?>
        <form method="POST">
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
            <label for="student_id">교번:</label>
            <input type="text" id="student_id" name="student_id" required>

            <label for="password">비밀번호:</label>
            <input type="password" id="password" name="password" required>
			
			<div class="button-group">	
            	<button type="submit" name="action" value="login">로그인</button>
			</div>
        </form>
    </div>
</body>
</html>