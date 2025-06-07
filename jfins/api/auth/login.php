<?php
session_start();
// Kết nối với database.
require_once '../../config/db_connect.php';

$response = array('success' => false, 'message' => '');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Kiểm tra các trường nhập liệu có trống?
    if(empty($_POST['email']) || empty($_POST['password'])) {
        $response['message'] = "Vui lòng nhập đầy đủ thông tin!";
    } else {
        $email = trim($_POST["email"]);
        $password = trim($_POST["password"]);
        
        try {
            // truy vấn thông tin user
            $sql = "SELECT user_id, username, email, password FROM users WHERE email = :email";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':email', $email);
            $stmt->execute();

            if($stmt->rowCount() == 1) {
                $user = $stmt->fetch(PDO::FETCH_ASSOC);

                // Kiểm tra password
                if(password_verify($password, $user["password"])) {
                    // Set session variables
                    $_SESSION['user_id'] = $user['user_id'];
                    $_SESSION['username'] = $user['username'];

                    // Đảm bảo session được ghi
                    session_write_close();

                    $response['success'] = true;
                    $response['message'] = 'Đăng nhập thành công';
                } else {
                    $response['message'] = "Mật khẩu không đúng";
                }
            } else {
                $response['message'] = "Email không tồn tại";
            }
        } catch (PDOException $e) {
            $response['message'] = "Có lỗi xảy ra: " . $e->getMessage();
        }
    }
}

// Trả về response dạng JSON
header('Content-Type: application/json');
echo json_encode($response);
exit();
?>