<?php
session_start();

// 세션 및 CSRF 토큰 설정
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

$message = '';
$show_form = true;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("CSRF 토큰 검증 실패");
    }

    $competition_type = $_POST['competition_type'] ?? '';
    $category = $_POST['category'] ?? null;
    $details = trim($_POST['details'] ?? '');
    $date = $_POST['date'] ?? '';
    $file_path = '';

    if (empty($competition_type) || empty($details) || empty($date)) {
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
            }
        }

        $conn = new mysqli($servername, $username, $password, $dbname, $port);
        $conn->set_charset("utf8mb4");

        if ($conn->connect_error) {
            die("DB 연결 실패: " . $conn->connect_error);
        }

        $sql = "INSERT INTO physical_competitions (student_id, competition, sub_category, details, date, file_path)
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            $stmt->bind_param(
                "ssssss",
                $_SESSION['student_id'],
                $competition_type,
                $category,
                $details,
                $date,
                $file_path
            );

            if ($stmt->execute()) {
                $message = "제출이 완료되었습니다.";
                $show_form = false;
            } else {
                $message = "DB 저장 오류: " . $stmt->error;
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
    <title>대회참여</title>
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
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
        }
    </style>
    <script>
        function toggleCategory() {
            const competitionType = document.getElementById('competition_type').value;
            const categoryDiv = document.getElementById('categoryDiv');
            categoryDiv.style.display = competitionType === '마라톤' ? 'block' : 'none';
        }
    </script>
</head>
<body>
    <h1>대회참여</h1>
    <?php if ($message): ?>
        <p><?php echo htmlspecialchars($message); ?></p>
    <?php endif; ?>

    <?php if ($show_form): ?>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

            <label for="competition_type">대회 종류:</label>
            <select id="competition_type" name="competition_type" onchange="toggleCategory()" required>
                <option value="" disabled selected>선택하세요</option>
                <option value="대외 활동">대외 활동</option>
                <option value="마라톤">마라톤</option>
                <option value="트라이애슬론">트라이애슬론</option>
            </select>

            <div id="categoryDiv" style="display:none;">
                <label for="category">종목:</label>
                <select id="category" name="category">
                    <option value="">선택하세요</option>
                    <option value="풀코스">풀코스</option>
                    <option value="하프코스">하프코스</option>
                    <option value="애니멀런">애니멀런</option>
                    <option value="언택트">언택트</option>
                </select>
            </div>

            <label for="details">상세내용:</label>
            <textarea id="details" name="details" rows="4" required></textarea>

            <label for="date">참여일자:</label>
            <input type="date" id="date" name="date" required>

            <label for="file">증빙자료:</label>
            <input type="file" id="file" name="file">

            <input type="submit" value="제출">
			<a href="select_category.php" class="button">홈으로</a>
        </form>
    <?php endif; ?>
</body>
</html>


