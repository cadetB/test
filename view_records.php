<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION['student_id'])) {
    header("Location: login.php");
    exit;
} 


$servername = "localhost";
$username = "root";
$password = "1234";
$dbname = "GhHj";
$port = 3306;


$conn = new mysqli($servername, $username, $password, $dbname, $port);
$conn->set_charset("utf8mb4");

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}


$student_id = $_SESSION['student_id'];
$student_name = $_SESSION['name'];
$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_table'], $_POST['delete_id'])) {
    $delete_table = $_POST['delete_table'];
    $delete_id = (int)$_POST['delete_id'];


    $valid_tables = [
        'language_exams', 'certifications', 'academic_conferences',
        'reading_activities', 'dops', 'club_activities',
        'physical_tests', 'physical_certifications', 'physical_competitions'
    ];

    if (in_array($delete_table, $valid_tables)) {
        $sql = "DELETE FROM $delete_table WHERE id = ? AND student_id = ?";
        $stmt = $conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("is", $delete_id, $student_id);
            if ($stmt->execute()) {
                $message = "";
            } else {
                $message = "기록 삭제 실패: " . $stmt->error;
            }
            $stmt->close();
        } else {
            $message = "SQL 오류: " . $conn->error;
        }
    } else {
        $message = "유효하지 않은 테이블입니다.";
    }
}


$column_names = [
    'language_exams' => ['exam' => '시험', 'score' => '점수', 'improvement' => '향상점수', 'grade' => '등급', 'date' => '응시일자', 'file_path' => '증빙자료'],
    'certifications' => ['certification' => '자격증', 'date' => '취득일자', 'file_path' => '증빙자료'],
    'academic_conferences' => ['conference_type' => '대회', 'award' => '수상여부', 'details' => '세부내용', 'date' => '참여일자', 'file_path' => '증빙자료'],
    'reading_activities' => ['activity_type' => '활동', 'details' => '세부내용', 'date' => '참여일자', 'file_path' => '증빙자료'],
    'dops' => ['type' => '활동', 'details' => '세부내용', 'participation_hours' => '참여시간', 'selection' => 'DOP생도 선정', 'date' => '참여일자', 'file_path' => '증빙자료'],
    'club_activities' => ['activity_type' => '소모임', 'details' => '세부내용', 'date' => '참여일자', 'file_path' => '증빙자료'],
    'physical_tests' => ['result' => '합불여부', 'grade' => '등급', 'total_score' => '총점', 'improvement_score' => '향상점수', 'file_path' => '증빙자료'],
    'physical_certifications' => ['certification' => '자격증', 'details' => '세부내용', 'date' => '취득일자', 'file_path' => '증빙자료'],
    'physical_competitions' => ['competition' => '대회', 'details' => '세부내용', 'date' => '참여일자', 'file_path' => '증빙자료']
];


$categories = ['지', '인', '용'];
$tables = [
    '지' => ['language_exams' => '어학시험', 'certifications' => '자격증', 'academic_conferences' => '학술대회'],
    '인' => ['reading_activities' => '독서활동', 'dops' => 'DOP 활동', 'club_activities' => '소모임'],
    '용' => ['physical_tests' => '체력검정', 'physical_certifications' => '체육자격증', 'physical_competitions' => '대회참여']
];


if (isset($_GET['action']) && $_GET['action'] === 'download') {
    header('Content-Type: text/csv; charset=utf-8');
    header("Content-Disposition: attachment; filename=\"{$student_id}_{$student_name}.csv\"");

    $output = fopen('php://output', 'w');

    foreach ($categories as $category) {
        fputcsv($output, [$category . " 분야"]);
        foreach ($tables[$category] as $table => $display_name) {
            fputcsv($output, [$display_name]);
            $columns = array_diff(array_values($column_names[$table]), ['증빙자료']);
            fputcsv($output, $columns);

            $sql = "SELECT * FROM $table WHERE student_id = ? ORDER BY date DESC";
            $stmt = $conn->prepare($sql);
            if ($stmt) {
                $stmt->bind_param("s", $student_id);
                $stmt->execute();
                $result = $stmt->get_result();

                while ($row = $result->fetch_assoc()) {
                    $row_data = [];
                    foreach ($column_names[$table] as $key => $value) {
                        if ($value !== '증빙자료') {
                            $row_data[] = $row[$key] ?? '';
                        }
                    }
                    fputcsv($output, $row_data);
                }
                $stmt->close();
            }
            fputcsv($output, []);
        }
    }

    fclose($output);
    exit;
}


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
        h1 { color: #0000FF; text-align: center; }
        h2 { color: #0000FF; }
        h3 { color: #0000FF; }
        table { border-collapse: collapse; width: 100%; margin-bottom: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #0000FF; color: white; }
        .button {
            display: inline-block;
            padding: 10px 20px;
            font-size: 16px;
            background-color: #0000FF;
            color: white;
            border: none;
            border-radius: 15px;
            text-decoration: none;
            margin-top: 20px;
            cursor: pointer;
        }
        .button:hover { background-color: #000099; }
        .delete-button { color: red; cursor: pointer; }
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
        <a href="select_category.php">홈으로</a>
    </div>

    <h1><?php echo htmlspecialchars($student_name); ?>님의 활동 기록</h1>

    <?php if ($message): ?>
        <p><?php echo htmlspecialchars($message); ?></p>
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
                                        <a href="<?php echo htmlspecialchars($row[$key]); ?>" download>증빙자료</a>
                                    <?php elseif ($key === 'file_path'): ?>
                                        없음
                                    <?php else: ?>
                                        <?php echo htmlspecialchars($row[$key] ?? ''); ?>
                                    <?php endif; ?>
                                </td>
                            <?php endforeach; ?>
                            <td>
                                <form action="view_records.php" method="post" style="display:inline;">
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

    <form action="view_records.php" method="get">
        <button type="submit" name="action" value="download" class="button">다운로드</button>
    </form>
</body>
</html>