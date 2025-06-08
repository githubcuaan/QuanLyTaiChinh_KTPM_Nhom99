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
            // Lấy thông tin khoản thu nhập trước khi xóa
            $get_income_sql = "SELECT amount FROM incomes WHERE income_id = :income_id AND user_id = :user_id";
            $get_income_stmt = $conn->prepare($get_income_sql);
            $get_income_stmt->bindParam(':income_id', $income_id);
            $get_income_stmt->bindParam(':user_id', $user_id);
            $get_income_stmt->execute();
            $income = $get_income_stmt->fetch(PDO::FETCH_ASSOC);

            if (!$income) {
                $response['message'] = 'Không tìm thấy khoản thu nhập';
                echo json_encode($response);
                exit();
            }

            // Lấy tỷ lệ phân bổ của các hũ
            $allocation_sql = "SELECT ja.jar_id, ja.percentage 
                             FROM jar_allocations ja 
                             WHERE ja.user_id = :user_id";
            $allocation_stmt = $conn->prepare($allocation_sql);
            $allocation_stmt->bindParam(':user_id', $user_id);
            $allocation_stmt->execute();
            $allocations = $allocation_stmt->fetchAll(PDO::FETCH_ASSOC);

            // Bắt đầu transaction
            $conn->beginTransaction();

            // Trừ số tiền từ các hũ
            foreach ($allocations as $allocation) {
                $jar_amount = ($income['amount'] * $allocation['percentage']) / 100;
                
                $update_sql = "UPDATE jar_balances 
                             SET balance = balance - :amount 
                             WHERE user_id = :user_id AND jar_id = :jar_id";
                $update_stmt = $conn->prepare($update_sql);
                $update_stmt->bindParam(':amount', $jar_amount);
                $update_stmt->bindParam(':user_id', $user_id);
                $update_stmt->bindParam(':jar_id', $allocation['jar_id']);
                $update_stmt->execute();
            }

            // Xóa khoản thu nhập
            $sql = "DELETE FROM incomes 
                    WHERE income_id = :income_id AND user_id = :user_id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':income_id', $income_id);
            $stmt->bindParam(':user_id', $user_id);

            if($stmt->execute()) {
                // Commit transaction nếu mọi thứ OK
                $conn->commit();
                $response['success'] = true;
                $response['message'] = 'Xóa thu nhập thành công';
            } else {
                // Rollback nếu có lỗi
                $conn->rollBack();
                $response['message'] = 'Có lỗi khi xóa thu nhập';
            }
        } catch (PDOException $e) {
            // Rollback nếu có lỗi
            if ($conn->inTransaction()) {
                $conn->rollBack();
            }
            $response['message'] = 'Có lỗi xảy ra: '. $e->getMessage();
        }
    }
}

header('Content-Type: application/json');
echo json_encode($response);
exit();
?> 