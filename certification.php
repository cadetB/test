<?php
session_start();
if (!isset($_SESSION['student_id'])) {
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

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $certification = $_POST['certification'];
    $date = $_POST['date'];
    $student_id = $_SESSION['student_id'];
    
    // 파일 업로드 처리
    $file_path = "";
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
    
    $sql = "INSERT INTO certifications (student_id, certification, date, file_path) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $student_id, $certification, $date, $file_path);
    
    if ($stmt->execute()) {
        $message = "자격증 정보가 성공적으로 저장되었습니다.";
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
    <title>자격증 정보 입력</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        h1 { color: #4CAF50; }
        form { margin-top: 20px; }
        label { display: block; margin-top: 10px; }
        input[type="text"], input[type="date"], input[type="file"] { width: 300px; padding: 5px; margin-top: 5px; }
        input[type="submit"] { margin-top: 20px; padding: 10px 20px; background-color: #4CAF50; color: white; border: none; cursor: pointer; }
        button { margin-top: 20px; padding: 10px 20px; background-color: #4CAF50; color: white; border: none; cursor: pointer; }
    </style>
</head>
<body>
    <h1>자격증 정보 입력</h1>
    <?php if ($message): ?>
        <p><?php echo $message; ?></p>
        <button onclick="location.href='select_category.php'">홈으로</button>
    <?php else: ?>
        <form action="" method="post" enctype="multipart/form-data">
            <label for="certification">자격증:</label>
            <input type="text" id="certification" name="certification" required><br>
            
            <label for="date">취득일자:</label>
            <input type="date" id="date" name="date" required><br>
            
            <label for="file">증빙 자료:</label> 
            <input type="file" id="file" name="file"><br>
            
            <input type="submit" value="제출">
        </form>
    <?php endif; ?>
</body>
</html>