<?php
session_start();

// 로그인 확인
if (!isset($_SESSION['name'])) {
    header("Location: login.php");
    exit();
}

// 선택된 카테고리에 따라 리다이렉트
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['category'])) {
    $category = $_POST['category'];

    switch ($category) {
        case '지':
            header("Location: ji_category.php");
            break;
        case '인':
            header("Location: in_category.php");
            break;
        case '용':
            header("Location: yong_category.php");
            break;
        default:
            header("Location: select_category.php");
            break;
    }
    exit();
} else {
    header("Location: select_category.php");
    exit();
}