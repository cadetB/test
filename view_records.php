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

// 데이터베이스 연결
$conn = new mysqli($servername, $username, $password, $dbname);

// 연결 확인
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$student_id = $_SESSION['student_id'];
$records = [];

// 활동 기록 읽기
$sql = "SELECT * FROM activities WHERE student_id = ? ORDER BY category, activity_type";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $student_id);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $records[] = [
        'category' => $row['category'],
        'activity_type' => $row['activity_type'],
        'details' => $row['details'],
        'date' => $row['date']
    ];
}

$stmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html>
<head>
    <title>활동 기록 조회</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        h1 { color: #4CAF50; }
        table { border-collapse: collapse; width: 100%; margin-bottom: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #4CAF50; color: white; }
        .button {
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
            margin-top: 20px;
        }
        .button:hover { background-color: #3e8e41; }
    </style>
</head>
<body>
    <h1><?php echo htmlspecialchars($_SESSION['name']); ?>님의 활동 기록</h1>
    <?php if (empty($records)): ?>
        <p>기록된 활동이 없습니다.</p>
    <?php else: ?>
        <?php foreach (['지', '인', '용'] as $category): ?>
            <h2><?php echo htmlspecialchars($category); ?> 분야</h2>
            <table>
                <tr>
                    <th>활동 유형</th>
                    <th>세부 내용</th>
                    <th>날짜</th>
                </tr>
                <?php foreach ($records as $record): ?>
                    <?php if ($record['category'] === $category): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($record['activity_type']); ?></td>
                            <td><?php echo htmlspecialchars($record['details']); ?></td>
                            <td><?php echo htmlspecialchars($record['date']); ?></td>
                        </tr>
                    <?php endif; ?>
                <?php endforeach; ?>
            </table>
        <?php endforeach; ?>
    <?php endif; ?>
    <br>
    <button class="button" onclick="location.href='select_category.php'">홈으로</button>
</body>
</html>