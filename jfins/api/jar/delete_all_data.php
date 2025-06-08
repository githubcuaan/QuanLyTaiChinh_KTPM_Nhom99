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

try {
    $user_id = $_SESSION['user_id'];
    $conn->beginTransaction();

    // Default jar allocations
    $default_allocations = [
        ['jar_id' => 1, 'percentage' => 55], // Thiết Yếu
        ['jar_id' => 2, 'percentage' => 10], // Tự Do Tài Chính
        ['jar_id' => 3, 'percentage' => 10], // Giáo Dục
        ['jar_id' => 4, 'percentage' => 10], // Hưởng Thụ
        ['jar_id' => 5, 'percentage' => 5],  // Thiện Tâm
        ['jar_id' => 6, 'percentage' => 10]  // Tiết Kiệm
    ];

    // Delete all incomes
    $delete_incomes_sql = "DELETE FROM incomes WHERE user_id = :user_id";
    $delete_incomes_stmt = $conn->prepare($delete_incomes_sql);
    $delete_incomes_stmt->bindParam(':user_id', $user_id);
    $delete_incomes_stmt->execute();

    // Delete all expenses
    $delete_expenses_sql = "DELETE FROM expenses WHERE user_id = :user_id";
    $delete_expenses_stmt = $conn->prepare($delete_expenses_sql);
    $delete_expenses_stmt->bindParam(':user_id', $user_id);
    $delete_expenses_stmt->execute();

    // Reset jar balances to 0
    $reset_balances_sql = "UPDATE jar_balances SET balance = 0 WHERE user_id = :user_id";
    $reset_balances_stmt = $conn->prepare($reset_balances_sql);
    $reset_balances_stmt->bindParam(':user_id', $user_id);
    $reset_balances_stmt->execute();

    // Reset jar allocations to default values
    $update_allocation_sql = "UPDATE jar_allocations 
                            SET percentage = :percentage, 
                                updated_at = CURRENT_TIMESTAMP 
                            WHERE user_id = :user_id AND jar_id = :jar_id";
    $update_allocation_stmt = $conn->prepare($update_allocation_sql);

    foreach ($default_allocations as $allocation) {
        $update_allocation_stmt->bindParam(':user_id', $user_id);
        $update_allocation_stmt->bindParam(':jar_id', $allocation['jar_id']);
        $update_allocation_stmt->bindParam(':percentage', $allocation['percentage']);
        $update_allocation_stmt->execute();
    }

    $conn->commit();
    $response['success'] = true;
    $response['message'] = 'Đã xóa tất cả dữ liệu thành công';

} catch (Exception $e) {
    $conn->rollBack();
    $response['message'] = 'Lỗi: ' . $e->getMessage();
}

echo json_encode($response);
?> 