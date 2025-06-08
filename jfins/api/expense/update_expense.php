<?php
session_start();
require_once '../../config/db_connect.php';

header('Content-Type: application/json');
$response = array('success' => false, 'message' => '');

if (!isset($_SESSION['user_id'])) {
    $response['message'] = 'Vui lòng đăng nhập';
    echo json_encode($response);
    exit();
}

// Lấy dữ liệu từ request
$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['expense_id']) || !isset($data['date']) || !isset($data['amount']) || 
    !isset($data['description']) || !isset($data['jar_id'])) {
    $response['message'] = 'Thiếu thông tin cần thiết';
    echo json_encode($response);
    exit();
}

try {
    $user_id = $_SESSION['user_id'];
    $expense_id = $data['expense_id'];
    $expense_date = $data['date'];
    $amount = floatval($data['amount']);
    $description = $data['description'];
    $jar_id = $data['jar_id'];

    // Bắt đầu transaction
    $conn->beginTransaction();

    // Lấy thông tin chi tiêu cũ
    $get_old_expense_sql = "SELECT amount, jar_id FROM expenses WHERE expense_id = :expense_id AND user_id = :user_id";
    $get_old_expense_stmt = $conn->prepare($get_old_expense_sql);
    $get_old_expense_stmt->bindParam(':expense_id', $expense_id);
    $get_old_expense_stmt->bindParam(':user_id', $user_id);
    $get_old_expense_stmt->execute();
    $old_expense = $get_old_expense_stmt->fetch(PDO::FETCH_ASSOC);

    if (!$old_expense) {
        throw new Exception('Không tìm thấy chi tiêu cần sửa');
    }

    // Cập nhật số dư hũ cũ (hoàn trả số tiền cũ)
    $update_old_jar_sql = "UPDATE jar_balances 
                          SET balance = balance + :old_amount 
                          WHERE user_id = :user_id AND jar_id = :old_jar_id";
    $update_old_jar_stmt = $conn->prepare($update_old_jar_sql);
    $update_old_jar_stmt->bindParam(':old_amount', $old_expense['amount']);
    $update_old_jar_stmt->bindParam(':user_id', $user_id);
    $update_old_jar_stmt->bindParam(':old_jar_id', $old_expense['jar_id']);
    $update_old_jar_stmt->execute();

    // Kiểm tra số dư hũ mới
    $check_balance_sql = "SELECT balance FROM jar_balances 
                         WHERE user_id = :user_id AND jar_id = :jar_id";
    $check_balance_stmt = $conn->prepare($check_balance_sql);
    $check_balance_stmt->bindParam(':user_id', $user_id);
    $check_balance_stmt->bindParam(':jar_id', $jar_id);
    $check_balance_stmt->execute();
    $current_balance = $check_balance_stmt->fetchColumn();

    if ($current_balance < $amount) {
        throw new Exception('Số dư trong hũ không đủ!');
    }

    // Cập nhật chi tiêu
    $update_expense_sql = "UPDATE expenses 
                          SET expense_date = :expense_date, 
                              amount = :amount, 
                              description = :description, 
                              jar_id = :jar_id 
                          WHERE expense_id = :expense_id AND user_id = :user_id";
    $update_expense_stmt = $conn->prepare($update_expense_sql);
    $update_expense_stmt->bindParam(':expense_date', $expense_date);
    $update_expense_stmt->bindParam(':amount', $amount);
    $update_expense_stmt->bindParam(':description', $description);
    $update_expense_stmt->bindParam(':jar_id', $jar_id);
    $update_expense_stmt->bindParam(':expense_id', $expense_id);
    $update_expense_stmt->bindParam(':user_id', $user_id);

    if ($update_expense_stmt->execute()) {
        // Cập nhật số dư hũ mới
        $update_new_jar_sql = "UPDATE jar_balances 
                              SET balance = balance - :amount 
                              WHERE user_id = :user_id AND jar_id = :jar_id";
        $update_new_jar_stmt = $conn->prepare($update_new_jar_sql);
        $update_new_jar_stmt->bindParam(':amount', $amount);
        $update_new_jar_stmt->bindParam(':user_id', $user_id);
        $update_new_jar_stmt->bindParam(':jar_id', $jar_id);
        $update_new_jar_stmt->execute();

        // Commit transaction
        $conn->commit();

        $response['success'] = true;
        $response['message'] = 'Cập nhật chi tiêu thành công';
    } else {
        throw new Exception('Có lỗi khi cập nhật chi tiêu');
    }
} catch (Exception $e) {
    // Rollback transaction nếu có lỗi
    $conn->rollBack();
    $response['message'] = 'Có lỗi xảy ra: ' . $e->getMessage();
}

echo json_encode($response);
?> 