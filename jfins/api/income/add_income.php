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
    if (!isset($data['date']) || !isset($data['amount']) || !isset($data['description'])){
        $response['message'] = 'Vui lòng điền đủ thông tin!';
        error_log("Missing required fields. Data received: " . print_r($data, true));
    } else {
        // Lấy thông tin để dùng sql
        $user_id = $_SESSION['user_id'];
        $income_date = $data['date'];
        $amount = $data['amount'];
        $description = $data['description'];
    
        error_log("Processing income addition - User ID: $user_id, Date: $income_date, Amount: $amount, Description: $description");
    
        // truy vấn -> thêm dữ liệu vào db
        try {
            $sql = "INSERT INTO incomes (user_id, income_date, amount, description) VALUES (:user_id, :income_date, :amount, :description)";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':user_id', $user_id);
            $stmt->bindParam(':income_date', $income_date);
            $stmt->bindParam(':amount', $amount);
            $stmt->bindParam(':description', $description);

            if($stmt->execute()) {
                // Lấy tỷ lệ phân bổ của các hũ
                $allocation_sql = "SELECT ja.jar_id, ja.percentage, j.name 
                                 FROM jar_allocations ja 
                                 JOIN jars j ON ja.jar_id = j.jar_id 
                                 WHERE ja.user_id = :user_id";
                $allocation_stmt = $conn->prepare($allocation_sql);
                $allocation_stmt->bindParam(':user_id', $user_id);
                $allocation_stmt->execute();
                $allocations = $allocation_stmt->fetchAll(PDO::FETCH_ASSOC);

                // Tính toán và cập nhật số dư cho từng hũ
                foreach ($allocations as $allocation) {
                    $jar_amount = ($amount * $allocation['percentage']) / 100;
                    
                    // Kiểm tra xem hũ đã có số dư chưa
                    $check_sql = "SELECT balance_id FROM jar_balances 
                                WHERE user_id = :user_id AND jar_id = :jar_id";
                    $check_stmt = $conn->prepare($check_sql);
                    $check_stmt->bindParam(':user_id', $user_id);
                    $check_stmt->bindParam(':jar_id', $allocation['jar_id']);
                    $check_stmt->execute();
                    
                    if ($check_stmt->rowCount() > 0) {
                        // Cập nhật số dư hiện có
                        $update_sql = "UPDATE jar_balances 
                                     SET balance = balance + :amount 
                                     WHERE user_id = :user_id AND jar_id = :jar_id";
                        $update_stmt = $conn->prepare($update_sql);
                        $update_stmt->bindParam(':amount', $jar_amount);
                        $update_stmt->bindParam(':user_id', $user_id);
                        $update_stmt->bindParam(':jar_id', $allocation['jar_id']);
                        $update_stmt->execute();
                    } else {
                        // Tạo số dư mới cho hũ
                        $insert_sql = "INSERT INTO jar_balances (user_id, jar_id, balance) 
                                     VALUES (:user_id, :jar_id, :amount)";
                        $insert_stmt = $conn->prepare($insert_sql);
                        $insert_stmt->bindParam(':user_id', $user_id);
                        $insert_stmt->bindParam(':jar_id', $allocation['jar_id']);
                        $insert_stmt->bindParam(':amount', $jar_amount);
                        $insert_stmt->execute();
                    }
                }

                $response['success'] = true;
                $response['message'] = 'Thêm thu nhập thành công';
                error_log("Income added successfully");
            } else {
                $response['message'] = 'Có lỗi khi thêm thu nhập';
                error_log("SQL Error: " . print_r($stmt->errorInfo(), true));
            }
        } catch (PDOException $e) {
            $response['message'] = 'Có lỗi xảy ra: '. $e->getMessage();
            error_log("PDO Exception: " . $e->getMessage());
        }
    }
}

header('Content-Type: application/json');
echo json_encode($response);
exit();

?>