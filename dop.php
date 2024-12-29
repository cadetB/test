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
    $date = $_POST['date'];
    $details = isset($_POST['details']) ? $_POST['details'] : null;
    $hours = isset($_POST['hours']) ? $_POST['hours'] : null;
    $subtype = isset($_POST['subtype']) ? $_POST['subtype'] : null;
    $dop_award = $_POST['dop_award'];
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

    $sql = "INSERT INTO dop_activities (student_id, activity_type, date, details, hours, subtype, dop_award, file_path) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssss", $student_id, $activity_type, $date, $details, $hours, $subtype, $dop_award, $file_path);

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
    <title>DOP 활동</title>
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
    <script>
        function toggleFields() {
            const activityType = document.getElementById('activity_type').value;
            const detailsField = document.getElementById('detailsField');
            const hoursField = document.getElementById('hoursField');
            const subtypeField = document.getElementById('subtypeField');

            if (activityType === '봉사활동') {
                detailsField.style.display = 'block';
                hoursField.style.display = 'block';
                subtypeField.style.display = 'none';
            } else if (activityType === '헌혈') {
                detailsField.style.display = 'none';
                hoursField.style.display = 'none';
                subtypeField.style.display = 'block';
            } else {
                detailsField.style.display = 'none';
                hoursField.style.display = 'none';
                subtypeField.style.display = 'none';
            }
        }
    </script>
</head>
<body>
    <h1>DOP 활동</h1>
    <?php if ($message): ?>
        <p><?php echo htmlspecialchars($message); ?></p>
        <a href="select_category.php" class="button">홈으로</a>
    <?php else: ?>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
            
            <label for="activity_type">항목:</label>
            <select id="activity_type" name="activity_type" onchange="toggleFields()" required>
                <option value="봉사활동">봉사활동</option>
                <option value="헌혈">헌혈</option>
            </select>

            <div id="detailsField" style="display:none;">
                <label for="details">상세 내용:</label>
                <textarea id="details" name="details" rows="4"></textarea>
            </div>

            <div id="hoursField" style="display:none;">
                <label for="hours">참여 시간(시간):</label>
                <input type="text" id="hours" name="hours">
            </div>

            <div id="subtypeField" style="display:none;">
                <label for="subtype">세부 항목:</label>
                <select id="subtype" name="subtype">
                    <option value="전혈">전혈</option>
                    <option value="혈장">혈장</option>
                </select>
            </div>

            <label for="date">참여 날짜:</label>
            <input type="date" id="date" name="date" required>

            <label for="dop_award">DOP 생도 선정:</label>
            <select id="dop_award" name="dop_award" required>
                <option value="최우수">최우수</option>
                <option value="우수">우수</option>
                <option value="장려">장려</option>
                <option value="해당 없음">해당 없음</option>
            </select>

            <label for="file">증빙 자료:</label>
            <input type="file" id="file" name="file">

            <input type="submit" value="제출">
            <a href="select_category.php" class="button">홈으로</a>
        </form>
    <?php endif; ?>
</body>
</html>