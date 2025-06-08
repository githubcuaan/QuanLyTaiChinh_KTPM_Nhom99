# 💸 App Quản Lý Chi Tiêu 6 Hũ - JFINs

Ứng dụng web quản lý tài chính cá nhân theo **phương pháp 6 hũ tài chính** – một công cụ đơn giản, trực quan giúp bạn kiểm soát thu nhập và chi tiêu một cách hiệu quả, minh bạch và bền vững. Dự án được xây dựng với kiến trúc Client-Server, sử dụng PHP & MySQL cho backend và JavaScript cho frontend.

---
## 👥 Thành viên nhóm

Dự án được thực hiện bởi các thành viên:

- Phạm Lê Đình An
- Hà Lê Quang Minh
- Nguyễn Duy Anh Tuấn
- Nguyễn Đức Tuấn

## 📚 Giới thiệu: Phương pháp 6 Hũ

**6 Hũ Tài Chính** là một phương pháp quản lý tiền bạc nổi tiếng do T. Harv Eker giới thiệu. Nguyên tắc của nó là chia mọi khoản thu nhập thành 6 quỹ (hũ) khác nhau, phục vụ cho những mục tiêu tài chính riêng biệt:

| Hũ | Tên Tiếng Anh | Mục đích | Tỷ lệ gợi ý |
| :--- | :--- | :--- | :--- |
| **NEC** | Necessities | Nhu cầu thiết yếu (ăn, ở, đi lại...) | 55% |
| **FFA** | Financial Freedom Account | Tự do tài chính (đầu tư, tạo thu nhập thụ động) | 10% |
| **EDU** | Education | Giáo dục (học hành, phát triển bản thân) | 10% |
| **LTSS** | Long-term Saving for Spending | Tiết kiệm dài hạn (mua nhà, xe, du lịch...) | 10% |
| **PLAY**| Play | Hưởng thụ (giải trí, mua sắm cho bản thân) | 10% |
| **GIVE**| Give | Cho đi (từ thiện, giúp đỡ người khác) | 5% |

Ứng dụng JFINs giúp bạn tự động hóa và áp dụng phương pháp này một cách dễ dàng.

---

## 🚀 Chức năng chính

* **👤 Quản lý Người dùng:**
    * Đăng ký tài khoản mới.
    * Đăng nhập và đăng xuất an toàn qua cơ chế Session của PHP.
    * Thay đổi mật khẩu.

* **🏠 Bảng điều khiển (Dashboard):**
    * Hiển thị tổng quan số dư, tổng thu nhập và tổng chi tiêu.
    * Biểu đồ tròn trực quan hóa tỷ lệ và số tiền trong mỗi hũ.
    * Biểu đồ cột so sánh thu nhập và chi tiêu.

* **💰 Quản lý Thu nhập:**
    * Thêm, sửa, xóa các khoản thu nhập.
    * **Tự động phân bổ thu nhập:** Khi thêm một khoản thu nhập mới, hệ thống sẽ tự động chia số tiền đó vào 6 hũ dựa trên tỷ lệ bạn đã cài đặt.

* **💸 Quản lý Chi tiêu:**
    * Ghi lại từng khoản chi tiêu từ một hũ cụ thể.
    * **Kiểm tra số dư:** Hệ thống không cho phép chi tiêu nếu số tiền trong hũ không đủ.
    * Cập nhật số dư của hũ ngay sau khi chi tiêu.

* **⚙️ Cài đặt:**
    * Tùy chỉnh tỷ lệ phân bổ (%) cho 6 hũ theo nhu cầu cá nhân.
    * Cung cấp "vùng nguy hiểm" để xóa toàn bộ dữ liệu tài chính của tài khoản.

---

## 🛠️ Công nghệ sử dụng

Dự án được xây dựng trên kiến trúc Client-Server vững chắc:

* **Backend:**
    * **Ngôn ngữ:** PHP
    * **Cơ sở dữ liệu:** MySQL
    * **Mô tả:** Xử lý toàn bộ logic nghiệp vụ, quản lý phiên làm việc, tương tác với cơ sở dữ liệu thông qua PDO để tăng cường bảo mật.

* **Frontend:**
    * **Ngôn ngữ:** HTML, CSS, JavaScript (ES6+)
    * **Thư viện:** Chart.js để vẽ các biểu đồ tài chính.
    * **Tương tác:** Sử dụng Fetch API (AJAX) để giao tiếp với Backend, mang lại trải nghiệm mượt mà không cần tải lại trang.

---

## 🗂️ Cấu trúc thư mục

Cấu trúc thư mục được tổ chức một cách rõ ràng và khoa học:
```json
QuanLyTaiChinh_KTPM_Nhom99-main/
└── jfins/
├── api/                <- Chứa các file PHP xử lý logic (backend)
│   ├── auth/           # Xác thực người dùng
│   ├── expense/        # Xử lý chi tiêu
│   ├── income/         # Xử lý thu nhập
│   └── jar/            # Xử lý liên quan đến các hũ
├── assets/             <- Chứa tài nguyên tĩnh
│   ├── css/            # Các file CSS
│   ├── js/             # Các file JavaScript
│   └── icon/           # Các icon
├── config/             <- Chứa file kết nối CSDL
│   └── db_connect.php
├── page/               <- Chứa các trang chính của ứng dụng
│   ├── auth/
│   │   └── auth.php    <- Trang đăng nhập/đăng ký
│   └── index.php       <- Trang tổng quan chính
└── sql/
└── create_database.sql <- File mã lệnh để tạo CSDL
```
---
## ⚙️ Hướng dẫn cài đặt và chạy dự án (Local - XAMPP)

Để chạy dự án trên máy tính của bạn, hãy làm theo các bước sau:

1.  **Cài đặt XAMPP:**
    * Tải và cài đặt phiên bản XAMPP mới nhất có chứa PHP và MySQL.

2.  **Sao chép dự án:**
    * Sao chép thư mục `QuanLyTaiChinh_KTPM_Nhom99-main` vào thư mục `htdocs` của XAMPP (Thường là `C:\xampp\htdocs`).

3.  **Khởi động XAMPP:**
    * Mở XAMPP Control Panel và nhấn **Start** cho 2 module: **Apache** và **MySQL**.

4.  **Tạo Cơ sở dữ liệu:**
    * Mở trình duyệt và truy cập `http://localhost/phpmyadmin/`.
    * Tạo một database mới với tên là `jfins` và mã hóa (collation) là `utf8mb4_unicode_ci`.

5.  **Import Dữ liệu:**
    * Trong phpMyAdmin, chọn database `jfins` vừa tạo.
    * Vào tab `Import`, chọn file `create_database.sql` từ thư mục `jfins/sql/` của dự án.
    * Nhấn **Go** để thực thi. Quá trình này sẽ tạo ra các bảng và chèn dữ liệu ban đầu cho các hũ.

6.  **Chạy ứng dụng:**
    * Mở trình duyệt và truy cập vào địa chỉ:
        `http://localhost/QuanLyTaiChinh_KTPM_Nhom99-main/jfins/page/auth/auth.php`
    * Bây giờ bạn có thể đăng ký tài khoản và bắt đầu sử dụng ứng dụng!

---

## 📬 Liên hệ

Nếu bạn có bất kỳ câu hỏi, góp ý hoặc muốn hợp tác phát triển dự án, vui lòng liên hệ qua:

- 📧 Email: nhom99@gmail.com

---

## 📄 License

MIT License © 2025

> “Hãy để mỗi đồng tiền bạn chi ra phản ánh đúng giá trị của nó.”
