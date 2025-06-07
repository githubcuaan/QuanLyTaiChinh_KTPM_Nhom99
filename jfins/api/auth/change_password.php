<?php 

    session_start();
    require_once('../../config/db_connect.php');

    // Kiểm tra xem user được đăng nhập chưa.
    if (!isset($_SESSION['user_id'])) {
        // báo lỗi 401 -> unauthurize
        http_response_code(401);
        echo json_encode(['success'=> false,'error'=> 'Unauthorized']);
        exit();
    }

    // Kiểm tra kiểu gửi thông tin -> chỉ nhận POST
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        echo json_encode(['success'=> false,'error'=> 'Method not allowed']);
        exit();
    }

    // Vượt qua kiểm tra -> đổi mật khẩu
    // lấy data từ POST 
    $data = json_decode(file_get_contents('php://input'), true);
    $current_password = $data['current_password'];
    $new_password = $data['new_password'];
    $confirm_password = $data['confirm_password'];

    // kiểm tra đầu vào
    if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Nhập đủ dữ liệu đi ba']);
        exit();
    }
    
    if ($new_password !== $confirm_password) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Mật khẩu mới không trùng nhau!']);
        exit();
    }

    // vượt qua kiểm thử -> đổi mật khẩu
    try {
        // lấy currantpassword từ database
        $user_id = $_SESSION['user_id'];
        $stmt = $conn->prepare('SELECT password FROM users WHERE user_id = :id');
        $stmt->bindParam(':id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // kiểm tra mật khẩu hiện tại đã nhập đúng chưa
        if (!password_verify($current_password, $user['password'])) {
            http_response_code(400);
            echo json_encode(['success'=> false,'message'=> 'Mật khẩu hiện tại sai rùi hjhj']);
            exit();
        }

        // nếu đúng -> đổi
        //hash pass mới
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

        // update trong database
        $stmt = $conn->prepare('UPDATE users SET password = :newPass WHERE user_id = :id');
        $stmt->bindParam(':newPass', $hashed_password, PDO::PARAM_STR);
        $stmt->bindParam(':id', $user_id);
        $stmt->execute();

        echo json_encode(['success'=> true,'message'=> 'Đổi mật khẩu thành công!']);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['success'=> false,'message'=> 'Đổi mật khẩu không thành công!']);
    }
?>