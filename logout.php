<?php
session_start();
session_destroy(); // 세션 종료
header("Location: login.php");
exit();
?>