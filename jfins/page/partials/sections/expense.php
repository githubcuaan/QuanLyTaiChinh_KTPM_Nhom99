<!-- section chi tieu -->
       <section class="chitieu" id="chitieu" style="display: none;">
        <div class="chitieu-head">
          <h1 class="header-chitieu">Quản lý chi tiêu</h1>
        </div>
        <hr>
        <div class="container-chitieu">
            <div class="top-bar">
                <div class="filter-buttons">
                    <button class="active">Tháng này</button>
                    <button>Quý này</button>
                    <button>Năm nay</button>
                    <button>Tùy chỉnh</button>
                </div>
                <div class="search-bar-chitieu">
                    <i class="fa-solid fa-search search-icon"></i>
                    <input type="text" id="expense-search" placeholder="Tìm kiếm mô tả...">
                </div>
                <button class="add-button" >+ Thêm chi tiêu</button>
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
                    <!-- Dữ liệu sẽ được load từ JavaScript -->
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
