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
$show_form = true;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("CSRF 토큰 검증 실패");
    }

    $activity_type = $_POST['activity_type'] ?? '';
    $details = trim($_POST['details'] ?? '');
    $date = $_POST['date'] ?? '';
    $student_id = $_SESSION['student_id'];

    if (empty($activity_type) || empty($date)) {
        $message = "";
    } else {
        $file_path = "";
        if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
            $target_dir = "uploads/";
            if (!is_dir($target_dir)) {
                mkdir($target_dir, 0777, true);
            }
            $target_file = $target_dir . basename($_FILES["file"]["name"]);
            if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
                $file_path = $target_file;
            }
        }

        $sql = "INSERT INTO club_activities (student_id, activity_type, details, date, file_path) 
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("sssss", $student_id, $activity_type, $details, $date, $file_path);

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
            color: #0000FF;
            text-align: center;
            margin-bottom: 20px;
        }
        label, select, textarea, input[type="date"], input[type="file"] {
            display: block;
            width: 90%;
            margin: 10px auto;
            font-size: 16px;
        }
        textarea {
            height: 20px;
            resize: none;
        }
        input[type="file"] {
            border: none;
            padding: 0;
        }
        input[type="submit"] {
            display: inline-block;
            margin-top: 10px;
            padding: 10px 20px;
            background-color: #0000FF;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-align: center;
            font-size: 16px;
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
        <h1>소모임 & 휴일프로그램</h1>

        <?php if (!empty($message)): ?>
            <p><?php echo htmlspecialchars($message); ?></p>
        <?php endif; ?>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

            <label for="activity_type">항목:</label>
            <select id="activity_type" name="activity_type" required>
                <option value="" disabled selected>선택하세요</option>
                <option value="휴일 프로그램">휴일 프로그램</option>
                <option value="소모임">소모임</option>
            </select>

            <label for="details">상세내용:</label>
            <textarea id="details" name="details" required></textarea>

            <label for="date">참여일자:</label>
            <input type="date" id="date" name="date" required>

            <label for="file">증빙자료:</label>
            <input type="file" id="file" name="file">

            <input type="submit" value="제출">
        </form>
    </div>
</body>
</html>