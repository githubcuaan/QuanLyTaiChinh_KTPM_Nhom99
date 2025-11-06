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
