<?php
session_start();

if (!isset($_SESSION['student_id'])) {
    header("Location: login.php");
    exit;
} 

header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

$servername = "localhost";
$username = "root";
$password = "1234";
$dbname = "GhHj";
$port = 3306;
?>

<!DOCTYPE html>
<html>
<head>
    <title>활동 분야 선택</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            margin: 20px;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background-color: white;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            text-align: center;
        }
        h1 { 
            color: #0000FF; 
            margin-bottom: 30px;
        }
        .button-group {
            display: flex;
            gap: 20px;
            justify-content: center;
            margin: 30px 0;
        }
        button { 
            padding: 15px 40px; 
            background-color: #0000FF; 
            color: white; 
            border: none; 
            border-radius: 4px;
            cursor: pointer; 
            font-size: 16px;
        }
        button:hover { 
            background-color: #000099; 
        }
        .top-right-links {
            position: absolute;
            top: 20px;
            right: 20px;
            font-size: 14px;
        }
        .top-right-links a {
            margin-left: 20px;
            text-decoration: none;
            color: #0000FF;
        }
        .top-right-links a:hover {
            text-decoration: underline;
            color: #000099;
        }
    </style>
</head>
<body>
    <div class="top-right-links">
        <a href="view_records.php">조회</a>
        <a href="logout.php">로그아웃</a>
    </div>
    <div class="container">
        <h1>분야 선택</h1>
        <div class="button-group">
            <form action="ji_category.php" method="POST">
                <button type="submit">지</button>
            </form>
            <form action="in_category.php" method="POST">
                <button type="submit">인</button>
            </form>
            <form action="yong_category.php" method="POST">
                <button type="submit">용</button>
            </form>
        </div>
    </div>
</body>
</html>