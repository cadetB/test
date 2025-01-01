<?php
session_start();

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
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("CSRF 토큰 검증 실패");
    }

    $student_id = $_POST['student_id'];
    $name = $_POST['name'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $role = $_POST['role'];
    $confirmation_code = isset($_POST['confirmation_code']) ? $_POST['confirmation_code'] : '';

    if ($password !== $confirm_password) {
        $error_message = "비밀번호가 일치하지 않습니다.";
    } elseif ($role === '관리자' && $confirmation_code !== '0000') {
        $error_message = "확인코드가 올바르지 않습니다.";
    } else {
        $stmt = $conn->prepare("SELECT * FROM users WHERE student_id = ?");
        $stmt->bind_param("s", $student_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $error_message = "이미 가입된 교번입니다.";
        } else {
            $stmt = $conn->prepare("INSERT INTO users (student_id, name, password, role) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $student_id, $name, $password, $role);

            if ($stmt->execute()) {
                header("Location: login.php");
                exit();
            } else {
                $error_message = "등록 중 오류가 발생했습니다: " . $stmt->error;
            }
        }
        $stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>회원가입</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            margin: 20px; 
            display: flex; 
            flex-direction: column; 
            align-items: center; 
        }
        h1 { 
            color: #0000FF; 
            text-align: center; 
            margin-bottom: 20px; 
        }
        form { 
            max-width: 400px; 
            margin: auto; 
        }
        label { 
            display: block; 
            margin: 10px 0 5px; 
        }
        input, select { 
            width: 100%; 
            padding: 8px; 
            margin-bottom: 10px; 
        }
        .button-group { 
            display: flex; 
            justify-content: space-between; 
        }
        button { 
            padding: 10px 20px; 
            background-color: #0000FF; 
            color: white; 
            border: none; 
            cursor: pointer; 
            border-radius: 4px; 
        }
        button:hover { 
            background-color: #0000CC; 
        }
        .error { 
            color: red; 
            text-align: center; 
            margin-bottom: 15px; 
        }
    </style>
</head>
<body>
    <h1>회원가입</h1>
    <?php if ($error_message): ?>
        <p class="error"><?php echo htmlspecialchars($error_message); ?></p>
    <?php endif; ?>
    <form method="POST">
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
        <label for="student_id">교번:</label>
        <input type="text" id="student_id" name="student_id" required>

        <label for="name">이름:</label>
        <input type="text" id="name" name="name" required>

        <label for="password">비밀번호:</label>
        <input type="password" id="password" name="password" required>

        <label for="confirm_password">비밀번호 확인:</label>
        <input type="password" id="confirm_password" name="confirm_password" required>

        <label for="role">권한:</label>
        <select id="role" name="role" onchange="showConfirmationCode()" required>
            <option value="사용자">사용자</option>
            <option value="관리자">관리자</option>
        </select>

        <div id="confirmationCodeDiv" style="display:none;">
            <label for="confirmation_code">확인코드:</label>
            <input type="text" id="confirmation_code" name="confirmation_code">
        </div>

        <div class="button-group">
            <button type="submit">등록</button>
            <button type="button" onclick="window.location.href='login.php'">로그인</button>
        </div>
    </form>

    <script>
        function showConfirmationCode() {
            var role = document.getElementById('role').value;
            document.getElementById('confirmationCodeDiv').style.display = (role === '관리자') ? 'block' : 'none';
        }
    </script>
</body>
</html>