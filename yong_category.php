<?php
session_start();
if (!isset($_SESSION['student_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $activity_type = $_POST['activity_type'];

    // 활동 유형에 따라 페이지 이동
    switch ($activity_type) {
        case '체력검정':
            header("Location: physical_test.php");
            break;
        case '체육자격증':
            header("Location: physical_certification.php");
            break;
        case '대회참여':
            header("Location: physical_competition.php");
            break;
        default:
            header("Location: select_category.php");
            break;
    }
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>용 분야 활동 입력</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        h1 { color: #4CAF50; }
        form { margin-top: 20px; }
        label { display: block; margin-top: 10px; }
        input[type="text"], input[type="date"], select { width: 300px; padding: 5px; }
        input[type="submit"] { margin-top: 20px; padding: 10px 20px; background-color: #4CAF50; color: white; border: none; cursor: pointer; }
    </style>
</head>
<body>
    <h1>용 분야 활동 입력</h1>
    <form method="post">
        <label for="activity_type">활동 유형:</label>
        <select id="activity_type" name="activity_type" required>
            <option value="체력검정">체력검정</option>
            <option value="체육자격증">체육자격증</option>
            <option value="대회참여">대회참여</option>
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