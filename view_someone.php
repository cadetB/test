<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== '관리자') {
    header("Location: login.php");
    exit;
}

if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>생도 조회</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background-color: #f9f9f9; }
        h1 { color: #4CAF50; }
        form { max-width: 400px; margin: auto; }
        label { display: block; margin-top: 10px; }
        input { width: 100%; padding: 10px; margin-top: 5px; margin-bottom: 15px; }
        button { 
            padding: 10px 20px; 
            background-color: #4CAF50; 
            color: white; 
            border: none; 
            border-radius: 4px; 
            cursor: pointer;
        }
        button:hover { background-color: #45a049; }
    </style>
</head>
<body>
    <h1>생도 조회</h1>
    <form action="view_user_records.php" method="post">
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
        <label for="student_id">교번:</label>
        <input type="text" id="student_id" name="student_id" required>
        <button type="submit">조회</button>
        <button type="button" onclick="window.location.href='login.php'">로그아웃</button>
    </form>
</body>
</html>