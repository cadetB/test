<?php
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$error_message = "";

// MySQL 연결 설정
$servername = "localhost";
$username = "root";
$password = "1234";
$dbname = "GhHj";
$port = 3306;

// 데이터베이스 연결
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("데이터베이스 연결 실패: " . $conn->connect_error);
}

// 로그인 처리
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['action']) && $_POST['action'] === "login") {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        // 이메일 확인
        $stmt = $conn->prepare("SELECT name, password FROM users WHERE email = ?");
        if (!$stmt) {
            die("쿼리 준비 실패: " . $conn->error);
        }
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            $error_message = "등록되지 않은 이메일입니다.";
        } else {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                $_SESSION['name'] = $user['name'];
                header("Location: select_category.php");
                exit();
            } else {
                $error_message = "비밀번호가 일치하지 않습니다.";
            }
        }
        $stmt->close();
    } elseif (isset($_POST['action']) && $_POST['action'] === "register") {
        header("Location: index.php");
        exit();
    }
}

$conn->close();
?>