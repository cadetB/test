<?php
session_start();
$error_message = "";

// MySQL 연결 설정
$servername = "localhost";
$username = "root";
$password = "1234";
$dbname = "GhHj";
$port = 3306;
// 데이터베이스 연결
$conn = new mysqli($servername, $username, $password, $dbname);

// 연결 확인
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully to the database!";


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $student_id = $_POST['student_id'];
    $name = $_POST['name'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $role = $_POST['role'];
    $confirmation_code = isset($_POST['confirmation_code']) ? $_POST['confirmation_code'] : '';

    // 기존 사용자 확인
    $stmt = $conn->prepare("SELECT * FROM users WHERE student_id = ?");
    $stmt->bind_param("s", $student_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $error_message = "이미 가입된 교번입니다.";
    } elseif ($password !== $confirm_password) {
        $error_message = "비밀번호가 일치하지 않습니다.";
    } elseif ($role === '관리자' && $confirmation_code !== '0000') {
        $error_message = "확인코드가 올바르지 않습니다.";
    } else {
        // 비밀번호 해싱
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // 사용자 정보 저장
        $stmt = $conn->prepare("INSERT INTO users (student_id, name, password, role) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $student_id, $name, $hashed_password, $role);

        if ($stmt->execute()) {
            // 등록 성공 시 login.php로 이동
            header("Location: login.php");
            exit();
        } else {
            $error_message = "등록 중 오류가 발생했습니다: " . $conn->error;
        }
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>사용자 등록</title>
</head>
<body>
    <h1>사용자 등록</h1>
    <?php if ($error_message): ?>
        <p style="color: red;"><?php echo htmlspecialchars($error_message); ?></p>
    <?php endif; ?>
    <form action="register.php" method="post">
        교번: <input type="text" name="student_id" required><br>
        이름: <input type="text" name="name" required><br>
        비밀번호: <input type="password" name="password" required><br>
        비밀번호 확인: <input type="password" name="confirm_password" required><br>
        권한:
        <select name="role" id="role" onchange="showConfirmationCode()">
            <option value="사용자">사용자</option>
            <option value="관리자">관리자</option>
        </select><br>
        <div id="confirmationCodeDiv" style="display:none;">
            확인코드: <input type="text" name="confirmation_code"><br>
        </div>
        <input type="submit" value="등록">
    </form>

    <script>
        function showConfirmationCode() {
            var role = document.getElementById('role').value;
            document.getElementById('confirmationCodeDiv').style.display = (role === '관리자') ? 'block' : 'none';
        }
    </script>
</body>
</html>