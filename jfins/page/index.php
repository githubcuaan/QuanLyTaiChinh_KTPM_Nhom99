<?php
  session_start();
  // // Xóa toàn bộ session để test
  // session_destroy();
  // session_start();
  
  // nếu user_id của session chưa đc set -> chưa đăng nhập -> chuyển hướng đến trang đăng nhập
  if(!isset($_SESSION['user_id']))
  {
    // Add debugging
    error_log("Session user_id not set, redirecting to login page");
    header('Location: auth/auth.php');
    exit();
  }
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Quản lý tài chính</title>

    <link rel="stylesheet" href="../assets/css/style.css" />
    <link rel="stylesheet" href="../assets/css/sumaryStyle.css">
    <!-- chitieu css -->
    <link rel="stylesheet" href="../assets/css/chitieu.css">
    <!--   Settings.css   -->
    <link rel="stylesheet" href="../assets/css/Settings.css">
    <!-- thunhap css -->
    <link rel="stylesheet" href="../assets/css/thunhap.css">
    <!-- model css -->
    <link rel="stylesheet" href="../assets/css/model.css">
    <!-- chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="icon" href="../assets/icon/icons8-money-bag-50.png" />

    <!-- font add -->
    <link href="https://fonts.googleapis.com/css2?family=Varela+Round&display=swap" rel="stylesheet">

  </head>
  <body>
    <!-- header -->
    <header>
      <img
        class="header-icon"
        src="../assets/icon/icons8-money-bag-50.png"
        alt="icon"
        style="width: 40px"
      />
      <p class="header-title">JFINs - Quản lý tài chính</p>
      
      <!-- user-profile -->
      <div class="user-profile">
        <button class="profile-btn">
          <i class="fa-solid fa-user"></i>
          <p class="username"><?php echo htmlspecialchars($_SESSION['username']); ?></p>
        </button>
        <div class="dropdown-menu">
          <button class="dropdown-item" id="profile-btn">
            <i class="fa-solid fa-user-circle"></i>
            Đổi mật khẩu
          </button>
          <button class="dropdown-item" id="logout-btn">
            <i class="fa-solid fa-right-from-bracket"></i>
            Đăng xuất
          </button>
        </div>
      </div>
      
    </header>

    <!-- main -->
    <main>
      <!-- side bar -->
      <nav>
        <ul>
          <li><i class="fa-solid fa-chart-pie"></i> Tổng quan</li>
          <li><i class="fa-solid fa-money-check-dollar"></i> Thu nhập</li>
          <li><i class="fa-solid fa-cart-shopping"></i> Chi tiêu</li>
          <li><i class="fa-solid fa-gear"></i>Cài đặt</li>
        </ul>
      </nav>

      <!-- Section Tổng Quan -->
      <section class="sumary" id="sumary" >
        <div class="sumary-head">
          <h1>Tổng quan tài chính</h1>
          <div class="filter">
            <button class="active">Tháng này</button>
            <button>Quý này</button>
            <button>Năm nay</button>
            <button>Tùy chỉnh</button>
          </div>
        </div>

        <hr>

        <div class="sumary-content">
          <!-- tổng quan -->
          <div class="total container">
            <div class="card content">
              <div class="icon-circle" style="padding: 18px;">
                <i class="fa-solid fa-wallet"></i>
              </div>
              <div class="collum-ctn">
                <p>Tổng số dư</p>
                <h2>0.00</h2>
              </div>
            </div>
            <div class="card content">
              <div class="icon-circle">
                <i class="fa-solid fa-arrow-down"></i>
              </div>
              <div class="collum-ctn">
                <p>Tổng thu nhập</p>
                <h2>0.00</h2>  
              </div>
            </div>
            <div class="card content">
              <div class="icon-circle">
                <i class="fa-solid fa-arrow-up"></i>
              </div>
              <div class="collum-ctn">
                <p>Tổng chi tiêu</p>
                <h2>0.00</h2>
              </div>
            </div>
          </div>

          <!-- biểu đồ -->
          <div class="charts container">
            <div class="chart content" id="pie">
              <canvas id="pieChart"></canvas>  
            </div>
            <div class="chart content" id="bar">
              <canvas id="barChart"></canvas>
            </div>
          </div>

          <!-- 6 hũ tài chính -->
           <div class="jars content container">
            <p class="jar-title">6 Hũ Tài Chính</p>
            <div class="jar">
              <img src="../assets/icon/jars/icons8-home-48.png" alt="">
              <p>Thiết Yếu</p>
              <strong>0.00</strong>
              <span>0%</span>
            </div>
            <div class="jar">
              <img src="../assets/icon/jars/icons8-money-bag-48.png" alt="">
              <p>Tự Do Tài Chính</p>
              <strong>0.00</strong>
              <span>0%</span>
            </div>
            <div class="jar">
              <img src="../assets/icon/jars/icons8-books-50.png" alt="">
              <p>Giáo Dục</p>
              <strong>0.00</strong>
              <span>0%</span>
            </div>
            <div class="jar">
              <img src="../assets/icon/jars/icons8-pub-50.png" alt="">
              <p>Hưởng Thụ</p>
              <strong>0.00</strong>
              <span>0%</span>
            </div>
            <div class="jar">
              <img src="../assets/icon/jars/icons8-gift-50.png" alt="">
              <p>Thiện Tâm</p>
              <strong>0.00</strong>
              <span>0%</span>
            </div>
            <div class="jar">
              <img src="../assets/icon/jars/icons8-saving-money-64.png" style="width: 50px;" alt="">
              <p>Tiết Kiệm</p>
              <strong>0.00</strong>
              <span>0%</span>
            </div>
           </div>
        </div>
      </section>

      <!-- Section Thu Nhập -->
      <section class="thunhap" id="thunhap" style="display: none;">
        <div class="thunhap-container">
          <div class="thunhap-header-box">
            <div class="thunhap-page-header">
              <h1>Quản lý thu nhập</h1>
              <button class="thunhap-add-btn">+ Thêm thu nhập</button>
            </div>
            <hr class="thunhap-divider" />
          </div>

          <!-- Bộ lọc + tìm kiếm -->
          <div class="thunhap-box">
            <div class="thunhap-controls">
              <div class="thunhap-filter-bar">
                <button class="thunhap-btn active">Tháng này</button>
                <button class="thunhap-btn">Quý này</button>
                <button class="thunhap-btn">Năm nay</button>
                <button class="thunhap-btn">Tùy chỉnh</button>
              </div>
              <input type="text" class="thunhap-search-box" placeholder="Tìm kiếm mô tả..." />
            </div>
          </div>

          <!-- Bảng thu nhập -->
          <div class="thunhap-box">
            <table class="thunhap-table">
              <thead>
                <tr>
                  <th>Ngày</th>
                  <th>Mô tả</th>
                  <th>Số tiền</th>
                  <th>Hành động</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>09/04/2025</td>
                  <td>Thưởng</td>
                  <td>2.000.000 đ</td>
                  <td>
                    <button id="income-edit-btn" class="thunhap-action-btn">✏️</button>
                    <button id="income-delete-btn" class="thunhap-action-btn">🗑️</button>
                  </td>
                </tr>
                <tr>
                  <td>08/04/2025</td>
                  <td>Lương tháng 4</td>
                  <td>600.000 đ</td>
                  <td>
                    <button id="income-edit-btn" class="thunhap-action-btn">✏️</button>
                    <button id="income-delete-btn" class="thunhap-action-btn">🗑️</button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </section>
      
      <!-- section chi tieu -->
       <section class="chitieu" id="chitieu" style="display: none;">
        <div class="chitieu-head">
          <h1 class="header-chitieu">Quản lý chi tiêu</h1>
        </div>
        <hr>
        <div class="container-chitieu">
            <div class="top-bar">
                <div class="pagination">
                    <button onclick="prevPage()">&larr;</button>
                    <span>Trang 1/2</span>
                </div>
                <div class="filter-buttons">
                    <button class="active">Tháng này</button>
                    <button>Quý này</button>
                    <button>Năm nay</button>
                    <button>Tùy chỉnh</button>
                </div>
                <div class="search-bar-chitieu">
                    <i class="fa-solid fa-search search-icon"></i>
                    <input type="text" placeholder="Tìm kiếm mô tả..." id="search-input">
                </div>
                <button class="add-button" onclick="openExpenseForm()">+ Thêm chi tiêu</button>
            </div>
            <table class="table-chitieu">
                <thead class="thead-chitieu">
                    <tr>
                        <th>Ngày</th>
                        <th>Hũ</th>
                        <th>Mô tả</th>
                        <th>Số tiền</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>10/04/2025</td>
                <td class="category"><img src="../assets/icon/jars/icons8-books-50.png" class="category-icon"/>Giáo Dục</td>
                <td>Ghi chú</td>
                <td>25.000 đ</td>
                        <td class="actions">
                  <button id="expense-edit-btn" onclick="openExpenseEditForm()">✏️</button>
                  <button id="expense-delete-btn" onclick="deleteRow(this)">🗑️</button>
                        </td>
                    </tr>
                    <tr>
                <td>10/04/2025</td>
                <td class="category"><img src="https://img.icons8.com/color/48/heart-health.png" class="category-icon"/>Thiết Yếu</td>
                        <td>Tiền điện nước</td>
                <td>100.000 đ</td>
                        <td class="actions">
                  <button id="expense-edit-btn" onclick="openExpenseEditForm()">✏️</button>
                  <button id="expense-delete-btn" onclick="deleteRow(this)">🗑️</button>
                        </td>
                    </tr>
                    <tr>
                <td>10/04/2025</td>
                <td class="category"><img src="../assets/icon/jars/icons8-gift-50.png" class="category-icon"/>thiện tâm </td>
                <td>gửi tặng</td>
                <td>25.000 đ</td>
                        <td class="actions">
                  <button id="expense-edit-btn" onclick="openExpenseEditForm()">✏️</button>
                  <button id="expense-delete-btn" onclick="deleteRow(this)">🗑️</button>
                        </td>
                    </tr>
                </tbody>
            </table>
            </div>
            <script>
                function deleteRow(button) {
                if (confirm("Bạn có chắc muốn xóa mục chi tiêu này không?")) {
                const row = button.closest("tr");
            row.remove();
            }
             }
          </script>

      </section>
      <!-- Section cài đặt -->
      <section class="jar-config-section" id="jar-config-section" style="display: none;">
        <div class="config-wrapper">
          <h2 class="config-header">
            <i class="fa-solid fa-gear"></i>
            Cấu hình phân bổ 6 hũ
          </h2>
          <!-- Grid 2 cột -->
          <div class="jar-grid">
            <!-- Cột trái -->
            <div class="jar-column">
              <!-- Hũ 1 -->
              <div class="jar-item">
                <div class="jar-header">
                  
                  <div><img src="../assets/icon/jars/icons8-home-48.png" alt="" class="jar-icon"></div>
                  <span class="jar-name">Thiết Yếu</span>
                </div>
                <div class="jar-control">
                  <input type="number" value="8" min="0" max="100" class="jar-percent">
                  <span class="percent-sign">%</span>
                </div>
              </div>
              <!-- Hũ 2 -->
              <div class="jar-item">
                <div class="jar-header">
                  
                  <div><img src="../assets/icon/jars/icons8-money-bag-48.png" alt="" class="jar-icon"></div>
                  <span class="jar-name">Tự Do Tài Chính</span>
                </div>
                <div class="jar-control">
                  <input type="number" value="10" min="0" max="100" class="jar-percent">
                  <span class="percent-sign">%</span>
                </div>
              </div>

              <!-- Hũ 3 -->
              <div class="jar-item">
                <div class="jar-header">
                  
                  <div><img src="../assets/icon/jars/icons8-books-50.png" alt="" class="jar-icon"></div>
                  <span class="jar-name">Giáo Dục</span>
                </div>
                <div class="jar-control">
                  <input type="number" value="5" min="0" max="100" class="jar-percent">
                  <span class="percent-sign">%</span>
                </div>
              </div>
            </div>
            <!-- Cột phải -->
            <div class="jar-column">
              <!-- Hũ 4 -->
              <div class="jar-item">
                <div class="jar-header">
                  
                  <div><img src="../assets/icon/jars/icons8-pub-50.png" alt="" class="jar-icon"></div>
                  <span class="jar-name"> Hưởng Thụ </span>
                </div>
                <div class="jar-control">
                  <input type="number" value="10" min="0" max="100" class="jar-percent">
                  <span class="percent-sign">%</span>
                </div>
              </div>
              <!-- Hũ 5 -->
              <div class="jar-item">
                <div class="jar-header">
                  
                  <div><img src="../assets/icon/jars/icons8-gift-50.png" alt="" class="jar-icon"></div>
                  <span class="jar-name">Thiện Tâm</span>
                </div>
                <div class="jar-control">
                  <input type="number" value="10" min="0" max="100" class="jar-percent">
                  <span class="percent-sign">%</span>
                </div>
              </div>
              <!-- Hũ 6 -->
              <div class="jar-item">
                <div class="jar-header">
                  
                  <div><img src="../assets/icon/jars/icons8-saving-money-64.png" alt="" class="jar-icon"></div>
                  <span class="jar-name">Tiết Kiệm</span>
                </div>
                <div class="jar-control">
                  <input type="number" value="55" min="0" max="100" class="jar-percent">
                  
                  <span class="percent-sign">%</span>
                </div>
              </div>
            </div>
          </div>
          <!-- Tổng và nút lưu -->
          <div class="config-footer">
            <div class="total-percent">
            </div>
            <button class="save-config-btn">
              <i class="fa-solid fa-circle-check"></i>
              Lưu cấu hình
            </button>
          </div>
        </div>
        <!-- Thêm vào index.html ngay sau phần config-footer -->
      <div class="danger-zone">
        <h3 class="danger-title">
          <i class="fa-solid fa-triangle-exclamation"></i>
          Xóa toàn bộ dữ liệu
        </h3>
        
        <div class="danger-content">
          <p class="warning-text">
            <i class="fa-solid fa-circle-exclamation"></i>
            Cảnh báo: Hành động này sẽ xóa VĨNH VIỄN mọi dữ liệu bao gồm thu nhập, chi tiêu và cấu hình. Không thể hoàn tác!
          </p>

          <div class="delete-control">
            <label class="confirm-check">
              <input type="checkbox" class="danger-checkbox">
              <span>Tôi hiểu rõ hậu quả và muốn tiếp tục</span>
            </label>
            <button class="delete-btn" disabled>
              <i class="fa-solid fa-bomb"></i>
              Xóa tất cả dữ liệu
            </button>
          </div>
        </div>
      </div>
      </section>
    </main>
    <!-- footer -->
    <footer>
      <p>Quản lý chi tiêu 6 hũ 2025 - Phát triển bởi nhóm 99</p>
    </footer>
    
    <!-- Modal for jar details -->
    <div id="jarModal" class="modal">
      <div class="modal-content">
        <span class="close">&times;</span>
        <h2 id="jar-name">Tên Hũ</h2>
        <p><strong>Số dư hiện tại:</strong> <span id="jar-balance">0.00</span> ₫</p>
        <p><strong>Tỷ lệ phân bổ:</strong> <span id="jar-percent">0</span>%</p>
        <p><strong>Mô tả:</strong> <span id="jar-description"></span></p>
        <button class="spend-from-jar">Chi tiêu từ hũ này</button>
      </div>
    </div>
    <!-- modal của chi tiêu -->
    <!-- Form Thêm Khoản Chi Tiêu -->
    <div id="expense-form-container" class="modal">
      <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Thêm khoản chi tiêu</h2>

        <!-- Ngày -->
        <label for="expense-date">Ngày:</label>
        <input type="date" id="expense-date" class="form-control" required>

        <!-- Hũ -->
        <label for="expense-jar-select">Hũ:</label>
        <select id="expense-jar-select" class="form-control" required>
          <option value="jar-thietyeu">🏠 Thiết yếu</option>
          <option value="jar-tudotaichinh">💰 Tự Do Tài Chính</option>
          <option value="jar-giaoduc">📘 Giáo Dục</option>
          <option value="jar-huongthu">🎉 Hưởng Thụ</option>
          <option value="jar-thientam">🎁 Thiện Tâm</option>
          <option value="jar-tietkiem">📋 Tiết Kiệm</option>
        </select>

        <!-- Số dư của hũ (chỉ hiển thị, không cho chỉnh sửa) -->
        <label for="ex pense-jar-balance">Số dư:</label>
        <input type="text" id="expense-jar-balance" class="form-control" disabled>

        <!-- Số tiền chi tiêu -->
        <label for="expense-amount">Số tiền:</label>
        <input type="number" id="expense-amount" class="form-control" placeholder="Nhập số tiền" min="0" required>

        <!-- Mô tả -->
        <label for="expense-description">Mô tả:</label>
        <input type="text" id="expense-description" class="form-control" placeholder="Nhập mô tả khoản chi tiêu">

        <!-- Nút Hủy và Lưu -->
        <div class="form-actions">
          <button type="button" id="cancel-expense" class="btn btn-secondary">Hủy</button>
          <button type="submit" id="save-expense" class="btn btn-primary">Lưu</button>
        </div>
      </div>
    </div>

    <!-- Form Thêm Khoản Thu Nhập -->
    <div id="income-form-container" class="modal">
      <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Thêm khoản thu nhập</h2>

        <!-- Ngày -->
        <label for="income-date">Ngày:</label>
        <input type="date" id="income-date" class="form-control" required>

        <!-- Số tiền -->
        <label for="income-amount">Số tiền:</label>
        <input type="number" id="income-amount" class="form-control" placeholder="Nhập số tiền" min="0" required>

        <!-- Mô tả -->
        <label for="income-description">Mô tả:</label>
        <input type="text" id="income-description" class="form-control" placeholder="Nhập mô tả khoản thu nhập">

        <!-- Nút Hủy và Lưu -->
        <div class="form-actions">
          <button type="button" id="cancel-income" class="btn btn-secondary">Hủy</button>
          <button type="submit" id="save-income" class="btn btn-primary">Lưu</button>
        </div>
      </div>
    </div>

    <!-- Thêm sau các modal khác -->
     <!-- Modal Xác nhận xóa dữ liệu -->
      <div id="deleteConfirmModal" class="modal">
        <div class="modal-content">
          <span class="close">&times;</span>
          <h2>Xác nhận xóa dữ liệu</h2>
          <p>Bạn có chắc chắn muốn xóa tất cả dữ liệu không?<br>Hành động này không thể hoàn tác!</p>
          <label for="confirm-delete-input">Nhập "XÓA" để xác nhận</label>
          <input type="text" id="confirm-delete-input" class="form-control" placeholder="XÓA">
          <div class="form-actions" style="margin-top: 20px;">
            <button id="cancel-delete-btn" class="btn btn-secondary">Hủy</button>
            <button id="confirm-delete-btn" class="btn btn-danger" disabled>Xóa tất cả</button>
          </div>
        </div>
      </div>

    <!-- Form Sửa Khoản Thu Nhập -->
    <div id="income-edit-container" class="modal">
      <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Sửa khoản thu nhập</h2>

        <!-- Ngày -->
        <label for="edit-income-date">Ngày:</label>
        <input type="date" id="edit-income-date" class="form-control" required>

        <!-- Số tiền -->
        <label for="edit-income-amount">Số tiền:</label>
        <input type="number" id="edit-income-amount" class="form-control" placeholder="Nhập số tiền" min="0" required>

        <!-- Mô tả -->
        <label for="edit-income-description">Mô tả:</label>
        <input type="text" id="edit-income-description" class="form-control" placeholder="Nhập mô tả khoản thu nhập">

        <!-- Nút Hủy và Lưu -->
        <div class="form-actions">
          <button type="button" id="cancel-edit-income" class="btn btn-secondary">Hủy</button>
          <button type="submit" id="save-edit-income" class="btn btn-primary">Lưu thay đổi</button>
        </div>
      </div>
    </div>

    <!-- Form Sửa Khoản Chi Tiêu -->
    <div id="expense-edit-container" class="modal">
      <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Sửa khoản chi tiêu</h2>

        <!-- Ngày -->
        <label for="edit-expense-date">Ngày:</label>
        <input type="date" id="edit-expense-date" class="form-control" required>

        <!-- Hũ -->
        <label for="edit-expense-jar-select">Hũ:</label>
        <select id="edit-expense-jar-select" class="form-control" required>
          <option value="jar-thietyeu">🏠 Thiết yếu</option>
          <option value="jar-tudotaichinh">💰 Tự Do Tài Chính</option>
          <option value="jar-giaoduc">📘 Giáo Dục</option>
          <option value="jar-huongthu">🎉 Hưởng Thụ</option>
          <option value="jar-thientam">🎁 Thiện Tâm</option>
          <option value="jar-tietkiem">📋 Tiết Kiệm</option>
        </select>

        <!-- Số tiền -->
        <label for="edit-expense-amount">Số tiền:</label>
        <input type="number" id="edit-expense-amount" class="form-control" placeholder="Nhập số tiền" min="0" required>

        <!-- Mô tả -->
        <label for="edit-expense-description">Mô tả:</label>
        <input type="text" id="edit-expense-description" class="form-control" placeholder="Nhập mô tả khoản chi tiêu">

        <!-- Nút Hủy và Lưu -->
        <div class="form-actions">
          <button type="button" id="cancel-edit-expense" class="btn btn-secondary">Hủy</button>
          <button type="submit" id="save-edit-expense" class="btn btn-primary">Lưu thay đổi</button>
        </div>
      </div>
    </div>

    <!-- Modal Đổi mật khẩu -->
    <div id="profile-modal" class="modal">
      <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Đổi mật khẩu</h2>
        
        <div class="profile-info">
          <div class="profile-details">
            <div class="form-group">
              <label for="current-password">Mật khẩu hiện tại:</label>
              <input type="password" id="current-password" class="form-control" placeholder="Nhập mật khẩu hiện tại">
            </div>

            <div class="form-group">
              <label for="new-password">Mật khẩu mới:</label>
              <input type="password" id="new-password" class="form-control" placeholder="Nhập mật khẩu mới">
            </div>

            <div class="form-group">
              <label for="confirm-password">Xác nhận mật khẩu mới:</label>
              <input type="password" id="confirm-password" class="form-control" placeholder="Nhập lại mật khẩu mới">
            </div>
          </div>
        </div>

        <div class="form-actions">
          <button type="button" id="cancel-profile" class="btn btn-secondary">Hủy</button>
          <button type="submit" id="save-profile" class="btn btn-primary">Lưu thay đổi</button>
        </div>
      </div>
    </div>

    <!-- Modal Xác nhận đăng xuất -->
    <div id="logout-modal" class="modal">
      <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Xác nhận đăng xuất</h2>
        
        <div class="logout-confirm" style="display: flex; align-items: center; gap: 15px;">
          <i class="fa-solid fa-right-from-bracket" style="font-size: 48px; color: #e74c3c;"></i>
          <p style="margin: 0;">Bạn có chắc chắn muốn đăng xuất khỏi hệ thống?</p>
        </div>

        <div class="form-actions">
          <button type="button" id="cancel-logout" class="btn btn-secondary">Hủy</button>
          <button type="button" id="confirm-logout" class="btn btn-danger">Đăng xuất</button>
        </div>
      </div>
    </div>

    <script src="../assets/js/main.js"></script>
    <script src="../assets/js/modal.js"></script>
    <script src="../assets/js/chart.js"></script>
    <script src="../assets/js/expense.js"></script>
    <script src="../assets/js/income.js"></script>
    <script src="../assets/js/profile.js"></script>
  </body>
</html>
