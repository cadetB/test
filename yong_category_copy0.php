<?php
session_start();
if (!isset($_SESSION['student_id'])) {
    header("Location: login.php");
    exit;
}
 
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

function handleError($errno, $errstr) {
    error_log("Error: [$errno] $errstr");
    echo "<p style='color: red;'>죄송합니다. 오류가 발생했습니다. 관리자에게 문의해주세요.</p>";
}

set_error_handler("handleError");

function getDatabaseConnection() {
    $servername = "localhost";
    $username = "root";
    $password = "1234";
    $dbname = "GhHj";
    $port = 3306;

    $conn = new mysqli($servername, $username, $password, $dbname, $port);
    $conn->set_charset("utf8mb4");

    if ($conn->connect_error) {
        handleError(E_USER_ERROR, "데이터베이스 연결 실패: " . $conn->connect_error);
    }

    return $conn;
}
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>용 분야 활동 입력</title>
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
            color: #4CAF50; 
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
            background-color: #4CAF50; 
            color: white; 
            border: none; 
            border-radius: 4px;
            cursor: pointer; 
            font-size: 16px;
        }
        button:hover { 
            background-color: #45a049; 
        }
        @media (max-width: 600px) {
            .button-group {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>용 분야 활동 입력</h1>
        <div class="button-group">
            <form action="physical_test.php" method="POST">
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                <button type="submit" aria-label="체력검정 입력">체력검정</button>
            </form>
            <form action="physical_certification.php" method="POST">
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                <button type="submit" aria-label="체육자격증 입력">체육자격증</button>
            </form>
            <form action="physical_competition.php" method="POST">
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                <button type="submit" aria-label="대회참여 입력">대회참여</button>
            </form>
        </div>
        <form action="select_category.php">
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
            <button type="submit" aria-label="홈으로 돌아가기">홈으로</button>
        </form>
    </div>
</body>
</html>