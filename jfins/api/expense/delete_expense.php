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
    if (!isset($data['expense_id'])) {
        $response['message'] = 'Thiếu thông tin expense_id!';
    } else {
        // Lấy thông tin để dùng sql
        $user_id = $_SESSION['user_id'];
        $expense_id = $data['expense_id'];
    
        try {
            // Bắt đầu transaction
            $conn->beginTransaction();

            // Lấy thông tin chi tiêu trước khi xóa
            $get_expense_sql = "SELECT amount, jar_id FROM expenses WHERE expense_id = :expense_id AND user_id = :user_id";
            $get_expense_stmt = $conn->prepare($get_expense_sql);
            $get_expense_stmt->bindParam(':expense_id', $expense_id);
            $get_expense_stmt->bindParam(':user_id', $user_id);
            $get_expense_stmt->execute();
            $expense = $get_expense_stmt->fetch(PDO::FETCH_ASSOC);

            if (!$expense) {
                throw new Exception('Không tìm thấy chi tiêu cần xóa');
            }

            // Hoàn trả số tiền vào hũ
            $update_jar_sql = "UPDATE jar_balances 
                             SET balance = balance + :amount 
                             WHERE user_id = :user_id AND jar_id = :jar_id";
            $update_jar_stmt = $conn->prepare($update_jar_sql);
            $update_jar_stmt->bindParam(':amount', $expense['amount']);
            $update_jar_stmt->bindParam(':user_id', $user_id);
            $update_jar_stmt->bindParam(':jar_id', $expense['jar_id']);
            $update_jar_stmt->execute();

            // Xóa chi tiêu
            $delete_sql = "DELETE FROM expenses 
                          WHERE expense_id = :expense_id AND user_id = :user_id";
            $delete_stmt = $conn->prepare($delete_sql);
            $delete_stmt->bindParam(':expense_id', $expense_id);
            $delete_stmt->bindParam(':user_id', $user_id);

            if($delete_stmt->execute()) {
                // Commit transaction nếu mọi thứ OK
                $conn->commit();
                $response['success'] = true;
                $response['message'] = 'Xóa chi tiêu thành công';
            } else {
                throw new Exception('Có lỗi khi xóa chi tiêu');
            }
        } catch (Exception $e) {
            // Rollback nếu có lỗi
            if ($conn->inTransaction()) {
                $conn->rollBack();
            }
            $response['message'] = 'Có lỗi xảy ra: ' . $e->getMessage();
        }
    }
}

header('Content-Type: application/json');
echo json_encode($response);
exit();
?> 