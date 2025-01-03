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
        body { 
            font-family: Arial, sans-serif; 
            margin: 0; 
            padding: 20px; 
            background-color: #f9f9f9; 
        }
        h1 { 
            color: #0000FF; 
            text-align: center; 
            margin-bottom: 20px; 
        }
        form { 
            max-width: 400px; 
            margin: auto; 
            background: white; 
            padding: 20px; 
            border-radius: 5px; 
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); 
        }
        label { 
            display: block; 
            margin-top: 10px; 
            font-size: 16px; 
        }
        input { 
            width: 100%; 
            padding: 10px; 
            margin-top: 5px; 
            margin-bottom: 15px; 
            border: 1px solid #ccc; 
            border-radius: 4px; 
            box-sizing: border-box; 
        }
        button { 
            display: inline-block; 
            padding: 10px 20px; 
            background-color: #0000FF; 
            color: white; 
            border: none; 
            border-radius: 4px; 
            cursor: pointer; 
        }
        button:hover { 
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
            color: #000099; 
            text-decoration: underline; 
        }
    </style>
</head>
<body>
    <div class="top-right-link">
        <a href="login.php">로그아웃</a>
    </div>
    <h1>생도 조회</h1>
    <form action="view_user_records.php" method="post">
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
        <label for="student_id">교번:</label>
        <input type="text" id="student_id" name="student_id" required>
        <button type="submit">조회</button>
    </form>
</body>
</html>