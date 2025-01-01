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
    echo "<p style='color: red;'>죄송합니다. 오류가 발생했습니다.</p>";
}

set_error_handler("handleError");
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>지 분야 활동 입력</title>
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
        @media (max-width: 600px) {
            .button-group {
                flex-direction: column;
            }
        }
        .top-right-link {
            position: absolute;
            top: 20px;
            right: 20px;
            font-size: 14px;
        }
        .top-right-link a {
            text-decoration: none;
            color: #0000FF;
            font-weight: normal;
        }
        .top-right-link a:hover {
            text-decoration: underline;
            color: #000099;
        }
    </style>
</head>
<body>
    <div class="top-right-link">
        <a href="select_category.php" aria-label="홈으로">홈으로</a>
    </div>
    <div class="container">
        <h1>지 분야 활동 입력</h1>
        <div class="button-group">
            <form action="language_exam.php" method="POST">
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                <button type="submit" aria-label="어학시험 입력">어학시험</button>
            </form>
            <form action="certification.php" method="POST">
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                <button type="submit" aria-label="자격증 입력">자격증</button>
            </form>
            <form action="academic_conference.php" method="POST">
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                <button type="submit" aria-label="학술대회 입력">학술대회</button>
            </form>
        </div>
    </div>
</body>
</html>