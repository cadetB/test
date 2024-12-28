<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

$message = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $certification_type = $_POST['certification_type'];
    $details = $_POST['details'];
    $date = $_POST['date'];
    $file_path = "";

    // 파일 업로드 처리
    if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = 'uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        $uploadFile = $uploadDir . basename($_FILES['file']['name']);
        if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadFile)) {
            $file_path = $uploadFile;
        }
    }

    // 데이터베이스 저장
    $servername = "localhost";
    $username = "root";
    $password = "1234";
    $dbname = "GhHj";
	$port = 3306;

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "INSERT INTO physical_certifications (username, certification_type, details, date, file_path) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $_SESSION['username'], $certification_type, $details, $date, $file_path);

    if ($stmt->execute()) {
        $message = "자격증 정보가 제출되었습니다.";
    } else {
        $message = "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html>
<head>
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
        echo "<p>$message</p>";
        echo "<button class='button' onclick=\"location.href='select_category.php'\">홈으로</button>";
    } else {
    ?>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
        <label for="certification_type">항목:</label>
        <select id="certification_type" name="certification_type" required>
            <option value="">선택하세요</option>
            <option value="무도 단증">무도 단증</option>
            <option value="기타 체육 자격증">기타 체육 자격증</option>
        </select>

        <label for="details">상세 내용:</label>
        <textarea id="details" name="details" rows="4" required></textarea>

        <label for="date">취득 일자:</label>
        <input type="date" id="date" name="date" required>

        <label for="file">증빙 자료:</label>
        <input type="file" id="file" name="file">

        <input type="submit" value="제출">
    </form>
    <?php } ?>
</body>
</html>