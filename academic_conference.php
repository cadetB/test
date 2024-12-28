<?php
session_start();
if (!isset($_SESSION['student_id'])) {
    header("Location: login.php");
    exit;
}

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

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $conference_type = $_POST['conference_type'];
    $details = $_POST['details'];
    $date = $_POST['date'];
    $award = isset($_POST['award']) ? $_POST['award'] : null;
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

    $sql = "INSERT INTO academic_conferences (student_id, conference_type, details, date, award, file_path) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssss", $student_id, $conference_type, $details, $date, $award, $file_path);

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
<html>
<head>
    <title>학술대회 정보 입력</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        h1 { color: #4CAF50; }
        form { margin-top: 20px; }
        label { display: block; margin-top: 10px; }
        input[type="text"], input[type="date"], select, textarea { width: 300px; padding: 5px; margin-top: 5px; }
        input[type="submit"] { margin-top: 20px; padding: 10px 20px; background-color: #4CAF50; color: white; border: none; cursor: pointer; }
        button { margin-top: 20px; padding: 10px 20px; background-color: #4CAF50; color: white; border: none; cursor: pointer; }
    </style>
    <script>
        function toggleAwardField() {
            const conferenceType = document.getElementById('conference_type').value;
            const awardField = document.getElementById('awardField');
            if (conferenceType === '학술 관련 대회 참가') {
                awardField.style.display = 'block';
            } else {
                awardField.style.display = 'none';
            }
        }
    </script>
</head>
<body>
    <h1>학술대회 정보 입력</h1>
    <?php if ($message): ?>
        <p><?php echo $message; ?></p>
        <button onclick="location.href='select_category.php'">홈으로</button>
    <?php else: ?>
        <form action="" method="post" enctype="multipart/form-data">
            <label for="conference_type">항목:</label>
            <select id="conference_type" name="conference_type" onchange="toggleAwardField()" required>
                <option value="학술 관련 대회 참가">학술 관련 대회 참가</option>
                <option value="군 내외부 학술지 게재">군 내외부 학술지 게재</option>
                <option value="동아리 및 프로젝트">동아리 및 프로젝트</option>
            </select>

            <div id="awardField" style="display:none;">
                <label for="award">수상:</label>
                <select id="award" name="award">
                    <option value="동상(우수상) 이상">동상(우수상) 이상</option>
                    <option value="동상(우수상) 이하">동상(우수상) 이하</option>
                </select>
            </div>

            <label for="details">상세 내용:</label>
            <textarea id="details" name="details" rows="4" required></textarea>

            <label for="date">참여 날짜:</label>
            <input type="date" id="date" name="date" required>

            <label for="file">증빙 자료:</label>
            <input type="file" id="file" name="file">

            <input type="submit" value="제출">
        </form>
    <?php endif; ?>
</body>
</html>