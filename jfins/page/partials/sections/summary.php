<!-- Section Tổng Quan -->
      <section class="sumary" id="sumary" >
        <div class="sumary-head">
          <h1>Tổng quan tài chính</h1>
          <div class="filter">
            <button class="active">Tháng này</button>
            <button>Quý này</button>
            <button>Năm nay</button>
            <button>Tùy chỉnh</button>
            <div class="date-range" style="display: none; margin-top: 10px;">
              <input type="date" id="start-date" class="date-input">
              <span>đến</span>
              <input type="date" id="end-date" class="date-input">
            </div>
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
