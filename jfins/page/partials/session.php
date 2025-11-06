<?php
session_start();

// nếu user_id của session chưa đc set -> chưa đăng nhập -> chuyển hướng đến trang đăng nhập
if (!isset($_SESSION['user_id'])) {
    error_log("Session user_id not set, redirecting to login page");
    // Redirect to the login page. Use an absolute path so the location is correct
    // regardless of the current request URL resolving rules.
    header('Location: /jfins/page/auth/auth.php');
    exit();
}
?>
