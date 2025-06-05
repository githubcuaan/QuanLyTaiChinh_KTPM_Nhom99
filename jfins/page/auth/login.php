<?php
session_start();
// Kết nối với database.
require_once '../../config/db_connect.php';

// Kiểm tra đầu vào có phải POST không
if($_SERVER["REQUEST_METHOD"] == "POST") {
    // Kiểm tra các trường nhập liệu có trống?
    if(empty($_POST['email']) || empty($_POST['password']))
    {
        $login_error = " Vui lòng nhập đầy đủ thông tin !";
        error_log("Missing email or password\n");
    }
    // Nếu không trống -> lấy thông tin để đăng nhập.
    else {
        $email = trim($_POST["email"]);
        $password = trim($_POST["password"]);
        
        // Chuẩn bị các câu lệnh truy vấn, biến để nhận data sau khi truy vấn
        try {
            // truy vấn id,... where email = :email (:email là biến sẽ được điền)
            $sql = "SELECT user_id, username, email, password FROM users WHERE email = :email";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':email', $email);
            $stmt->execute();

            // Kiểm tra: có tìm thấy user nào không
            if($stmt->rowCount() == 1)
            {
                // fetch : lấy 1 dòng từ kết quả truy vấn SQL
                // FETCH_ASSOC : trả kq dưới dạng mảng kết hợp
                $user = $stmt->fetch(PDO::FETCH_ASSOC);
                //debugging: in user được đăng nhập vào error_log
                error_log("user từ database: ". print_r($user, true));

                // Kiểm tra password được nhập
                if(password_verify($password, $user["password"])) 
                {
                    // Set session variables -> kiểm tra đã được đăng nhập chưa.
                    $_SESSION['user_id'] = $user['user_id'];
                    $_SESSION['username'] = $user['username'];

                    // debuging: lưu thông tin đã đăng nhập vào err log
                    error_log("Login successful for user: " . $user['username']);
                    error_log("Session data after login: " . print_r($_SESSION, true));

                    // đảm bảo (ensure) sesstion đã được ghi lại trước khi chuyển hướng (redict)
                    session_write_close();

                    // chuyển hướng sang index.php
                    header("Location: ../index.php");
                    exit();
                } else {
                   $login_error = "Mật khẩu không đúng";
                }
            } else {
               $login_error = "Email không tồn tại";
            }
        } catch (PDOException $e) {
           $login_error = "Có lỗi xảy ra: " . $e->getMessage();
            error_log("Database error: " . $e->getMessage());
        }
    }
}

// include auth.php chứa giao diện
include 'auth.php'
?>