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

// Get the raw POST data
$raw_data = file_get_contents('php://input');
$data = json_decode($raw_data, true);

if (!isset($data['allocations']) || !is_array($data['allocations'])) {
    $response['message'] = 'Dữ liệu không hợp lệ';
    echo json_encode($response);
    exit();
}

try {
    $user_id = $_SESSION['user_id'];
    $conn->beginTransaction();

    // Update each jar allocation
    $update_sql = "UPDATE jar_allocations 
                  SET percentage = :percentage, updated_at = CURRENT_TIMESTAMP 
                  WHERE user_id = :user_id AND jar_id = :jar_id";
    $update_stmt = $conn->prepare($update_sql);

    foreach ($data['allocations'] as $allocation) {
        if (!isset($allocation['jar_id']) || !isset($allocation['percentage'])) {
            throw new Exception('Dữ liệu không hợp lệ');
        }

        $update_stmt->bindParam(':user_id', $user_id);
        $update_stmt->bindParam(':jar_id', $allocation['jar_id']);
        $update_stmt->bindParam(':percentage', $allocation['percentage']);
        $update_stmt->execute();
    }

    // Recalculate jar balances based on new percentages
    // First get total income
    $total_income_sql = "SELECT COALESCE(SUM(amount), 0) as total FROM incomes WHERE user_id = :user_id";
    $total_income_stmt = $conn->prepare($total_income_sql);
    $total_income_stmt->bindParam(':user_id', $user_id);
    $total_income_stmt->execute();
    $total_income = $total_income_stmt->fetch(PDO::FETCH_ASSOC)['total'];

    // Update each jar's balance
    $update_balance_sql = "UPDATE jar_balances 
                          SET balance = (:total_income * :percentage / 100),
                              updated_at = CURRENT_TIMESTAMP
                          WHERE user_id = :user_id AND jar_id = :jar_id";
    $update_balance_stmt = $conn->prepare($update_balance_sql);

    foreach ($data['allocations'] as $allocation) {
        $update_balance_stmt->bindParam(':total_income', $total_income);
        $update_balance_stmt->bindParam(':percentage', $allocation['percentage']);
        $update_balance_stmt->bindParam(':user_id', $user_id);
        $update_balance_stmt->bindParam(':jar_id', $allocation['jar_id']);
        $update_balance_stmt->execute();
    }

    $conn->commit();
    $response['success'] = true;
    $response['message'] = 'Cập nhật thành công';

} catch (Exception $e) {
    $conn->rollBack();
    $response['message'] = 'Lỗi: ' . $e->getMessage();
}

echo json_encode($response);
?> 