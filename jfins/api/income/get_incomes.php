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
    $sql = "SELECT * FROM incomes WHERE user_id = :user_id ORDER BY income_date DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();

    $incomes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $response['success'] = true;
    $response['data'] = $incomes;
} catch (PDOException $e) {
    $response['message'] = 'Có lỗi xảy ra: ' . $e->getMessage();
}

header('Content-Type: application/json');
echo json_encode($response);
exit();
?> 