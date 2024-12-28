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

?>
<!DOCTYPE html>
<html>
<head>
    <title>지 분야 활동 입력</title>
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
            
            if (activityType === '어학시험') {
                form.action = 'language_exam.php';
            } else if (activityType === '자격증') {
                form.action = 'certification.php';
            } else if (activityType === '학술대회') {
                form.action = 'academic_conference.php';
            }
        }
    </script>
</head>
<body>
    <h1>지 분야 활동 입력</h1>
    <form method="post" id="activityForm" onsubmit="updateFormAction()">
        <label for="activity_type">활동 유형:</label>
        <select id="activity_type" name="activity_type" required>
            <option value="어학시험">어학시험</option>
            <option value="자격증">자격증</option>
            <option value="학술대회">학술대회</option>
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