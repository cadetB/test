<?php
session_start();

// 로그인 확인
if (!isset($_SESSION['name'])) {
    header("Location: login.php");
    exit();
}

$name = htmlspecialchars($_SESSION['name']);
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>카테고리 선택</title>
</head>
<body>
    <h1><?php echo $name; ?>님, 기입할 분야를 선택하세요.</h1>
    
    <!-- 카테고리 선택 -->
    <form action="redirect_category.php" method="post">
        <label for="category">카테고리:</label>
        <select id="category" name="category" required>
            <option value="지">지</option>
            <option value="인">인</option>
            <option value="용">용</option>
        </select>
        <br><br>
        <input type="submit" value="선택">
    </form>

    <!-- 로그아웃 -->
    <form action="logout.php" method="post" style="margin-top: 20px;">
        <button type="submit">로그아웃</button>
    </form>
</body>
</html>