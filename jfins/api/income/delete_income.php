<?php
session_start();
require_once '../../config/db_connect.php';

$response = array('success' => false, 'message' => '');

// Kiểm tra phương thức truyền
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Kiểm tra đăng nhập
    if (!isset($_SESSION['user_id'])) {
        $response['message'] = 'Vui lòng đăng nhập';
        echo json_encode($response);
        exit();
    }

    // Lấy dữ liệu từ request  
    $raw_data = file_get_contents('php://input');
    $data = json_decode($raw_data, true);

    // Kiểm tra dữ liệu có trống hay không
    if (!isset($data['income_id'])) {
        $response['message'] = 'Thiếu thông tin income_id!';
    } else {
        // Lấy thông tin để dùng sql
        $user_id = $_SESSION['user_id'];
        $income_id = $data['income_id'];
    
        // truy vấn -> xóa dữ liệu trong db
        try {
            $sql = "DELETE FROM incomes 
                    WHERE income_id = :income_id AND user_id = :user_id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':income_id', $income_id);
            $stmt->bindParam(':user_id', $user_id);

            if($stmt->execute()) {
                $response['success'] = true;
                $response['message'] = 'Xóa thu nhập thành công';
            } else {
                $response['message'] = 'Có lỗi khi xóa thu nhập';
            }
        } catch (PDOException $e) {
            $response['message'] = 'Có lỗi xảy ra: '. $e->getMessage();
        }
    }
}

header('Content-Type: application/json');
echo json_encode($response);
exit();
?> 