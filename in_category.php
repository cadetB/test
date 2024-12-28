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

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>인 분야 활동 입력</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        h1 { color: #4CAF50; }
        form { margin-top: 20px; }
        label { display: block; margin-top: 10px; }
        input[type="text"], input[type="date"], select { width: 300px; padding: 5px; }
        input[type="submit"] { margin-top: 20px; padding: 10px 20px; background-color: #4CAF50; color: white; border: none; cursor: pointer; }
    </style>
    <script>
        function updateFormAction() {
            const activityType = document.getElementById('activity_type').value;
            const form = document.getElementById('activityForm');
            
            if (activityType === '독서활동') {
                form.action = 'reading_activity.php';
            } else if (activityType === 'DOP') {
                form.action = 'dop.php';
            } else if (activityType === '소모임') {
                form.action = 'club_activity.php';
            }
        }
    </script>
</head>
<body>
    <h1>인 분야 활동 입력</h1>
    <form method="post" id="activityForm" onsubmit="updateFormAction()">
        <label for="activity_type">활동 유형:</label>
        <select id="activity_type" name="activity_type" required>
            <option value="독서활동">독서활동</option>
            <option value="DOP">DOP</option>
            <option value="소모임">소모임</option>
        </select>
        
        <label for="details">세부 내용:</label>
        <input type="text" id="details" name="details" required>
        
        <label for="date">날짜:</label>
        <input type="date" id="date" name="date" required>
        
        <input type="submit" value="제출">
    </form>
    <br>
    <button onclick="location.href='select_category.php'">돌아가기</button>
</body>
</html>