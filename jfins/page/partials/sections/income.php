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
              <input type="text" id="income-search" class="thunhap-search-box" placeholder="Tìm kiếm mô tả..." />
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
