<?php
session_start();
require_once '../../config/db_connect.php';

$response = array('success' => false, 'message' => '', 'data' => array());

if (!isset($_SESSION['user_id'])) {
    $response['message'] = 'Vui lòng đăng nhập';
    echo json_encode($response);
    exit();
}

try {
    $user_id = $_SESSION['user_id'];
    $sql = "SELECT e.*, j.name as jar_name 
            FROM expenses e 
            JOIN jars j ON e.jar_id = j.jar_id 
            WHERE e.user_id = :user_id 
            ORDER BY e.expense_date DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();

    $expenses = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $response['success'] = true;
    $response['data'] = $expenses;
} catch (PDOException $e) {
    $response['message'] = 'Có lỗi xảy ra: ' . $e->getMessage();
}

header('Content-Type: application/json');
echo json_encode($response);
exit();
?> 