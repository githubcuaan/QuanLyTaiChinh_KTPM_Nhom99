<?php 
    // 1. Cấu hình thông tin kết nối
    $servername = "127.0.0.1";
    $username = "root";
    $password = "";
    $dbname = "jfins";

    //2. Tạo kết nối PDO
    $conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8mb4", $username, $password);

    //3. Cấu hình cho kết nối
    // Thiết lập chế độ báo lỗi
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Thiết lập chế độ fetch mặc định
    $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC); //Khi dùng $stmt->fetch(), nó sẽ trả mảng kết quả dạng ['tencot' => giatri] thay vì cả chỉ số số và tên.

    // Thiết lập múi giờ
    date_default_timezone_set("Asia/Ho_Chi_Minh");  
?>