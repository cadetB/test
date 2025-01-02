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

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("CSRF 토큰 검증 실패");
    }

    $activity_type = $_POST['activity_type'] ?? null;
    if (empty($activity_type)) {
        $message = "";
    } elseif (!in_array($activity_type, ['독서프로그램', '독후감 대회'])) {
        $message = "Error: 유효하지 않은 항목입니다.";
    } else {
        $details = trim($_POST['details'] ?? '');
        $date = $_POST['date'] ?? null;
        $award = isset($_POST['award']) && $_POST['award'] !== '' ? $_POST['award'] : null;
        $student_id = $_SESSION['student_id'];

        // 파일 업로드 처리
        $file_path = "";
        if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
            $upload_dir = "uploads/";
            if (!is_dir($upload_dir)) {
                if (!mkdir($upload_dir, 0777, true)) {
                    $message = "업로드 폴더 생성에 실패했습니다.";
                }
            }

            if (empty($message)) {
                $file_name = uniqid() . "_" . basename($_FILES['file']['name']);
                $target_file = $upload_dir . $file_name;
                $file_type = pathinfo($target_file, PATHINFO_EXTENSION);

                $allowed_types = ['jpg', 'jpeg', 'png', 'pdf'];
                $max_file_size = 2 * 1024 * 1024; // 2MB 제한

                if (!in_array(strtolower($file_type), $allowed_types)) {
                    $message = "허용되지 않는 파일 형식입니다. (허용: jpg, jpeg, png, pdf)";
                } elseif ($_FILES['file']['size'] > $max_file_size) {
                    $message = "파일 크기가 2MB를 초과합니다.";
                } else {
                    if (move_uploaded_file($_FILES['file']['tmp_name'], $target_file)) {
                        $file_path = $target_file;
                    } else {
                        $message = "파일 업로드에 실패했습니다. 다시 시도하세요.";
                    }
                }
            }
        } elseif (isset($_FILES['file'])) {
            // 업로드 실패 원인 출력
            switch ($_FILES['file']['error']) {
                case UPLOAD_ERR_INI_SIZE:
                case UPLOAD_ERR_FORM_SIZE:
                    $message = "업로드된 파일이 허용된 크기를 초과합니다.";
                    break;
                case UPLOAD_ERR_PARTIAL:
                    $message = "파일이 부분적으로만 업로드되었습니다.";
                    break;
                case UPLOAD_ERR_NO_FILE:
                    $message = "파일이 업로드되지 않았습니다.";
                    break;
                default:
                    $message = "알 수 없는 오류가 발생했습니다.";
            }
        }

        if (empty($message)) {
            $sql = "INSERT INTO reading_activities (student_id, activity_type, details, date, award, file_path) 
                    VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssssss", $student_id, $activity_type, $details, $date, $award, $file_path);

            if ($stmt->execute()) {
                $message = "제출이 완료되었습니다.";

            } else {
                $message = "Error: " . $stmt->error;
            }
            $stmt->close();
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
    <title>독서활동</title>
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
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
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
            height: 60px;
            resize: none;
        }
        input[type="submit"] {
            margin-top: 10px;
            padding: 10px 20px;
            background-color: #0000FF;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        input[type="submit"]:hover {
            background-color: #000099;
        }.top-right-link {
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
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const activityType = document.getElementById('activity_type');
            const awardField = document.getElementById('awardField');

            activityType.addEventListener('change', function () {
                if (activityType.value === '독후감 대회') {
                    awardField.style.display = 'block';
                } else {
                    awardField.style.display = 'none';
                }
            });
        });
    </script>
</head>
<body>
	<div class="top-right-link">
        <a href="select_category.php">홈으로</a>
    </div>
    <div class="container">
        <h1>독서활동</h1>
        <?php if (!empty($message)): ?>
            <p><?php echo htmlspecialchars($message); ?></p>
			<?php endif; ?>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

            <label for="activity_type">항목:</label>
            <select id="activity_type" name="activity_type" required>
                <option value="" disabled selected>선택하세요</option>
                <option value="독서프로그램">독서프로그램</option>
                <option value="독후감 대회">독후감 대회</option>
            </select>

            <div id="awardField" style="display:none;">
                <label for="award">수상:</label>
                <select id="award" name="award">
                    <option value="">선택하세요</option>
                    <option value="영내 입상">영내 입상</option>
                    <option value="대외 입상">대외 입상</option>
                </select>
            </div>

            <label for="details">상세 내용:</label>
            <textarea id="details" name="details"></textarea>

            <label for="date">참여 날짜:</label>
            <input type="date" id="date" name="date" required>

            <label for="file">증빙 자료:</label>
            <input type="file" id="file" name="file">

            <input type="submit" value="제출">
        </form>
    </div>
</body>
</html>
