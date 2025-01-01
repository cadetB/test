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

//12.30 01:45 ** submit_exam 버튼이 눌려야만 DB 저장 로직을 실행 **/


if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['submit_exam'])) {
	// CSRF 토큰 검증
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("CSRF 토큰 검증 실패");
    }
//12.30 01:45 
    $student_id  = $_SESSION['student_id'];
    $exam        = $_POST['exam']        ?? '';
    $score       = null;
    $improvement = null;
    $grade       = null;
    $details     = null;
    $date        = $_POST['date']        ?? '';
    $file_path   = "";
    // 파일 업로드 처리

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
    // 시험 종류에 따라 값 분기/ //12.30 01:45 
    /*if ($exam === "토익" || $exam === "토익스피킹") {
        $score = $_POST['score'];
        $improvement = $_POST['improvement'];
    } elseif ($exam === "HSK") {
        $grade = $_POST['grade'];
    } elseif ($exam === "JLPT") {
        $grade = $_POST['grade'];
    } elseif ($exam === "기타") {
        $details = $_POST['details'];
        $score = $_POST['score'];
    }*/
	if ($exam === "토익" || $exam === "토익스피킹") {
        $score       = $_POST['score']       ?? '';
        $improvement = $_POST['improvement'] ?? '';
    } elseif ($exam === "HSK" || $exam === "JLPT") {
        $grade = $_POST['grade'] ?? '';
    } elseif ($exam === "기타") {
        $score   = $_POST['score']   ?? '';
        $details = $_POST['details'] ?? '';
    }
	 // language_exams 테이블 구조: (id, student_id, exam, score, improvement, grade, details, date, file_path)
    // id는 AUTO_INCREMENT이므로 제외하고 삽입.
    $sql = "INSERT INTO language_exams (student_id, exam, score, improvement, grade, details, date, file_path) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

	$stmt = $conn->prepare($sql); //12.30 01:45
    $stmt->bind_param("ssssssss",
        $student_id,
        $exam,
        $score,
        $improvement,
        $grade,
        $details,
        $date,
        $file_path
    ); //12.30 01:45 
	
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
    <title>어학시험 정보 입력</title>
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
            margin-right: 10px;
        }
        input[type="submit"]:hover, .button:hover {background-color: #3e8e41}
        input[type="submit"]:active, .button:active {
            background-color: #3e8e41;
            box-shadow: 0 2px #666;
            transform: translateY(4px);
        }
    </style>
    <script>
        function handleExamChange() {
            const exam = document.getElementById('exam').value;
            document.getElementById('scoreDiv').style.display = (exam === '토익' || exam === '토익스피킹' || exam === '기타') ? 'block' : 'none';
            document.getElementById('improvementDiv').style.display = (exam === '토익' || exam === '토익스피킹') ? 'block' : 'none';
            document.getElementById('gradeDiv').style.display = (exam === 'HSK' || exam === 'JLPT') ? 'block' : 'none';
            document.getElementById('detailsDiv').style.display = (exam === '기타') ? 'block' : 'none';
        }
    </script>
</head>
<body>
    <h1>어학시험 정보 입력</h1>
    <?php
    if ($message) {
        echo "<p>$message</p>";
        echo "<button class='button' onclick=\"location.href='select_category.php'\">홈으로</button>";
    } else {
    ?>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
        <label for="exam">항목:</label>
        <select id="exam" name="exam" onchange="handleExamChange()" required>
            <option value="">선택하세요</option>
            <option value="토익">토익</option>
            <option value="토익스피킹">토익스피킹</option>
            <option value="HSK">HSK</option>
            <option value="JLPT">JLPT</option>
            <option value="기타">기타</option>
        </select>

        <div id="scoreDiv" style="display:none;">
            <label for="score">점수:</label>
            <input type="number" id="score" name="score">
        </div>

        <div id="improvementDiv" style="display:none;">
            <label for="improvement">전 학기 대비 향상 점수:</label>
            <input type="number" id="improvement" name="improvement">
        </div>

        <div id="gradeDiv" style="display:none;">
            <label for="grade">등급:</label>
            <select id="grade" name="grade">
                <option value="">선택하세요</option>
                <option value="1급">1급</option>
                <option value="2급">2급</option>
                <option value="3급">3급</option>
                <option value="4급">4급</option>
                <option value="5급">5급</option>
                <option value="6급">6급</option>
                <option value="N1">N1</option>
                <option value="N2">N2</option>
                <option value="N3">N3</option>
                <option value="N4">N4</option>
                <option value="N5">N5</option>
            </select>
        </div>

        <div id="detailsDiv" style="display:none;">
            <label for="details">상세내용:</label>
            <textarea id="details" name="details" rows="4"></textarea>
        </div>
		
		<label for="date">응시일자:</label>
        <input type="date" id="date" name="date" required><br>

        <label for="file">증빙자료:</label>
        <input type="file" id="file" name="file">

        <input type="submit" name="submit_exam" value="제출">
        <button type="button" class="button" onclick="location.href='select_category.php'">홈으로</button>
    </form>
    <?php } ?>
</body>
</html>