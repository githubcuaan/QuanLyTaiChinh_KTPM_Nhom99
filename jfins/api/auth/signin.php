<?php

session_start();
require_once '../../config/db_connect.php';

$response = array('success' => false, 'message' => '');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Kiểm tra nhập liệu
    if (!isset($_POST['username']) || !isset($_POST['email']) || 
        !isset($_POST['password']) || !isset($_POST['confirm_password'])) {
        $response['message'] = 'Vui lòng điền đầy đủ thông tin';
    } else {
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $confirmpassword = $_POST['confirm_password'];

        // kiểm tra mật khẩu xác nhận
        if ($confirmpassword != $password) {
            $response['message'] = "Mật khẩu xác nhận không khớp";
        } else {
            try {
                // Kiểm tra email đã tồn tại chưa
                $check_sql = "SELECT * FROM users WHERE email = :email";
                $check_stmt = $conn->prepare($check_sql);
                $check_stmt->bindParam(':email', $email);
                $check_stmt->execute();

                // nếu email đã tồn tại
                if ($check_stmt->rowCount() > 0) {
                    $response['message'] = "Email đã được sử dụng";
                } else {
                    try {
                        // Mã hóa mật khẩu
                        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                        // thêm user mới bằng tất cả data vừa có
                        $sql = "INSERT INTO users (username, email, password) VALUE (:username, :email, :password)";
                        $stmt = $conn->prepare($sql);
                        $stmt->bindParam(':username', $username);
                        $stmt->bindParam(':email', $email);
                        $stmt->bindParam(':password', $hashed_password);
                        
                        if ($stmt->execute()) {
                            //nếu tạo thành công -> lưu thông tin vào $_session và chuyển hướng
                            $_SESSION['user_id'] = $conn->lastInsertId();
                            $_SESSION['username'] = $username;
                            
                            $response['success'] = true;
                            $response['message'] = 'Đăng kí thành công';
                        } else {
                            $response['message'] = "Có lỗi xảy ra, vui lòng thử lại";
                        }
                    } catch (PDOException $e) {
                        if ($e->getCode() == 23000) {
                            $response['message'] = "Email đã được sử dụng";
                        } else {
                            $response['message'] = "Có lỗi xảy ra: " . $e->getMessage();
                        }
                    }
                }
            } catch (PDOException $e) {
                $response['message'] = "Có lỗi xảy ra: ". $e->getMessage();
            }
        }
    }
}


// Trả về response dạng JSON
header('Content-Type: application/json');
echo json_encode($response);
exit();