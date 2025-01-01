<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

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
$conn = new mysqli($servername, $username, $password, $dbname, $port);
$conn->set_charset("utf8mb4");

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// 사용자 정보
$student_id = $_SESSION['student_id'];
$message = "";

// 삭제 요청 처리
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_table']) && isset($_POST['delete_id'])) {
    $delete_table = $_POST['delete_table'];
    $delete_id = $_POST['delete_id'];

    // 삭제 쿼리 실행
    $sql = "DELETE FROM $delete_table WHERE id = ? AND student_id = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("is", $delete_id, $student_id);
        if ($stmt->execute()) {
            $message = "삭제가 완료되었습니다.";
        } else {
            $message = "삭제 중 오류가 발생했습니다: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $message = "SQL 준비 실패: " . $conn->error;
    }
}

// 테이블 및 열 매핑
$categories = ['지', '인', '용'];
$tables = [
    '지' => ['language_exams' => '어학시험', 'certifications' => '자격증', 'academic_conferences' => '학술대회'],
    '인' => ['reading_activities' => '독서활동', 'dops' => 'DOP 활동', 'club_activities' => '소모임'],
    '용' => ['physical_tests' => '체력검정', 'physical_certifications' => '체육자격증', 'physical_competitions' => '대회참여']
];
$column_names = [
    'language_exams' => [
        'exam' => '시험',
        'score' => '점수',
        'improvement' => '향상점수',
        'date' => '응시일자',
        'file_path' => '증빙자료'
    ],
    'certifications' => [
        'certification' => '자격증',
        'date' => '취득일자',
        'file_path' => '증빙자료'
    ],
    'academic_conferences' => [
        'conference_type' => '대회',
        'award' => '수상 여부',
        'details' => '세부내용',
        'date' => '참여일자',
        'file_path' => '증빙자료'
    ],
    'reading_activities' => [
        'activity_type' => '활동',
        'details' => '세부내용',
        'date' => '참여일자',
        'file_path' => '증빙자료'
    ],
    'dops' => [
        'type' => '활동',
        'details' => '세부내용',
        'participation_hours' => '참여시간',
        'selection' => 'DOP생도 선정',
        'date' => '참여일자',
        'file_path' => '증빙자료'
    ],
    'club_activities' => [
        'activity_type' => '소모임',
        'details' => '세부내용',
        'date' => '참여일자',
        'file_path' => '증빙자료'
    ],
    'physical_tests' => [
        'result' => '합불여부',
        'grade' => '등급',
        'total_score' => '총점',
        'improvement_score' => '향상점수',
        'file_path' => '증빙자료'
    ],
    'physical_certifications' => [
        'certification' => '자격증',
        'details' => '세부내용',
        'date' => '취득일자',
        'file_path' => '증빙자료'
    ],
    'physical_competitions' => [
        'competition' => '대회',
        'details' => '세부내용',
        'date' => '참여일자',
        'file_path' => '증빙자료'
    ]
];

// 데이터 가져오기
$records = [];
foreach ($categories as $category) {
    $records[$category] = [];
    foreach ($tables[$category] as $table => $display_name) {
        $sql = "SELECT * FROM $table WHERE student_id = ? ORDER BY date DESC";
        $stmt = $conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("s", $student_id);
            $stmt->execute();
            $result = $stmt->get_result();

            while ($row = $result->fetch_assoc()) {
                $records[$category][$table][] = $row;
            }
            $stmt->close();
        }
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
        body { font-family: Arial, sans-serif; margin: 20px; background-color: #f9f9f9; }
        h1 { color: #4CAF50; }
        h2 { color: #45a049; }
        h3 { color: #4CAF50; }
        table { border-collapse: collapse; width: 100%; margin-bottom: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #4CAF50; color: white; }
        .button { display: inline-block; padding: 10px 20px; font-size: 16px; background-color: #4CAF50; color: white; border: none; border-radius: 15px; text-decoration: none; margin-top: 20px; cursor: pointer; }
        .button:hover { background-color: #3e8e41; }
        .delete-button { color: red; cursor: pointer; }
    </style>
</head>
<body>
    <h1><?php echo htmlspecialchars($_SESSION['name']); ?>님의 활동 기록</h1>

    <?php if (!empty($message)): ?>
        <p style="color: green;"><?php echo htmlspecialchars($message); ?></p>
    <?php endif; ?>

    <?php foreach ($categories as $category): ?>
        <h2><?php echo htmlspecialchars($category); ?> 분야</h2>
        <?php foreach ($records[$category] as $table => $rows): ?>
            <h3><?php echo htmlspecialchars($tables[$category][$table]); ?></h3>
            <?php if (empty($rows)): ?>
                <p>기록된 정보가 없습니다.</p>
            <?php else: ?>
                <table>
                    <tr>
                        <?php foreach ($column_names[$table] as $key => $value): ?>
                            <th><?php echo htmlspecialchars($value); ?></th>
                        <?php endforeach; ?>
                        <th>삭제</th>
                    </tr>
                    <?php foreach ($rows as $row): ?>
                        <tr>
                            <?php foreach ($column_names[$table] as $key => $value): ?>
                                <td>
                                    <?php if ($key === 'file_path' && !empty($row[$key])): ?>
                                        <a href="<?php echo htmlspecialchars($row[$key]); ?>" download>다운로드</a>
                                    <?php elseif ($key === 'file_path'): ?>
                                        없음
                                    <?php else: ?>
                                        <?php echo htmlspecialchars($row[$key]); ?>
                                    <?php endif; ?>
                                </td>
                            <?php endforeach; ?>
                            <td>
                                <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" style="display:inline;">
                                    <input type="hidden" name="delete_table" value="<?php echo htmlspecialchars($table); ?>">
                                    <input type="hidden" name="delete_id" value="<?php echo htmlspecialchars($row['id']); ?>">
                                    <button type="submit" class="delete-button">삭제</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            <?php endif; ?>
        <?php endforeach; ?>
    <?php endforeach; ?>

    <form action="extract_records.php" method="post">
        <button class="button" type="submit">EXTRACT</button>
    </form>
    <a href="select_category.php" class="button">홈으로</a>
</body>
</html>