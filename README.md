# 💸 App Quản Lý Chi Tiêu 6 Hũ

Ứng dụng quản lý tài chính cá nhân theo **phương pháp 6 hũ tài chính** – một công cụ đơn giản, trực quan giúp bạn kiểm soát thu nhập và chi tiêu một cách hiệu quả, minh bạch và bền vững.

---

## 📚 Giới thiệu

**6 Hũ Tài Chính** là một phương pháp quản lý tiền bạc nổi tiếng do T. Harv Eker giới thiệu. Mỗi khoản thu nhập sẽ được chia thành 6 quỹ (hũ) khác nhau, phục vụ cho những mục tiêu tài chính riêng biệt:

| Hũ | Mục đích | Tỷ lệ gợi ý |
|----|----------|-------------|
| **NEC** | Nhu cầu thiết yếu (ăn, ở, điện nước,...) | 55% |
| **FFA** | Tự do tài chính (đầu tư, tiết kiệm dài hạn) | 10% |
| **EDU** | Giáo dục (học hành, phát triển bản thân) | 10% |
| **LTSS** | Tiết kiệm dài hạn (mua nhà, xe,...) | 10% |
| **PLAY** | Hưởng thụ (du lịch, ăn chơi) | 10% |
| **GIVE** | Cho đi (từ thiện, hỗ trợ người khác) | 5% |

Ứng dụng giúp bạn mô phỏng và áp dụng phương pháp này trong đời sống hàng ngày.

---

## 🚀 Chức năng chính

### 1. 👤 Đăng ký / Đăng nhập
- Tạo tài khoản người dùng, bảo mật qua `localStorage`
- Giao diện đơn giản, dễ sử dụng
- Chuyển hướng tự động nếu chưa đăng nhập

### 2. 🏠 Trang Tổng Quan (Dashboard)
- Hiển thị tổng số dư, thu nhập, chi tiêu của người dùng
- Phân tích số dư hiện tại của từng hũ
- Có bộ lọc theo ngày, tháng, quý, năm

### 3. 💰 Quản lý Thu Nhập
- Thêm khoản thu nhập mới: lương, thưởng, phụ cấp, v.v.
- Tự động phân bổ thu nhập vào 6 hũ theo tỷ lệ
- Hiển thị danh sách thu nhập chi tiết
- Bộ lọc thời gian và tìm kiếm mô tả

### 4. 💸 Quản lý Chi Tiêu
- Ghi lại từng khoản chi theo từng hũ
- Trừ tiền khỏi hũ tương ứng
- Chỉnh sửa hoặc xóa khoản chi
- Tìm kiếm mô tả, lọc theo thời gian

### 5. ⚙️ Cài Đặt
- Tùy chỉnh tỷ lệ phân bổ 6 hũ theo nhu cầu cá nhân
- Đặt lại toàn bộ dữ liệu người dùng
- Đặt lại mật khẩu (tuỳ chọn mở rộng)

---

## 🗂️ Cấu trúc thư mục
expense-tracker-6hu/
├── index.html # Trang tổng quan
├── login.html # Đăng nhập
├── register.html # Đăng ký
├── income.html # Thu nhập
├── expense.html # Chi tiêu
├── settings.html # Cài đặt
├── css/
│ └── style.css # Giao diện chung
├── js/
│ ├── auth.js # Xử lý xác thực
│ ├── storage.js # Tương tác localStorage
│ ├── dashboard.js # Hiển thị dashboard
│ ├── income.js # Logic thu nhập
│ ├── expense.js # Logic chi tiêu
│ ├── settings.js # Logic cài đặt
│ └── utils.js # Hàm tiện ích
└── assets/
└── icons/ # Hình ảnh và biểu tượng

