<?php
session_start();
require_once '../../config/db_connect.php';

$response = array('success' => false, 'message' => '', 'balance' => 0);

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

if (!isset($data['jar_id'])) {
    $response['message'] = 'Thiếu thông tin hũ';
    echo json_encode($response);
    exit();
}

try {
    $user_id = $_SESSION['user_id'];
    $jar_id = $data['jar_id'];
    
    error_log("Getting balance for user_id: $user_id, jar_id: $jar_id");

    // Kiểm tra xem có bản ghi trong jar_balances không
    $check_sql = "SELECT COUNT(*) FROM jar_balances WHERE user_id = :user_id AND jar_id = :jar_id";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bindParam(':user_id', $user_id);
    $check_stmt->bindParam(':jar_id', $jar_id);
    $check_stmt->execute();
    $exists = $check_stmt->fetchColumn();
    error_log("Record exists in jar_balances: " . ($exists ? 'Yes' : 'No'));

    if (!$exists) {
        // Nếu chưa có bản ghi, tạo mới với số dư 0
        $insert_sql = "INSERT INTO jar_balances (user_id, jar_id, balance) VALUES (:user_id, :jar_id, 0)";
        $insert_stmt = $conn->prepare($insert_sql);
        $insert_stmt->bindParam(':user_id', $user_id);
        $insert_stmt->bindParam(':jar_id', $jar_id);
        $insert_stmt->execute();
        error_log("Created new jar_balance record");
    }

    // Lấy số dư
    $sql = "SELECT balance FROM jar_balances WHERE user_id = :user_id AND jar_id = :jar_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->bindParam(':jar_id', $jar_id);
    $stmt->execute();

    $balance = $stmt->fetchColumn();
    error_log("Retrieved balance: " . ($balance !== false ? $balance : 'null'));
    
    $response['success'] = true;
    $response['balance'] = $balance !== false ? floatval($balance) : 0;
    error_log("Final response: " . print_r($response, true));
} catch (PDOException $e) {
    error_log("Database error: " . $e->getMessage());
    $response['message'] = 'Có lỗi xảy ra: ' . $e->getMessage();
}

header('Content-Type: application/json');
echo json_encode($response);
exit();
?> 