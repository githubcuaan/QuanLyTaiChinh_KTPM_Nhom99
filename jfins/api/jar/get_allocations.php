<?php
session_start();
require_once '../../config/db_connect.php';

header('Content-Type: application/json');
$response = array('success' => false, 'message' => '', 'allocations' => array());

if (!isset($_SESSION['user_id'])) {
    $response['message'] = 'Vui lòng đăng nhập';
    echo json_encode($response);
    exit();
}

try {
    $user_id = $_SESSION['user_id'];
    
    // Get all jar allocations for the user
    $sql = "SELECT ja.jar_id, ja.percentage 
            FROM jar_allocations ja 
            WHERE ja.user_id = :user_id 
            ORDER BY ja.jar_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();
    
    $allocations = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $response['success'] = true;
    $response['allocations'] = $allocations;
    
} catch (Exception $e) {
    $response['message'] = 'Lỗi: ' . $e->getMessage();
}

echo json_encode($response);
?> 