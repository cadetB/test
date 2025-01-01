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
    $date = $_POST['date'] ?? '';
    $details = isset($_POST['details']) ? trim($_POST['details']) : null;
    $hours = isset($_POST['hours']) ? (int)$_POST['hours'] : null;
    $subtype = isset($_POST['subtype']) ? $_POST['subtype'] : null;
    $dop_award = $_POST['dop_award'] ?? '';
    $student_id = $_SESSION['student_id'];

    if (empty($activity_type) || empty($date) || empty($dop_award)) {
        $message = "필수 항목을 입력해주세요.";
    } else {
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

        $sql = "INSERT INTO dops (student_id, type, date, details, participation_hours, sub_type, selection, file_path) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("ssssssss", $student_id, $activity_type, $date, $details, $hours, $subtype, $dop_award, $file_path);

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
            background-color: #f4f4f4;
        }
        .container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background-color: white;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            text-align: center;
        }
        h1 {
            color: #0000FF;
            margin-bottom: 30px;
        }
        form {
            margin-top: 20px;
        }
        label {
            display: block;
            margin-top: 10px;
            color: #000;
        }
        input[type="text"], input[type="date"], input[type="file"], select, textarea {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        input[type="submit"], .button {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #0000FF;
            color: white;
            border: none;
            cursor: pointer;
            text-decoration: none;
            font-size: 16px;
            border-radius: 4px;
        }
        input[type="submit"]:hover, .button:hover {
            background-color: #000099;
        }
        .top-right-link {
            position: absolute;
            top: 20px;
            right: 20px;
            font-size: 14px;
        }
        .top-right-link a {
            text-decoration: none;
            color: #0000FF;
        }
        .top-right-link a:hover {
            color: #000099;
            text-decoration: underline;
        }
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
    <div class="top-right-link">
        <a href="select_category.php">홈으로</a>
    </div>
    <div class="container">
        <h1>DOP 활동</h1>
        <?php if (!empty($message)): ?>
            <p><?php echo htmlspecialchars($message); ?></p>
            <?php if (!$show_form): ?>
                <a href="select_category.php" class="button">홈으로</a>
            <?php endif; ?>
        <?php endif; ?>

        <?php if ($show_form): ?>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                
                <label for="activity_type">항목:</label>
                <select id="activity_type" name="activity_type" onchange="toggleFields()" required>
                    <option value="" disabled selected>선택하세요</option>
                    <option value="봉사활동">봉사활동</option>
                    <option value="헌혈">헌혈</option>
                </select>

                <div id="detailsField" style="display:none;">
                    <label for="details">상세내용:</label>
                    <textarea id="details" name="details" rows="4"></textarea>
                </div>

                <div id="hoursField" style="display:none;">
                    <label for="hours">참여시간:</label>
                    <input type="number" id="hours" name="hours" min="1">
                </div>

                <div id="subtypeField" style="display:none;">
                    <label for="subtype">세부항목:</label>
                    <select id="subtype" name="subtype">
                        <option value="" disabled selected>선택하세요</option>
                        <option value="전혈">전혈</option>
                        <option value="혈장">혈장</option>
                    </select>
                </div>

                <label for="date">참여일자:</label>
                <input type="date" id="date" name="date" required>

                <label for="dop_award">DOP생도 선정:</label>
                <select id="dop_award" name="dop_award" required>
                    <option value="" disabled selected>선택하세요</option>
                    <option value="최우수">최우수</option>
                    <option value="우수">우수</option>
                    <option value="장려">장려</option>
                    <option value="해당 없음">해당 없음</option>
                </select>

                <label for="file">증빙자료:</label>
                <input type="file" id="file" name="file">

                <input type="submit" value="제출">
            </form>
        <?php endif; ?>
    </div>
</body>
</html>