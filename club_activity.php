<?php
session_start();
if (!isset($_SESSION['student_id'])) {
    header("Location: login.php");
    exit;
}

// CSRF 토큰 생성
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

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
    die("Connection failed: " . $conn->connect_error);
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // CSRF 토큰 검증
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("CSRF 토큰 검증 실패");
    }

    $activity_type = $_POST['activity_type'];
    $details = $_POST['details'];
    $date = $_POST['date'];
    $student_id = $_SESSION['student_id'];

    // 파일 업로드 처리
    $file_path = "";
    if (isset($_FILES['file']) && $_FILES['file']['error'] == 0) {
        $target_dir = "uploads/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        $target_file = $target_dir . basename($_FILES["file"]["name"]);
        if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
            $file_path = $target_file;
        }
    }

    $sql = "INSERT INTO club_activities (student_id, activity_type, details, date, file_path) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $student_id, $activity_type, $details, $date, $file_path);

    if ($stmt->execute()) {
        $message = "제출이 완료되었습니다.";
    } else {
        $message = "Error: " . $stmt->error;
    }
    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>소모임 & 휴일프로그램</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        h1 { color: #4CAF50; }
        form { margin-top: 20px; }
        label { display: block; margin-top: 10px; }
        input[type="text"], input[type="date"], input[type="file"], select, textarea { width: 300px; padding: 5px; margin-top: 5px; }
        input[type="submit"], .button {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
            text-decoration: none;
            font-size: 16px;
        }
        input[type="submit"]:hover, .button:hover { background-color: #45a049; }
    </style>
</head>
<body>
    <h1>소모임 & 휴일프로그램</h1>
    <?php if ($message): ?>
        <p><?php echo htmlspecialchars($message); ?></p>
        <a href="select_category.php" class="button">홈으로</a>
    <?php else: ?>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
            
            <label for="activity_type">항목:</label>
            <select id="activity_type" name="activity_type" required>
                <option value="휴일 프로그램">휴일 프로그램</option>
                <option value="소모임">소모임</option>
            </select>

            <label for="details">상세 내용:</label>
            <textarea id="details" name="details" rows="4" required></textarea>

            <label for="date">참여 날짜:</label>
            <input type="date" id="date" name="date" required>

            <label for="file">증빙 자료:</label>
            <input type="file" id="file" name="file">

            <input type="submit" value="제출">
            <a href="select_category.php" class="button">홈으로</a>
        </form>
    <?php endif; ?>
</body>
</html>