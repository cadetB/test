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
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("CSRF 토큰 검증 실패");
    }

    $activity_type = $_POST['activity_type'] ?? '';
    $date = $_POST['date'] ?? '';
    $dop_award = $_POST['dop_award'] ?? '';
    $details = $_POST['details'] ?? null;
    $hours = $_POST['hours'] ?? null;
    $file_path = "";

    if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = "uploads/";
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        $uploadFile = $uploadDir . basename($_FILES["file"]["name"]);
        if (move_uploaded_file($_FILES["file"]["tmp_name"], $uploadFile)) {
            $file_path = $uploadFile;
        }
    }

    $sql = "INSERT INTO dops (student_id, type, date, details, participation_hours, selection, file_path) 
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("sssssss", $_SESSION['student_id'], $activity_type, $date, $details, $hours, $dop_award, $file_path);

        if ($stmt->execute()) {
            echo "<script>alert('제출이 완료되었습니다.'); window.location.href = 'select_category.php';</script>";
            exit;
        } else {
            $message = "오류: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $message = "SQL 준비 실패: " . $conn->error;
    }
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
        }
        h1 {
            color: #0000FF;
            text-align: center;
            margin-bottom: 20px;
        }
        label {
            display: block;
            text-align: left;
            margin: 10px auto 5px;
            width: 90%;
            font-size: 16px;
        }
        select, input[type="date"], textarea, input[type="number"] {
            display: block;
            width: 90%;
            margin: 5px auto 15px;
            font-size: 14px; /* 글자 크기 조정 */
            padding: 5px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
            height: 20px; /* 높이 20px 유지 */
        }
        textarea {
            resize: none;
            height: 20px;
        }
        input[type="file"] {
            display: block;
            width: 90%;
            margin: 5px auto 15px;
            font-size: 14px; /* 파일 선택 글자 크기 조정 */
            padding: 0;
            border: none; /* 테두리 제거 */
            box-sizing: border-box;
        }
        input[type="submit"] {
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #0000FF;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #000099;
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
        <a href="select_category.php">홈으로</a>
    </div>
    <div class="container">
        <h1>DOP 활동</h1>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

            <label for="activity_type">항목</label>
            <select id="activity_type" name="activity_type" required>
                <option value="" disabled selected>선택하세요</option>
                <option value="봉사활동">봉사활동</option>
                <option value="헌혈">헌혈</option>
            </select>

            <label for="date">참여일자</label>
            <input type="date" id="date" name="date" required>

            <label for="dop_award">DOP생도 선정</label>
            <select id="dop_award" name="dop_award" required>
                <option value="" disabled selected>선택하세요</option>
                <option value="최우수">최우수</option>
                <option value="우수">우수</option>
                <option value="장려">장려</option>
                <option value="해당 없음">해당 없음</option>
            </select>

            <label for="details">상세내용</label>
            <textarea id="details" name="details"></textarea>

            <label for="hours">참여시간</label>
            <input type="number" id="hours" name="hours" min="1">

            <label for="file">증빙자료</label>
            <input type="file" id="file" name="file">

            <input type="submit" value="제출">
        </form>
    </div>
</body>
</html>