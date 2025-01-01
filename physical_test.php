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

$conn = new mysqli($servername, $username, $password, $dbname, $port);
$conn->set_charset("utf8mb4");


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = '';
$show_form = true;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("CSRF 토큰 검증 실패");
    }

    $student_id = (string) $_SESSION['student_id'];
    $result = isset($_POST['result']) ? trim($_POST['result']) : '';
    $grade = isset($_POST['grade']) ? trim($_POST['grade']) : '';
    $totalScore = isset($_POST['totalScore']) ? (int)$_POST['totalScore'] : 0;
    $improvementScore = isset($_POST['improvementScore']) ? (int)$_POST['improvementScore'] : 0;
    $date = date('Y-m-d');

    $valid_results = ['정기 합격', '1차 추가 합격', '2차 추가 합격', '3차 추가 합격', '4차 추가 합격', '불합격'];
    $valid_grades = ['골드', '실버', '특급', '1급', '2급', '3급'];

    if (empty($result) || !in_array($result, $valid_results) ||
        empty($grade) || !in_array($grade, $valid_grades) ||
        $totalScore <= 0 || $improvementScore < 0) {
        $message = "";
    } else {
        $sql = "INSERT INTO physical_tests (student_id, result, grade, total_score, improvement_score, date) 
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("sssiss", $student_id, $result, $grade, $totalScore, $improvementScore, $date);
            if ($stmt->execute()) {
                $message = "제출이 완료되었습니다.";
                $show_form = false;
            } else {
                if ($conn->errno === 1062) {
                    $message = "이미 제출된 데이터가 있습니다.";
                } else {
                    $message = "오류: " . $stmt->error;
                }
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
    <title>자기개발활동 - 체력검정</title>
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
            max-width: 650px; /* 좌우 규격 약간 늘림 */
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
        label, select, input[type="number"] {
            display: block;
            width: 92%; /* 좌우 규격 확장 */
            margin: 10px auto;
            font-size: 16px;
        }
        input[type="submit"], .button {
            display: inline-block;
            margin: 10px auto;
            padding: 10px 20px;
            background-color: #0000FF; /* 파란색 */
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-align: center;
            font-size: 16px;
        }
        input[type="submit"]:hover {
            background-color: #000099; /* 어두운 파란색 */
        }
        input[type="submit"]:active {
            background-color: #000099;
            box-shadow: 0 2px #666;
            transform: translateY(4px);
        }
        .top-right-link {
            position: absolute;
            top: 20px;
            right: 20px;
        }
        .top-right-link a {
            text-decoration: none;
            color: #0000FF; /* 파란색 */
            font-size: 16px;
        }
        .top-right-link a:hover {
            text-decoration: underline;
            color: #000099; /* 어두운 파란색 */
        }
    </style>
</head>
<body>
    <div class="top-right-link">
        <a href="select_category.php">홈으로</a>
    </div>
    <div class="container">
        <h1>체력검정</h1>

        <?php if (!empty($message)): ?>
            <p><?php echo htmlspecialchars($message); ?></p>
            <?php if (!$show_form): ?>
                <a href="select_category.php" class="button">홈으로</a>
            <?php endif; ?>
        <?php endif; ?>

        <?php if ($show_form): ?>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <input type="hidden" name="csrf_token" value="<?php echo isset($_SESSION['csrf_token']) ? $_SESSION['csrf_token'] : ''; ?>">

            <label for="result">합불 여부:</label>
            <select id="result" name="result" required>
                <option value="" disabled selected>선택하세요</option>
                <option value="정기 합격">정기 합격</option>
                <option value="1차 추가 합격">1차 추가 합격</option>
                <option value="2차 추가 합격">2차 추가 합격</option>
                <option value="3차 추가 합격">3차 추가 합격</option>
                <option value="4차 추가 합격">4차 추가 합격</option>
                <option value="불합격">불합격</option>
            </select>

            <label for="grade">등급:</label>
            <select id="grade" name="grade" required>
                <option value="" disabled selected>선택하세요</option>
                <option value="골드">골드</option>
                <option value="실버">실버</option>
                <option value="특급">특급</option>
                <option value="1급">1급</option>
                <option value="2급">2급</option>
                <option value="3급">3급</option>
            </select>

            <label for="totalScore">총점:</label>
            <input type="number" id="totalScore" name="totalScore" min="1" required>

            <label for="improvementScore">이전 학기 대비 상승 점수:</label>
            <input type="number" id="improvementScore" name="improvementScore" min="0" required>

            <input type="submit" value="제출">
        </form>
        <?php endif; ?>
    </div>
</body>
</html>