<?php
session_start();
if (!isset($_SESSION['username'])) {
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

$message = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $result = $_POST['result'];
    $grade = $_POST['grade'];
    $totalScore = $_POST['totalScore'];
    $improvementScore = $_POST['improvementScore'];
    $username = $_SESSION['username'];

    $sql = "INSERT INTO physical_tests (username, result, grade, totalScore, improvementScore) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssii", $username, $result, $grade, $totalScore, $improvementScore);

    if ($stmt->execute()) {
        $message = "제출 완료되었습니다.";
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
    <title>자기개발활동 - 체력검정</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        h1 { color: #4CAF50; }
        form { margin-bottom: 20px; }
        label, input, select { display: block; margin-bottom: 10px; }
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
    <h1>체력검정</h1>
    <?php
    if ($message) {
        echo "<p>$message</p>";
        echo "<button class='button' onclick=\"location.href='select_category.php'\">홈으로</button>";
    } else {
    ?>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <label for="result">합불 여부:</label>
        <select id="result" name="result" required>
            <option value="">선택하세요</option>
            <option value="정기 합격">정기 합격</option>
            <option value="1차 추가 합격">1차 추가 합격</option>
            <option value="2차 추가 합격">2차 추가 합격</option>
            <option value="3차 추가 합격">3차 추가 합격</option>
            <option value="4차 추가 합격">4차 추가 합격</option>
            <option value="불합격">불합격</option>
        </select>

        <label for="grade">등급:</label>
        <select id="grade" name="grade" required>
            <option value="">선택하세요</option>
            <option value="골드">골드</option>
            <option value="실버">실버</option>
            <option value="특급">특급</option>
            <option value="1급">1급</option>
            <option value="2급">2급</option>
            <option value="3급">3급</option>
        </select>

        <label for="totalScore">총점:</label>
        <input type="number" id="totalScore" name="totalScore" required>

        <label for="improvementScore">이전 학기 대비 상승 점수:</label>
        <input type="number" id="improvementScore" name="improvementScore" required>

        <input type="submit" value="제출">
    </form>
    <?php } ?>
</body>
</html>