# 💸 App Quản Lý Chi Tiêu 6 Hũ

Ứng dụng quản lý tài chính cá nhân theo **phương pháp 6 hũ tài chính** – một công cụ đơn giản, trực quan giúp bạn kiểm soát thu nhập và chi tiêu một cách hiệu quả, minh bạch và bền vững.

---
## 👥 Thành viên nhóm

Dự án được thực hiện bởi các thành viên:

- 🐶 Phạm Lê Đình An
- 🧑 Hà Lê Quang Minh
- 🧑 Nguyễn Duy Anh Tuấn
- 🧑 Nguyễn Đức Tuấn

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
<pre> expense-tracker-6hu/ ├── index.html ← Trang tổng quan ├── login.html ← Trang đăng nhập ├── register.html ← Trang đăng ký ├── income.html ← Quản lý thu nhập ├── expense.html ← Quản lý chi tiêu ├── settings.html ← Trang cài đặt ├── css/ │ └── style.css ← Giao diện chung ├── js/ │ ├── auth.js ← Xử lý đăng nhập/đăng ký │ ├── storage.js ← Hàm tương tác localStorage │ ├── dashboard.js ← Hiển thị tổng quan │ ├── income.js ← Xử lý thu nhập │ ├── expense.js ← Xử lý chi tiêu │ ├── settings.js ← Xử lý tỷ lệ 6 hũ và reset │ └── utils.js ← Hàm tiện ích chung ├── assets/ │ └── icons/ ← Icon UI (nếu có) │ ├── icons8-books-50.png │ ├── icons8-gift-50.png │ └── ... (biểu tượng khác) └── README.md ← Tài liệu hướng dẫn dự án </pre>
## 📬 Liên hệ

Nếu bạn có bất kỳ câu hỏi, góp ý hoặc muốn hợp tác phát triển dự án, vui lòng liên hệ qua:

- 📧 Email: nhom99@gmail.com
- 🌐 Website: https://nhom99.com
- 🐙 GitHub: https://github.com/githubcuaan/QuanLyTaiChinh_KTPM_Nhom99

## 📄 License

MIT License © 2025

> “Hãy để mỗi đồng tiền bạn chi ra phản ánh đúng giá trị của nó.”

