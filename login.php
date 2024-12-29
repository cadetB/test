<?php
session_start();
if (isset($_SESSION['student_id'])) {
    header("Location: select_category.php");
    exit;
}

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// CSRF 토큰 생성
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$error_message = "";

// MySQL 연결 설정
$servername = "localhost";
$username = "root";
$password = "1234";
$dbname = "GhHj";
$port = 3306;

// 데이터베이스 연결
$conn = new mysqli($servername, $username, $password, $dbname, $port);
$conn->set_charset("utf8mb4");

// 연결 확인
if ($conn->connect_error) {
    die("데이터베이스 연결 실패: " . $conn->connect_error);
}

// 로그인 처리
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // CSRF 토큰 검증
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("CSRF 토큰 검증 실패");
    }

    if (isset($_POST['action'])) {
        if ($_POST['action'] === "login") {
            $student_id = $_POST['student_id'] ?? '';
            $password = $_POST['password'] ?? '';

            // 교번으로 사용자 확인
            $stmt = $conn->prepare("SELECT student_id, name, password, role FROM users WHERE student_id = ?");
            if (!$stmt) {
                die("쿼리 준비 실패: " . $conn->error);
            }
            $stmt->bind_param("s", $student_id);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows === 1) {
                $user = $result->fetch_assoc();
                if ($password === $user['password']) {  // 직접 비교 방식
                    $_SESSION['student_id'] = $user['student_id'];
                    $_SESSION['name'] = $user['name'];
                    $_SESSION['role'] = $user['role'];
                    header("Location: select_category.php");
                    exit();
                } else {
                    $error_message = "비밀번호가 일치하지 않습니다.";
                }
            } else {
                $error_message = "등록되지 않은 교번입니다.";
            }
            $stmt->close();
        } elseif ($_POST['action'] === "register") {
            header("Location: index.php");
            exit();
        }
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
            color: #4CAF50; 
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
            background-color: #4CAF50; 
            color: white; 
            border: none; 
            border-radius: 4px;
            cursor: pointer; 
        }
        button:hover { 
            background-color: #45a049; 
        }
        .error { 
            color: red;
            text-align: center;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
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
                <button type="submit" name="action" value="register">회원가입</button>
            </div>
        </form>
    </div>
</body>
</html>