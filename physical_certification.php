<?php
session_start();
if (!isset($_SESSION['student_id'])) {
    header("Location: login.php");
    exit;
}

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$servername = "localhost";
$username = "root";
$password = "1234";
$dbname = "GhHj";
$port = 3306;

$message = '';
$show_form = true;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("CSRF 토큰 검증 실패");
    }

    $student_id = $_SESSION['student_id'];
    $certification_type = isset($_POST['certification_type']) ? trim($_POST['certification_type']) : '';
    $details = isset($_POST['details']) ? trim($_POST['details']) : '';
    $date = isset($_POST['date']) ? trim($_POST['date']) : '';
    $file_path = "";

    $valid_certifications = ['무도 단증', '기타 체육 자격증'];

    if (empty($certification_type) || !in_array($certification_type, $valid_certifications) || empty($date)) {
        $message = "";
    } else {
        if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = 'uploads/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            $uploadFile = $uploadDir . basename($_FILES['file']['name']);
            if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadFile)) {
                $file_path = $uploadFile;
            } else {
                $message = "파일 업로드 실패.";
                $show_form = true;
            }
        }

        $conn = new mysqli($servername, $username, $password, $dbname, $port);
        $conn->set_charset("utf8mb4");

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $sql = "INSERT INTO physical_certifications (student_id, certification, details, date, file_path) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("sssss", $student_id, $certification_type, $details, $date, $file_path);
            if ($stmt->execute()) {
                $message = "제출이 완료되었습니다.";
                $show_form = false;
            } else {
                $message = "오류: " . $stmt->error;
            }
            $stmt->close();
        } else {
            $message = "SQL 준비 실패: " . $conn->error;
        }

        $conn->close();
    }
}
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>자기개발활동 - 체육자격증</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        h1 { color: #4CAF50; }
        form { margin-bottom: 20px; }
        label, input, select, textarea { display: block; margin-bottom: 10px; }
        input[type="submit"], .button {
            display: inline-block;
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            text-align: center;
            text-decoration: none;
            outline: none;
            color: #fff;
            background-color: #4CAF50;
            border: none;
            border-radius: 15px;
            box-shadow: 0 5px #999;
        }
        input[type="submit"]:hover, .button:hover {background-color: #3e8e41}
        input[type="submit"]:active, .button:active {
            background-color: #3e8e41;
            box-shadow: 0 2px #666;
            transform: translateY(4px);
        }
    </style>
</head>
<body>
    <h1>체육자격증</h1>
    <?php
    if ($message) {
        echo "<p>" . htmlspecialchars($message) . "</p>";
        if (!$show_form) {
            echo "<a href='select_category.php' class='button'>홈으로</a>";
        }
    }
    if ($show_form) {
    ?>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

        <label for="certification_type">항목:</label>
        <select id="certification_type" name="certification_type" required>
            <option value="" disabled selected>선택하세요</option>
            <option value="무도 단증">무도 단증</option>
            <option value="기타 체육 자격증">기타 체육 자격증</option>
        </select>

        <label for="details">상세내용:</label>
        <textarea id="details" name="details" rows="4" required></textarea>

        <label for="date">취득일자:</label>
        <input type="date" id="date" name="date" required>

        <label for="file">증빙자료:</label>
        <input type="file" id="file" name="file">

        <input type="submit" value="제출">
        <a href="select_category.php" class="button">홈으로</a>
    </form>
    <?php } ?>
</body>
</html>