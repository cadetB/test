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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // CSRF 토큰 검증
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("CSRF 토큰 검증 실패");
    }

    $certification = isset($_POST['certification']) ? trim($_POST['certification']) : ""; //  12.30 02:30 입력 값 확인 및 공백 제거
    $date = $_POST['date'];
    $student_id = $_SESSION['student_id'];
    
	// 12.30 02:30 데이터 유효성 검사 추가
	if (empty(trim($certification))) {
        $error = "자격증 이름을 입력하세요."; // 수정된 부분: 에러 메시지 저장
	} else{ 
		$file_path = ""; // 파일 업로드 처리
		if (isset($_FILES['file']) && $_FILES['file']['error'] == 0) {
			$target_dir = "uploads/";
			if (!is_dir($target_dir)) {
				mkdir($target_dir, 0777, true);
			}
			$target_file = $target_dir . basename($_FILES["file"]["name"]);
			if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
				$file_path = $target_file;
				error_log("파일 업로드 성공: " . $file_path);

			} else {
        		error_log("파일 업로드 실패");
    		}
		} else {
    		error_log("파일 업로드 오류 코드: " . $_FILES['file']['error']);	
		}

		$sql = "INSERT INTO certifications (student_id, certification, date, file_path) VALUES (?, ?, ?, ?)";
		$stmt = $conn->prepare($sql);
		$stmt->bind_param("ssss", $student_id, $certification, $date, $file_path);

		error_log("SQL 실행: student_id=$student_id, certification=$certification, date=$date, file_path=$file_path");
		if ($stmt->execute()) {
			$message = "제출이 완료되었습니다.";
		} else {
			$message = "Error: " . $stmt->error;	
			error_log("SQL 에러: " . $stmt->error);
		}
		$stmt->close();
	}	   
// Close the database connection to free up resources
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>자격증 정보 입력</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        h1 { color: #4CAF50; }
        form { margin-top: 20px; }
        label { display: block; margin-top: 10px; }
        input[type="text"], input[type="date"], input[type="file"] { width: 300px; padding: 5px; margin-top: 5px; }
        input[type="submit"], .button {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
            text-decoration: none;
            font-size: 16px;
        }
        input[type="submit"]:hover, .button:hover { background-color: #45a049; }
    </style>
</head>
<body>
    <h1>자격증 정보 입력</h1>
    <?php if ($message): ?>
        <p><?php echo htmlspecialchars($message); ?></p>
        <a href="select_category.php" class="button">홈으로</a>
    <?php else: ?>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
            
            <label for="certification">자격증:</label>
            <input type="text" id="certification" name="certification" required><br>
            
            <label for="date">취득일자:</label>
            <input type="date" id="date" name="date" required><br>
            
            <label for="file">증빙 자료:</label> 
            <input type="file" id="file" name="file"><br>
            
            <input type="submit" value="제출">
            <a href="select_category.php" class="button">홈으로</a>
        </form>
    <?php endif; ?>
</body>
</html>