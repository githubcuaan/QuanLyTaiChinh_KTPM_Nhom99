<?php
session_start();
require_once '../config/db_connect.php';

// Kiểm tra đăng nhập
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit();
}

$user_id = $_SESSION['user_id'];

// Lấy tham số ngày từ request
$start_date = isset($_GET['start_date']) ? $_GET['start_date'] : date('Y-m-01');
$end_date = isset($_GET['end_date']) ? $_GET['end_date'] : date('Y-m-t');

try {
    // Lấy tổng thu nhập
    $income_query = "SELECT COALESCE(SUM(amount), 0) as total_income 
                    FROM incomes 
                    WHERE user_id = :user_id 
                    AND income_date BETWEEN :start_date AND :end_date";
    $income_stmt = $conn->prepare($income_query);
    $income_stmt->execute([
        'user_id' => $user_id,
        'start_date' => $start_date,
        'end_date' => $end_date
    ]);
    $total_income = $income_stmt->fetch()['total_income'];

    // Lấy tổng chi tiêu
    $expense_query = "SELECT COALESCE(SUM(amount), 0) as total_expense 
                     FROM expenses 
                     WHERE user_id = :user_id 
                     AND expense_date BETWEEN :start_date AND :end_date";
    $expense_stmt = $conn->prepare($expense_query);
    $expense_stmt->execute([
        'user_id' => $user_id,
        'start_date' => $start_date,
        'end_date' => $end_date
    ]);
    $total_expense = $expense_stmt->fetch()['total_expense'];

    // Lấy số dư các hũ
    $jar_balance_query = "SELECT j.name, jb.balance, ja.percentage
                         FROM jars j
                         LEFT JOIN jar_balances jb ON j.jar_id = jb.jar_id AND jb.user_id = :user_id
                         LEFT JOIN jar_allocations ja ON j.jar_id = ja.jar_id AND ja.user_id = :user_id
                         ORDER BY j.jar_id";
    $jar_balance_stmt = $conn->prepare($jar_balance_query);
    $jar_balance_stmt->execute(['user_id' => $user_id]);
    $jar_balances = $jar_balance_stmt->fetchAll();

    // Lấy dữ liệu chi tiêu theo hũ
    $expense_by_jar_query = "SELECT j.name, COALESCE(SUM(e.amount), 0) as total
                            FROM jars j
                            LEFT JOIN expenses e ON j.jar_id = e.jar_id 
                            AND e.user_id = :user_id
                            AND e.expense_date BETWEEN :start_date AND :end_date
                            GROUP BY j.jar_id
                            ORDER BY j.jar_id";
    $expense_by_jar_stmt = $conn->prepare($expense_by_jar_query);
    $expense_by_jar_stmt->execute([
        'user_id' => $user_id,
        'start_date' => $start_date,
        'end_date' => $end_date
    ]);
    $expense_by_jar = $expense_by_jar_stmt->fetchAll();

    // Tính tổng số dư
    $total_balance = 0;
    foreach ($jar_balances as $jar) {
        $total_balance += $jar['balance'] ?? 0;
    }

    // Chuẩn bị dữ liệu trả về
    $response = [
        'total_balance' => $total_balance,
        'total_income' => $total_income,
        'total_expense' => $total_expense,
        'jar_balances' => $jar_balances,
        'expense_by_jar' => $expense_by_jar,
        'date_range' => [
            'start_date' => $start_date,
            'end_date' => $end_date
        ]
    ];

    header('Content-Type: application/json');
    echo json_encode($response);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?> 