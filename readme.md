1. Setting Up the Server Environment -> mysql 
# Update system packages
sudo apt update && sudo apt upgrade -y %% sudo apt install nano -y (nano 편집기 -> vi 느낌)&& sudo apt install ufw -y (방화벽)
# Install Apache Web Server
sudo apt install apache2 -y
# Install PHP and required extensions
sudo apt install php libapache2-mod-php php-mysql -y
sudo apt install apache2 php libapache2-mod-php php-mysql mysql-server -y
# Install MySQL Server
sudo apt install mysql-server -y
# Start services
sudo systemctl start apache2
sudo systemctl start mysql
# Secure MySQL installation
sudo mysql_secure_installation
#password = 1234  (sudo mysql -u root -p  : 접속 명령어)
USE database_name;
	mysql> show tables;
	+---------------------+
	| Tables_in_ide_groom |
	+---------------------+
	| data                |
	+---------------------+
	1 row in set (0.00 sec)

	mysql> select * from data;
	Empty set (0.00 sec)

	mysql> desc data;
	+-------------+--------------+------+-----+-------------------+-----------------------------+
	| Field       | Type         | Null | Key | Default           | Extra                       |
	+-------------+--------------+------+-----+-------------------+-----------------------------+
	| id          | int(11)      | NO   | PRI | NULL              | auto_increment              |
	| title       | varchar(255) | NO   |     | NULL              |                             |
	| description | text         | NO   |     | NULL              |                             |
	| created_at  | timestamp    | NO   |     | CURRENT_TIMESTAMP |                             |
	| updated_at  | timestamp    | NO   |     | CURRENT_TIMESTAMP | on update CURRENT_TIMESTAMP |
	+-------------+--------------+------+-----+-------------------+-----------------------------+
	5 rows in set (0.00 sec)
## ide_groom 디비 삭제완료 
# Flow Diagram
1. Frontend:
HTML forms for input
JavaScript for interactivity
Dynamic tables to display data
2. Backend:
PHP handles CRUD operations.
MySQL stores data.
3. Deployment:
Hosted on Apache with URL routing.


User Input ---> PHP Scripts ---> MySQL Database
   ^                                  |
   |                                  v
HTML Page <--- JSON Response <--- Fetch Queries


# 라이브 서버의 경우:
호스팅 공급자(예: AWS, DigitalOcean 또는 웹 호스팅 서비스)를 사용합니다.
설치:
Apache/Nginx: HTTP 요청을 처리하는 웹 서버입니다.
PHP: 애플리케이션을 실행합니다.
MySQL: 데이터베이스용.

# How to commit to github
git init
git add .
git commit -m "messages blah blah"
git remote add origin https://github.com/cadetB/test.git
# 스테이지에 추가된 파일에 대한 master에 커밋하기 실행 (with message)
# push 실패하면 다음 명령어를 터미널에서 실행 
git push -u origin master
git config --global user.name "cadetB"
 or git config --global user.email "your-email@example.com"
Password for 'https://cadetB@github.com': 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

1. 구름에서 작업 후 각 파일 저장
2. 자동으로 스테이지에서 제외된 파일로 감
3. + 눌러서 스테이지에 추가된 파일로 이동 
4. commit 메시지 입력 후 master 에 커밋하기 
5. commit 오류나면 명령어 실행 with token 
=============================================================
[V]K_view_records: 점수 입력칸 삭제(코드 수정 필요)
[ ]기입 정보 엑셀 파일 저장 기능 추가(코드 수정 필요)
★새 터미널 열기 단축키: alt+shift+T(mac: ​⌥⇧T)

## Total
- 총점 자동 계산 
- 분야별 환산점수 자동계산(사용자가 직접 계산 x)
	ex. 토익 환산점수, 지난학기 대비 환산점수
## 사용자 관점
- 국방인사정보체계처럼 누적이 되어 그래프, 표, 점수 등 데이터 제공하면 좋을듯 -> 개인자력, 활동 등 관리 ("생도생활 이렇게 했다~ 장단점 이거다~)
- 인증자료 업로드 -> 스캔, 캡처 
## 관리자 관점
- 인증: 눈으로 보기만 하면 되서 얼마 안걸림 (서면)
==================================================================================================================================================================================================
1. 과제소개(classroom)
- 제출물: 최종보고서 + 소스코드 + 발표동영상 10분
최종보고서
최종보고서는 여러분의 최종 프로젝트를 문서로 설명하고, 여러분의 프로젝트가 얼마나 가치있는지를 글로써 표현하는 과제이며, 아래와 같은 사항을 준수하세요
프로그램의 제작 동기 (해결하고자 하는 문제)
관련 소프트웨어 및 연구
제안하는 소프트웨어가 문제를 해결하는 방법 (알고리즘)
다른 소프트웨어 및 연구와의 차이점
시간계획 (마일즈스톤)
임무분장 
개발한 소프트웨어를 통해 해결한 문제와 제한사항
참고문헌 (아래 링크 참고)
* https://library.khu.ac.kr/seoul/referencingNcitation/chicago
보고서 작성 시 사용하는 소프트웨어는 자유지만 overleaf(latex) 를 권장
* https://www.overleaf.com/

최종보고서 작성 시 유의사항은 아래와 같습니다.

제출 포맷은 pdf 입니다.
위의 구성 요소를 누락하지 않도록 유의하세요.
관련 소프트웨어 및 연구는 본인이 만들고자 하는 소프트웨어와 유사한 소프트웨어 또는 연구를 조사하는 것입니다. 파이썬 등을 소개하라는 것이 아닙니다.
IEEE conference 포맷을 사용합니다. overleaf에 있는 포맷을 참고하세요:
* https://www.overleaf.com/latex/templates/ieee-conference-template/grfzhhncsfqn
=================================================================================================================================================================================================
소스 코드
소스 코드와 작성한 프로그램을 제출합니다. 
Github에 public 으로 업로드하세요.
* 평가가 종료되면 private으로 변경하는 것을 권장합니다.
접근가능한 링크를 공유해주세요.
===================================================================================================================================================================================================
최종 발표 동영상
동영상은 여러분이 작성한 최종 프로젝트의 결과를 발표하는 자리입니다. 최종 발표는 기록 유지와 공유를 위해 동영상으로 대체합니다. 해당 동영상에는 아래와 같은 내용이 필수로 포함되어야 하며, 그외의 형식은 자유입니다. 
프로그램의 제작 동기 (해결하고자 하는 문제)
관련 소프트웨어 및 연구
제안하는 소프트웨어가 문제를 해결하는 방법 (알고리즘)
다른 소프트웨어 및 연구와의 차이점
작성한 프로그램의 시연

동영상 제작시 주의할 사항은 아래와 같습니다.
동영상의 길이는 10분을 초과하지 않도록 합니다. 반대로 너무 짧지 않도록 유의해주세요.
동영상은 본인의 유튜브 계정에 올려 링크를 공유해주기 바랍니다. 
화질이 너무 떨어지지 않도록 해주세요.
스마트 폰으로 화면을 촬영하는 방식으로 하면 영상의 질이 좋지 않습니다. 화면을 녹화(screen recording)해주세요. 여러가지 툴들이 있습니다.
======================================================================================================================================================================================================


2. 개요(Notion 참고) 링크: https://classic-poet-a4b.notion.site/3ebd55038ffd47d6af865cc1358ab488?pvs=4




==============================================
(선호재)
register에서 등록 성공하면 login으로 넘어가도록 register 코드 약간 수정
login에서 이메일과 패스워드로 로그인 가능하도록 코드 설정
GPT에서는 모든 파일이 index와 연동될 필요는 없어보인다고 판단 -> 각 파일이 데이터베이스와 연동되도록만 하면 됨
### SSH/포트포워딩 기능은 컨테이너가 동작 중일 때 사용할 수 있고 컨테이너 재실행 시 IP와 Port가 변경됩니다.
## -> $servername = "13.209.161.15" / $port = 55876; 변동해야함!!! 컨테이너 새로 실행할 때마다

24.12.29.
register의 기능을 index로 이동
index에서 데이터베이스 연동 코드를 주석처리하면 정상적으로 페이지가 열리나, 다음 페이지로 이동하는 과정에서 오류 발생
데이터베이스 연동 코드 포함하여 실행하면 애초에 페이지가 열리지 않음(mysqli 문제)
xampp 파일에 php 파일이 있는데 Apache24 파일로 붙여넣기하고 파일 내용 수정하였음
로컬에 설치된 php 파일 수정하니 Connection failed: Access denied for user 'root'@'localhost' 오류 발생

* ----> index.php에 대한 오류 전체 해결했다고 판단, 데이터베이스에 정확하게 기록되는 것까지 확인



구 index.php
<?php
// Display feedback from register.php
if (isset($_GET['message'])) {
    echo "<p>" . htmlspecialchars($_GET['message']) . "</p>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register User</title>
</head>
<body>
    <h1>Register</h1>
    <form action="register.php" method="POST">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required><br>
        
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br>
        
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br>
        
        <button type="submit">Register</button>
    </form>

    <h2>Registered Users</h2>
    <table border="1">
        <thead>
            <tr>
                <th>Username</th>
                <th>Email</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Connect to database
            $conn = new mysqli("localhost", "root", "1234", "GhHj", 3306);

            // Check connection
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // Fetch users
            $result = $conn->query("SELECT username, email FROM users");

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                        <td>" . htmlspecialchars($row['username']) . "</td>
                        <td>" . htmlspecialchars($row['email']) . "</td>
                    </tr>";
                }
            } else {
                echo "<tr><td colspan='2'>No users registered yet</td></tr>";
            }

            $conn->close();
            ?>
        </tbody>
    </table>
</body>
</html>

구 register.php
<?php
session_start();
$error_message = "";

// MySQL 연결 설정
$servername = "localhost";
$username = "root";
$password = "1234";
$dbname = "GhHj";
$port = 3306;

// 데이터베이스 연결
$conn = new mysqli($servername, $username, $password, $dbname);

// 연결 확인
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully to the database!";


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $student_id = $_POST['student_id'];
    $name = $_POST['name'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $role = $_POST['role'];
    $confirmation_code = isset($_POST['confirmation_code']) ? $_POST['confirmation_code'] : '';

    // 기존 사용자 확인
    $stmt = $conn->prepare("SELECT * FROM users WHERE student_id = ?");
    $stmt->bind_param("s", $student_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $error_message = "이미 가입된 교번입니다.";
    } elseif ($password !== $confirm_password) {
        $error_message = "비밀번호가 일치하지 않습니다.";
    } elseif ($role === '관리자' && $confirmation_code !== '0000') {
        $error_message = "확인코드가 올바르지 않습니다.";
    } else {
        // 비밀번호 해싱
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // 사용자 정보 저장
        $stmt = $conn->prepare("INSERT INTO users (student_id, name, password, role) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $student_id, $name, $hashed_password, $role);

        if ($stmt->execute()) {
            // 등록 성공 시 login.php로 이동
            header("Location: login.php");
            exit();
        } else {
            $error_message = "등록 중 오류가 발생했습니다: " . $conn->error;
        }
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>사용자 등록</title>
</head>
<body>
    <h1>사용자 등록</h1>
    <?php if ($error_message): ?>
        <p style="color: red;"><?php echo htmlspecialchars($error_message); ?></p>
    <?php endif; ?>
    <form action="register.php" method="post">
        교번: <input type="text" name="student_id" required><br>
        이름: <input type="text" name="name" required><br>
        비밀번호: <input type="password" name="password" required><br>
        비밀번호 확인: <input type="password" name="confirm_password" required><br>
        권한:
        <select name="role" id="role" onchange="showConfirmationCode()">
            <option value="사용자">사용자</option>
            <option value="관리자">관리자</option>
        </select><br>
        <div id="confirmationCodeDiv" style="display:none;">
            확인코드: <input type="text" name="confirmation_code"><br>
        </div>
        <input type="submit" value="등록">
    </form>

    <script>
        function showConfirmationCode() {
            var role = document.getElementById('role').value;
            document.getElementById('confirmationCodeDiv').style.display = (role === '관리자') ? 'block' : 'none';
        }
    </script>
</body>
</html>

신 index.php
<?php
session_start();
$error_message = "";

// MySQL 연결 설정
$servername = "localhost";
$username = "root";
$password = "1234";
$dbname = "GhHj";
$port = 3306;

// 데이터베이스 연결
$conn = new mysqli("localhost", "root", "1234", "GhHj", 3306);

// 연결 확인
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $student_id = $_POST['student_id'];
    $name = $_POST['name'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $role = $_POST['role'];
    $confirmation_code = isset($_POST['confirmation_code']) ? $_POST['confirmation_code'] : '';

    // 기존 사용자 확인
    $stmt = $conn->prepare("SELECT * FROM users WHERE student_id = ?");
    $stmt->bind_param("s", $student_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $error_message = "이미 가입된 교번입니다.";
    } elseif ($password !== $confirm_password) {
        $error_message = "비밀번호가 일치하지 않습니다.";
    } elseif ($role === '관리자' && $confirmation_code !== '0000') {
        $error_message = "확인코드가 올바르지 않습니다.";
    } else {
        // 사용자 정보 저장
        $stmt = $conn->prepare("INSERT INTO users (student_id, name, password, role) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $student_id, $name, $password, $role);

        if ($stmt->execute()) {
            // 등록 성공 시 로그인 페이지로 이동
            header("Location: login.php");
            exit();
        } else {
            $error_message = "등록 중 오류가 발생했습니다: " . $conn->error;
        }
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register User</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        h1 { color: #4CAF50; }
        form { max-width: 400px; margin: auto; }
        label { display: block; margin: 10px 0 5px; }
        input, select { width: 100%; padding: 8px; margin-bottom: 10px; }
        button { padding: 10px 20px; background-color: #4CAF50; color: white; border: none; cursor: pointer; }
        button:hover { background-color: #45a049; }
        .error { color: red; }
    </style>
</head>
<body>
    <h1>사용자 등록</h1>
    <?php if ($error_message): ?>
        <p class="error"><?php echo htmlspecialchars($error_message); ?></p>
    <?php endif; ?>
    <form method="POST">
        <label for="student_id">교번:</label>
        <input type="text" id="student_id" name="student_id" required>

        <label for="name">이름:</label>
        <input type="text" id="name" name="name" required>

        <label for="password">비밀번호:</label>
        <input type="password" id="password" name="password" required>

        <label for="confirm_password">비밀번호 확인:</label>
        <input type="password" id="confirm_password" name="confirm_password" required>

        <label for="role">권한:</label>
        <select id="role" name="role" onchange="showConfirmationCode()" required>
            <option value="사용자">사용자</option>
            <option value="관리자">관리자</option>
        </select>

        <div id="confirmationCodeDiv" style="display:none;">
            <label for="confirmation_code">확인코드:</label>
            <input type="text" id="confirmation_code" name="confirmation_code">
        </div>

        <button type="submit">등록</button>
    </form>

    <script>
        function showConfirmationCode() {
            var role = document.getElementById('role').value;
            document.getElementById('confirmationCodeDiv').style.display = (role === '관리자') ? 'block' : 'none';
        }
    </script>
</body>
</html>

1. mysql -u root -p
2. use GhHj
3. show tables
* -> 테이블 어떤 거 있는지 확인, 코드와 대응됨
4. desc language_exams
* -> 아래처럼 gpt 질문하면 적절한 테이블 관련 명령어를 알려줌, 적용
mysql> desc language_exams;

+------------+--------------------------------------------------------------------------+------+-----+---------+----------------+
| Field      | Type                                                                     | Null | Key | Default | Extra          |
+------------+--------------------------------------------------------------------------+------+-----+---------+----------------+
| id         | int(11)                                                                  | NO   | PRI | NULL    | auto_increment |
| student_id | varchar(50)                                                              | NO   | MUL | NULL    |                |
| exam       | enum('토익','토익스피킹','HSK','JLPT','기타')                            | NO   |     | NULL    |                |
| score      | int(11)                                                                  | YES  |     | NULL    |                |
| grade      | enum('1급','2급','3급','4급','5급','6급','N1','N2','N3','N4','N5')       | YES  |     | NULL    |                |
| details    | text                                                                     | YES  |     | NULL    |                |
| date       | date                                                                     | NO   |     | NULL    |                |
| file_path  | varchar(255)                                                             | YES  |     | NULL    |                |
+------------+--------------------------------------------------------------------------+------+-----+---------+----------------+
8 rows in set (0.00 sec)
이건 language_exam.php에 대한 테이블인데 <?php
session_start();
if (!isset($_SESSION['student_id'])) {
    header("Location: login.php");
    exit;
}

// CSRF 토큰 생성
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
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

// 연결 확인
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // CSRF 토큰 검증
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("CSRF 토큰 검증 실패");
    }

    $exam = $_POST['exam'];
    $date = $_POST['date'];
    $student_id = $_SESSION['student_id'];
    $file_path = "";
    $score = null;
    $improvement = null;
    $grade = null;
    $details = null;

    if (isset($_FILES['file']) && $_FILES['file']['error'] == 0) {
        $target_dir = "uploads/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        $target_file = $target_dir . basename($_FILES["file"]["name"]);
        if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
            $file_path = $target_file;
        }
    }

    if ($exam === "토익" || $exam === "토익스피킹") {
        $score = $_POST['score'];
        $improvement = $_POST['improvement'];
    } elseif ($exam === "HSK") {
        $grade = $_POST['grade'];
    } elseif ($exam === "JLPT") {
        $grade = $_POST['grade'];
    } elseif ($exam === "기타") {
        $details = $_POST['details'];
        $score = $_POST['score'];
    }

    $sql = "INSERT INTO language_exams (student_id, exam, date, score, improvement, grade, details, file_path) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssss", $student_id, $exam, $date, $score, $improvement, $grade, $details, $file_path);

    if ($stmt->execute()) {
        $message = "제출이 완료되었습니다.";
    } else {
        $message = "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>어학시험 정보 입력</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        h1 { color: #4CAF50; }
        form { margin-bottom: 20px; }
        label, input, select, textarea { display: block; margin-bottom: 10px; }
        input[type="submit"], .button {
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
            box-shadow: 0 5px #999;
            margin-right: 10px;
        }
        input[type="submit"]:hover, .button:hover {background-color: #3e8e41}
        input[type="submit"]:active, .button:active {
            background-color: #3e8e41;
            box-shadow: 0 2px #666;
            transform: translateY(4px);
        }
    </style>
    <script>
        function handleExamChange() {
            const exam = document.getElementById('exam').value;
            document.getElementById('scoreDiv').style.display = (exam === '토익' || exam === '토익스피킹' || exam === '기타') ? 'block' : 'none';
            document.getElementById('improvementDiv').style.display = (exam === '토익' || exam === '토익스피킹') ? 'block' : 'none';
            document.getElementById('gradeDiv').style.display = (exam === 'HSK' || exam === 'JLPT') ? 'block' : 'none';
            document.getElementById('detailsDiv').style.display = (exam === '기타') ? 'block' : 'none';
        }
    </script>
</head>
<body>
    <h1>어학시험 정보 입력</h1>
    <?php
    if ($message) {
        echo "<p>$message</p>";
        echo "<button class='button' onclick=\"location.href='select_category.php'\">홈으로</button>";
    } else {
    ?>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
        <label for="exam">항목:</label>
        <select id="exam" name="exam" onchange="handleExamChange()" required>
            <option value="">선택하세요</option>
            <option value="토익">토익</option>
            <option value="토익스피킹">토익스피킹</option>
            <option value="HSK">HSK</option>
            <option value="JLPT">JLPT</option>
            <option value="기타">기타</option>
        </select>

        <div id="scoreDiv" style="display:none;">
            <label for="score">점수:</label>
            <input type="number" id="score" name="score">
        </div>

        <div id="improvementDiv" style="display:none;">
            <label for="improvement">전 학기 대비 향상 점수:</label>
            <input type="number" id="improvement" name="improvement">
        </div>

        <div id="gradeDiv" style="display:none;">
            <label for="grade">등급:</label>
            <select id="grade" name="grade">
                <option value="">선택하세요</option>
                <option value="1급">1급</option>
                <option value="2급">2급</option>
                <option value="3급">3급</option>
                <option value="4급">4급</option>
                <option value="5급">5급</option>
                <option value="6급">6급</option>
                <option value="N1">N1</option>
                <option value="N2">N2</option>
                <option value="N3">N3</option>
                <option value="N4">N4</option>
                <option value="N5">N5</option>
            </select>
        </div>

        <div id="detailsDiv" style="display:none;">
            <label for="details">상세 내용:</label>
            <textarea id="details" name="details" rows="4"></textarea>
        </div>

        <label for="date">응시일자:</label>
        <input type="date" id="date" name="date" required>

        <label for="file">증빙 자료:</label>
        <input type="file" id="file" name="file">

        <input type="submit" value="제출">
        <button type="button" class="button" onclick="location.href='select_category.php'">홈으로</button>
    </form>
    <?php } ?>
</body>
</html> language_exam.php 코드와 일치하는지, 테이블에 대한 수정이 필요한지 판단하고 알맞게 테이블을 수정하는 명령어를 알려줘. 상세 내용 등은 한글이 입력되도록 할 필요도 있어.