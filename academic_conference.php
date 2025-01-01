<?php
session_start();
if (!isset($_SESSION['student_id'])) {
    header("Location: login.php");
    exit;
}

if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

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

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // CSRF 토큰 검증
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("CSRF 토큰 검증 실패");
    }

    if (!isset($_POST['conference_type']) || $_POST['conference_type'] === "") {
        $message = "";
    } else {
        $conference_type = $_POST['conference_type'];
        $details = $_POST['details'] ?? '';
        $date = $_POST['date'] ?? '';
        $award = null; // 기본값 null
        $student_id = $_SESSION['student_id'];

        if ($conference_type === "학술 관련 대회 참가" && isset($_POST['award'])) {
            $award = $_POST['award'];
        }

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
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>학술대회 정보 입력</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 600px;
            padding: 20px;
            background-color: white;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            position: relative;
        }
        h1 {
            color: #0000FF; /* 파란색 */
            text-align: center;
            margin-bottom: 20px;
        }
        label, select, textarea, input[type="date"], input[type="file"] {
            display: block;
            width: 90%;
            margin: 10px auto;
            font-size: 16px;
        }
        input[type="submit"], .button {
            display: inline-block;
            margin: 10px 5px;
            padding: 10px 20px;
            background-color: #0000FF; /* 파란색 */
            color: white;
            border: none;
            cursor: pointer;
            text-align: center;
            font-size: 16px;
            border-radius: 5px;
        }
        input[type="submit"]:hover, .button:hover {
            background-color: #000099; /* 어두운 파란색 */
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
            text-decoration: underline;
            color: #000099;
        }
    </style>
    <script>
        function toggleAwardField() {
            const conferenceType = document.getElementById('conference_type').value;
            const awardField = document.getElementById('awardField');
            if (conferenceType === '학술 관련 대회 참가') {
                awardField.style.display = 'block';
            } else {
                awardField.style.display = 'none';
                document.getElementById('award').value = "";
            }
        }
    </script>
</head>
<body>
    <div class="top-right-link">
        <a href="select_category.php">홈으로</a>
    </div>
    <div class="container">
        <h1>학술대회 정보 입력</h1>
        <?php if ($message): ?>
            <p class="<?php echo strpos($message, 'Error') !== false ? 'error' : ''; ?>">
                <?php echo htmlspecialchars($message); ?>
            </p>
            <?php if (strpos($message, '완료') !== false): ?>
                <a href="select_category.php" class="button">홈으로</a>
            <?php endif; ?>
        <?php endif; ?>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
            
            <label for="conference_type">항목:</label>
            <select id="conference_type" name="conference_type" onchange="toggleAwardField()" required>
                <option value="" disabled selected>선택하세요</option>
                <option value="학술 관련 대회 참가">학술 관련 대회 참가</option>
                <option value="군 내외부 학술지 게재">군 내외부 학술지 게재</option>
                <option value="동아리 및 프로젝트">동아리 및 프로젝트</option>
            </select>

            <div id="awardField" style="display:none;">
                <label for="award">수상:</label>
                <select id="award" name="award">
                    <option value="">수상 여부 선택</option>
                    <option value="동상(우수상) 이상">동상(우수상) 이상</option>
                    <option value="동상(우수상) 이하">동상(우수상) 이하</option>
                </select>
            </div>

            <label for="details">상세내용:</label>
            <textarea id="details" name="details" rows="4" required></textarea>

            <label for="date">응시일자:</label>
            <input type="date" id="date" name="date" required>

            <label for="file">증빙자료:</label>
            <input type="file" id="file" name="file">

            <input type="submit" value="제출">
        </form>
    </div>
</body>
</html>