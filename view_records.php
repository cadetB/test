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
$conn->set_charset("utf8mb4");

// 연결 확인
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$student_id = $_SESSION['student_id'];
$records = [];

// 활동 기록 읽기
$categories = ['지', '인', '용'];
foreach ($categories as $category) {
    $records[$category] = [];
    
    // 각 카테고리별 테이블에서 데이터 가져오기
    $tables = [
        '지' => ['language_exams', 'certifications', 'academic_conferences'],
        '인' => ['reading_activities', 'dop_activities', 'club_activities'],
        '용' => ['physical_tests', 'physical_certifications', 'physical_competitions']
    ];
    
    foreach ($tables[$category] as $table) {
        $sql = "SELECT * FROM $table WHERE student_id = ? ORDER BY date DESC";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $student_id);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            $records[$category][] = [
                'activity_type' => $table,
                'details' => json_encode($row), // 모든 세부 정보를 JSON으로 저장
                'date' => $row['date']
            ];
        }
        $stmt->close();
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>활동 기록 조회</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        h1 { color: #4CAF50; }
        h2 { color: #45a049; }
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
    <?php if (empty($records['지']) && empty($records['인']) && empty($records['용'])): ?>
        <p>기록된 활동이 없습니다.</p>
    <?php else: ?>
        <?php foreach ($categories as $category): ?>
            <h2><?php echo htmlspecialchars($category); ?> 분야</h2>
            <?php if (empty($records[$category])): ?>
                <p><?php echo htmlspecialchars($category); ?> 분야에 기록된 활동이 없습니다.</p>
            <?php else: ?>
                <table>
                    <tr>
                        <th>활동 유형</th>
                        <th>세부 내용</th>
                        <th>날짜</th>
                    </tr>
                    <?php foreach ($records[$category] as $record): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($record['activity_type']); ?></td>
                            <td><?php 
                                $details = json_decode($record['details'], true);
                                foreach ($details as $key => $value) {
                                    if ($key != 'student_id' && $key != 'date') {
                                        echo htmlspecialchars($key) . ": " . htmlspecialchars($value) . "<br>";
                                    }
                                }
                            ?></td>
                            <td><?php echo htmlspecialchars($record['date']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            <?php endif; ?>
        <?php endforeach; ?>
    <?php endif; ?>
    <br>
    <a href="select_category.php" class="button">홈으로</a>
</body>
</html>