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
    error_log("Raw input data: " . $raw_data);
    
    $data = json_decode($raw_data, true);
    error_log("Decoded data: " . print_r($data, true));

    // Kiểm tra dữ liệu có trống hay không
    if (!isset($data['income_id'])) {
        $response['message'] = 'Thiếu thông tin income_id!';
        error_log("Missing income_id in data");
    } else if (!isset($data['date']) || !isset($data['amount']) || !isset($data['description'])) {
        $response['message'] = 'Vui lòng điền đủ thông tin!';
        error_log("Missing required fields. Data received: " . print_r($data, true));
    } else {
        // Lấy thông tin để dùng sql
        $user_id = $_SESSION['user_id'];
        $income_id = $data['income_id'];
        $income_date = $data['date'];
        $amount = $data['amount'];
        $description = $data['description'];
    
        error_log("Processing income update - User ID: $user_id, Income ID: $income_id, Date: $income_date, Amount: $amount, Description: $description");
    
        // truy vấn -> cập nhật dữ liệu trong db
        try {
            $sql = "UPDATE incomes 
                    SET income_date = :income_date, 
                        amount = :amount, 
                        description = :description 
                    WHERE income_id = :income_id 
                    AND user_id = :user_id";
            
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':income_id', $income_id);
            $stmt->bindParam(':user_id', $user_id);
            $stmt->bindParam(':income_date', $income_date);
            $stmt->bindParam(':amount', $amount);
            $stmt->bindParam(':description', $description);

            if($stmt->execute()) {
                $response['success'] = true;
                $response['message'] = 'Cập nhật thu nhập thành công';
                error_log("Income updated successfully");
            } else {
                $response['message'] = 'Có lỗi khi cập nhật thu nhập';
                error_log("SQL Error: " . print_r($stmt->errorInfo(), true));
            }
        } catch (PDOException $e) {
            $response['message'] = 'Có lỗi xảy ra: '. $e->getMessage();
            error_log("PDO Exception: " . $e->getMessage());
        }
    }
}

header('Content-Type: application/json');
echo json_encode($response);
exit();
?> 