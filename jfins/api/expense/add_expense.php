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
    if (!isset($data['date']) || !isset($data['amount']) || !isset($data['description']) || !isset($data['jar_id'])){
        $response['message'] = 'Vui lòng điền đủ thông tin!';
        error_log("Missing required fields. Data received: " . print_r($data, true));
    } else {
        // Lấy thông tin để dùng sql
        $user_id = $_SESSION['user_id'];
        $expense_date = $data['date'];
        $amount = $data['amount'];
        $description = $data['description'];
        $jar_id = $data['jar_id'];
    
        error_log("Processing expense addition - User ID: $user_id, Date: $expense_date, Amount: $amount, Description: $description, Jar ID: $jar_id");
    
        try {
            // Bắt đầu transaction
            $conn->beginTransaction();

            // Kiểm tra số dư của hũ
            $check_balance_sql = "SELECT balance FROM jar_balances WHERE user_id = :user_id AND jar_id = :jar_id";
            $check_balance_stmt = $conn->prepare($check_balance_sql);
            $check_balance_stmt->bindParam(':user_id', $user_id);
            $check_balance_stmt->bindParam(':jar_id', $jar_id);
            $check_balance_stmt->execute();
            $current_balance = $check_balance_stmt->fetchColumn();

            if ($current_balance < $amount) {
                throw new Exception('Số dư trong hũ không đủ!');
            }

            // Thêm chi tiêu vào database
            $sql = "INSERT INTO expenses (user_id, expense_date, amount, description, jar_id) 
                   VALUES (:user_id, :expense_date, :amount, :description, :jar_id)";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':user_id', $user_id);
            $stmt->bindParam(':expense_date', $expense_date);
            $stmt->bindParam(':amount', $amount);
            $stmt->bindParam(':description', $description);
            $stmt->bindParam(':jar_id', $jar_id);

            if($stmt->execute()) {
                // Cập nhật số dư của hũ
                $update_balance_sql = "UPDATE jar_balances 
                                     SET balance = balance - :amount 
                                     WHERE user_id = :user_id AND jar_id = :jar_id";
                $update_balance_stmt = $conn->prepare($update_balance_sql);
                $update_balance_stmt->bindParam(':amount', $amount);
                $update_balance_stmt->bindParam(':user_id', $user_id);
                $update_balance_stmt->bindParam(':jar_id', $jar_id);
                $update_balance_stmt->execute();

                // Commit transaction
                $conn->commit();

                $response['success'] = true;
                $response['message'] = 'Thêm chi tiêu thành công';
                error_log("Expense added successfully");
            } else {
                throw new Exception('Có lỗi khi thêm chi tiêu');
            }
        } catch (Exception $e) {
            // Rollback transaction nếu có lỗi
            $conn->rollBack();
            $response['message'] = 'Có lỗi xảy ra: '. $e->getMessage();
            error_log("Error: " . $e->getMessage());
        }
    }
}

header('Content-Type: application/json');
echo json_encode($response);
exit();

?> 