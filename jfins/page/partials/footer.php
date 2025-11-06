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
          <option value="1">🏠 Thiết yếu</option>
          <option value="2">💰 Tự Do Tài Chính</option>
          <option value="3">📘 Giáo Dục</option>
          <option value="4">🎉 Hưởng Thụ</option>
          <option value="5">🎁 Thiện Tâm</option>
          <option value="6">📋 Tiết Kiệm</option>
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
          <option value="1">Thiết yếu</option>
          <option value="2">Tự Do Tài Chính</option>
          <option value="3">Giáo Dục</option>
          <option value="4"> Hưởng Thụ</option>
          <option value="5">Thiện Tâm</option>
          <option value="6">Tiết Kiệm</option>
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
    <script src="../assets/js/jar-config.js"></script>
  </body>
</html>
