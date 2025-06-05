<?php

session_start();
require_once '../../config/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Kiểm tra nhập liệuliệu
    if (!isset($_POST['username']) || !isset($_POST['email']) || 
        !isset($_POST['password']) || !isset($_POST['confirm_password'])) {
        $error = "Vui lòng điền đầy đủ thông tin";
    } else {
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $confirmpassword = $_POST['confirm_password'];

        // kiểm tra mật khẩu xác nhận
        if ($confirmpassword != $password) {
            $error = "Mật khẩu xác nhận không khớp";
        } else {
            try {
                // Kiểm tra email đã tồn tại chưa
                $check_sql = "SELECT * FROM users WHERE email = :email";
                $check_stmt = $conn->prepare($check_sql);
                $check_stmt->bindParam(':email', $email);
                $check_stmt->execute();

                // nếu tồn tại email
                if ($check_stmt->rowCount() > 0) {
                    $error = "Email đã được sử dụng";
                } else {
                    // Mã hóa mật khẩu
                    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                    // thêm user mới bằng tất cả data vừa có
                    $sql = "INSERT INTO users (username, email, password) VALUE (:user, :email, :password)";
                    $stmt = $conn->prepare($sql);
                    $stmt->bindParam(':username', $username);
                    $stmt->bindParam(':email', $email);
                    $stmt->bindParam(':password', $hashed_password);
                    $stmt->execute();

                    if ($stmt->execute()) {
                        //nếu tạo thành công -> lưu thông tin vào $_session và chuyển hướng
                        $_SESSION['user_id'] = $conn->lastInsertId();
                        $_SESSION['username'] = $username;
                        
                        header("Location: ../index.php");
                        exit();
                    } else {
                        $error = "Có lỗi xảy ra, vui lòng thử lại";
                    }
                }
            } catch (PDOException $e) {
                $error ="Có lỗi xảy ra: ". $e->getMessage();
            }
        }
    }
}


include 'auth.php';
?>