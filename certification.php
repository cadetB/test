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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("CSRF 토큰 검증 실패");
    }

    $certification = isset($_POST['certification']) ? trim($_POST['certification']) : "";
    $date = $_POST['date'];
    $student_id = $_SESSION['student_id'];

    if (!empty(trim($certification))) { 
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
            echo "<script>alert('제출이 완료되었습니다.'); window.location.href = 'select_category.php';</script>";
            exit;
        } else {
            echo "<script>alert('제출 실패: " . htmlspecialchars($stmt->error) . "');</script>";
        }
        $stmt->close();
    }
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
        body { 
            font-family: Arial, sans-serif; 
            margin: 0;
            background-color: #f4f4f4; 
            display: flex; 
            justify-content: center; 
            align-items: center; 
            height: 100vh;
        }
        .container {
            max-width: 600px;
            padding: 20px;
            background-color: white;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            position: relative;
            text-align: center;
        }
        h1 {
            color: #0000FF;
            margin-bottom: 20px;
        }
        label {
            display: block;
            text-align: left;
            margin: 10px auto;
            width: 90%;
        }
        input[type="text"], input[type="date"], input[type="file"], .button {
            display: block;
            margin: 10px auto;
            width: 90%;
        }
        input[type="submit"], .button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #0000FF;
            color: white;
            border: none;
            cursor: pointer;
            text-align: center;
            font-size: 16px;
            border-radius: 5px;
            text-decoration: none;
        }
        input[type="submit"]:hover, .button:hover {
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
            text-decoration: underline;
            color: #000099;
        }
    </style>
</head>
<body>
    <div class="top-right-link">
        <a href="select_category.php">홈으로</a>
    </div>
    <div class="container">
        <h1>자격증 정보 입력</h1>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
            
            <label for="certification">자격증:</label>
            <input type="text" id="certification" name="certification" required>
            
            <label for="date">취득일자:</label>
            <input type="date" id="date" name="date" required>
            
            <label for="file">증빙 자료:</label> 
            <input type="file" id="file" name="file">
            
            <input type="submit" name="submit_certification" value="제출">
        </form>
    </div>
</body>
</html>