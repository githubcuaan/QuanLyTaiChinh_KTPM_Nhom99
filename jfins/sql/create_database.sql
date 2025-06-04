-- user table
CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    username VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- jar table
CREATE TABLE jars (
    jar_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL UNIQUE,
    description TEXT
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- jar allocation: tỷ lệ phân bố hũ 
CREATE TABLE jar_allocations (
    allocation_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    jar_id INT NOT NULL,
    percentage DECIMAL(5,2) CHECK (percentage >= 0 AND percentage <= 100),
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE (user_id, jar_id),
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (jar_id) REFERENCES jars(jar_id)
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- income: thu nhap 
CREATE TABLE incomes (
    income_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    amount DECIMAL(15,2) CHECK (amount >= 0),
    description TEXT,
    income_date DATE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- expense: chi tieu
CREATE TABLE expenses (
    expense_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    jar_id INT NOT NULL,
    amount DECIMAL(15,2) CHECK (amount >= 0),
    description TEXT,
    expense_date DATE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (jar_id) REFERENCES jars(jar_id)
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- jar balance: số dư của hũ 
CREATE TABLE jar_balances (
    balance_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    jar_id INT NOT NULL,
    balance DECIMAL(15,2) CHECK (balance >= 0),
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE (user_id, jar_id),
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (jar_id) REFERENCES jars(jar_id)
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Chèn dữ liệu mặc định cho bảng jars
INSERT INTO jars (name, description) VALUES
('Thiết Yếu', 'Chi phí sinh hoạt thiết yếu như ăn uống, nhà ở'),
('Tự Do Tài Chính', 'Đầu tư để đạt tự do tài chính'),
('Giáo Dục', 'Chi phí học tập, phát triển bản thân'),
('Hưởng Thụ', 'Chi tiêu cho giải trí, sở thích'),
('Thiện Tâm', 'Chi tiêu cho từ thiện, giúp đỡ người khác'),
('Tiết Kiệm', 'Tiết kiệm dài hạn cho tương lai');
