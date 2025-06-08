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
                            $user_id = $conn->lastInsertId();
                            $_SESSION['user_id'] = $user_id;
                            $_SESSION['username'] = $username;
                            
                            // Thêm cài đặt phần trăm hũ mặc định
                            $default_allocations = [
                                ['jar_id' => 1, 'percentage' => 55], // Thiết Yếu
                                ['jar_id' => 2, 'percentage' => 10], // Tự Do Tài Chính
                                ['jar_id' => 3, 'percentage' => 10], // Giáo Dục
                                ['jar_id' => 4, 'percentage' => 10], // Hưởng Thụ
                                ['jar_id' => 5, 'percentage' => 5],  // Thiện Tâm
                                ['jar_id' => 6, 'percentage' => 10]  // Tiết Kiệm
                            ];

                            $allocation_sql = "INSERT INTO jar_allocations (user_id, jar_id, percentage) VALUES (:user_id, :jar_id, :percentage)";
                            $allocation_stmt = $conn->prepare($allocation_sql);

                            foreach ($default_allocations as $allocation) {
                                $allocation_stmt->bindParam(':user_id', $user_id);
                                $allocation_stmt->bindParam(':jar_id', $allocation['jar_id']);
                                $allocation_stmt->bindParam(':percentage', $allocation['percentage']);
                                $allocation_stmt->execute();
                            }
                            
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