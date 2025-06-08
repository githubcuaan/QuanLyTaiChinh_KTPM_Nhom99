// 1. Các hàm để hiển thị modalal
// Wait for DOM to be fully loaded
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded, initializing income modal functionality...');

    // Get modal elements
    const incomeModal = document.getElementById('income-form-container');
    const incomeEditModal = document.getElementById('income-edit-container');
    const closeBtns = document.querySelectorAll('.close');
    const cancelIncomeBtn = document.getElementById('cancel-income');
    const saveIncomeBtn = document.getElementById('save-income');
    const addIncomeBtn = document.querySelector('.thunhap-add-btn');
    const cancelEditIncomeBtn = document.getElementById('cancel-edit-income');
    const saveEditIncomeBtn = document.getElementById('save-edit-income');
    window.incomeTable = document.querySelector('.thunhap-table tbody');

    // Get form elements
    const incomeDate = document.getElementById('income-date');
    const incomeAmount = document.getElementById('income-amount');
    const incomeDescription = document.getElementById('income-description');
    const editIncomeDate = document.getElementById('edit-income-date');
    const editIncomeAmount = document.getElementById('edit-income-amount');
    const editIncomeDescription = document.getElementById('edit-income-description');

    // Set today's date as default
    incomeDate.valueAsDate = new Date();

    // 1. CÁC HÀM ĐỂ HIỂN THỊ MODAL
    // Function to show modal
    function showModal(modal) {
        modal.style.display = 'block';
        // Force a reflow
        modal.offsetHeight;
        modal.classList.add('show');
    }

    // Function to hide modal
    function hideModal(modal) {
        modal.classList.remove('show');
        setTimeout(() => {
            modal.style.display = 'none';
        }, 300); // Match this with the animation duration
    }

    // Show modal when clicking add income button
    addIncomeBtn.addEventListener('click', () => {
        // Reset form
        incomeDate.valueAsDate = new Date();
        incomeAmount.value = '';
        incomeDescription.value = '';
        
        // Show modal
        showModal(incomeModal);
    });

    // Close modals when clicking the X button
    closeBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            hideModal(incomeModal);
            hideModal(incomeEditModal);
        });
    });

    // Close modals when clicking outside
    window.addEventListener('click', (event) => {
        if (event.target === incomeModal) {
            hideModal(incomeModal);
        }
        if (event.target === incomeEditModal) {
            hideModal(incomeEditModal);
        }
    });

    // Handle cancel buttons
    cancelIncomeBtn.addEventListener('click', () => {
        hideModal(incomeModal);
    });

    cancelEditIncomeBtn.addEventListener('click', () => {
        hideModal(incomeEditModal);
    });

    // 2. HÀM THÊM THU NHẬP
    // Handle save button for new income
    saveIncomeBtn.addEventListener('click', async () => {
        const date = document.getElementById('income-date').value;
        const amount = document.getElementById('income-amount').value;
        const description = document.getElementById('income-description').value;

        // kiểm tra input
        if(!date || !amount || !description) {
            alert('Vui lòng nhập đủ thông tin');
            return;
        }

        try {
            const response = await fetch('/QuanLyTaiChinh_KTPM_Nhom99/jfins/api/income/add_income.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    date,
                    amount,
                    description
                })
            });

            const data = await response.json();

            if(data.success) {
                alert(data.message);
                hideModal(incomeModal);
                //reset form
                document.getElementById('income-date').value = '';
                document.getElementById('income-amount').value = '';
                document.getElementById('income-description').value = '';
                // load lại danh sách thu nhập
                loadIncomes();
            } else {
                alert('data.message');
            }
        } catch (error) {
            console.error('Error: ',error);
            alert('Có lỗi xảy ra, xin vui lòng thử lại');
        }
    });

    // Handle save button for editing income
    saveEditIncomeBtn.addEventListener('click', async () => {
        // Validate form
        if (!editIncomeDate.value || !editIncomeAmount.value || !editIncomeDescription.value) {
            alert('Vui lòng điền đầy đủ thông tin!');
            return;
        }

        try {
            const currentRow = document.querySelector('.thunhap-table tbody tr.editing');
            if (!currentRow) {
                alert('Không tìm thấy dữ liệu cần sửa!');
                return;
            }

            const incomeId = currentRow.getAttribute('data-income-id');
            if (!incomeId) {
                alert('Có lỗi xảy ra: Không tìm thấy ID của thu nhập');
                return;
            }

            console.log('Sending update request with income_id:', incomeId);
            const response = await fetch('/QuanLyTaiChinh_KTPM_Nhom99/jfins/api/income/edit_income.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    income_id: incomeId,
                    date: editIncomeDate.value,
                    amount: editIncomeAmount.value,
                    description: editIncomeDescription.value
                })
            });

            const data = await response.json();
            console.log('Update response:', data);

            if (data.success) {
                // Format the date
                const date = new Date(editIncomeDate.value);
                const formattedDate = date.toLocaleDateString('vi-VN');

                // Format the amount
                const amount = parseFloat(editIncomeAmount.value).toLocaleString('vi-VN') + ' đ';

                // Update the row
                currentRow.innerHTML = `
                    <td>${formattedDate}</td>
                    <td>${editIncomeDescription.value}</td>
                    <td>${amount}</td>
                    <td>
                        <button class="thunhap-action-btn" onclick="editIncomeRow(this)">✏️</button>
                        <button class="thunhap-action-btn" onclick="deleteIncomeRow(this)">🗑️</button>
                    </td>
                `;
                currentRow.classList.remove('editing');

                // Hide modal
                hideModal(incomeEditModal);
                alert('Cập nhật thu nhập thành công');
            } else {
                alert(data.message || 'Có lỗi xảy ra khi cập nhật thu nhập');
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Có lỗi xảy ra, xin vui lòng thử lại');
        }
    });

    // Add click event listeners to existing edit and delete buttons
    document.querySelectorAll('#income-edit-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            editIncomeRow(this);
        });
    });

    document.querySelectorAll('#income-delete-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            deleteIncomeRow(this);
        });
    });
});

// 2. các hàm liên quan đén CRUD
// Hàm để sửa thông tin ở giao diện bảngbảng
async function editIncomeRow(button) {
    const row = button.closest('tr');
    const cells = row.cells;
    const incomeId = row.getAttribute('data-income-id');
    
    if (!incomeId) {
        console.error('No income_id found on row');
        alert('Có lỗi xảy ra: Không tìm thấy ID của thu nhập');
        return;
    }

    // Get current values
    const dateText = cells[0].textContent;
    const description = cells[1].textContent;
    const amountText = cells[2].textContent;

    // Set values in edit form
    const editDate = document.getElementById('edit-income-date');
    const editAmount = document.getElementById('edit-income-amount');
    const editDescription = document.getElementById('edit-income-description');

    // Convert date from dd/mm/yyyy to yyyy-mm-dd
    const [day, month, year] = dateText.split('/');
    editDate.value = `${year}-${month.padStart(2, '0')}-${day.padStart(2, '0')}`;
    
    // Convert amount from "1.000.000 đ" to "1000000"
    const amount = amountText.replace(/[^\d]/g, '');
    editAmount.value = amount;
    
    editDescription.value = description;

    // Store the row for later use
    row.classList.add('editing');

    // Show edit modal
    const editModal = document.getElementById('income-edit-container');
    editModal.style.display = 'block';
    editModal.offsetHeight;
    editModal.classList.add('show');
}

// Function to delete income row
async function deleteIncomeRow(button) {
    if (confirm("Bạn có chắc muốn xóa mục thu nhập này không?")) {
        const row = button.closest('tr');
        const incomeId = row.getAttribute('data-income-id');
        
        if (!incomeId) {
            console.error('No income_id found on row');
            alert('Có lỗi xảy ra: Không tìm thấy ID của thu nhập');
            return;
        }

        try {
            const response = await fetch('/QuanLyTaiChinh_KTPM_Nhom99/jfins/api/income/delete_income.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    income_id: incomeId
                })
            });

            const data = await response.json();

            if (data.success) {
                row.remove();
                alert('Xóa thu nhập thành công');
            } else {
                alert(data.message || 'Có lỗi xảy ra khi xóa thu nhập');
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Có lỗi xảy ra, xin vui lòng thử lại');
        }
    }
}

// 3. HÀM GET INCOME
// Hàm load danh sách thu nhập
async function loadIncomes() {
    try {
        const response = await fetch('/QuanLyTaiChinh_KTPM_Nhom99/jfins/api/income/get_incomes.php');
        const data = await response.json();

        if(data.success) {
            // Xóa nội dung cũ
            incomeTable.innerHTML = '';
            
            // Thêm data mới
            data.data.forEach(income => {
                const row = document.createElement('tr');
                row.setAttribute('data-income-id', income.income_id);
                row.innerHTML = `
                    <td>${formatDate(income.income_date)}</td>
                    <td>${income.description}</td>
                    <td>${formatMoney(income.amount)}</td>
                    <td>
                        <button class="thunhap-action-btn" onclick="editIncomeRow(this)">✏️</button>
                        <button class="thunhap-action-btn" onclick="deleteIncomeRow(this)">🗑️</button>
                    </td>    
                `;
                incomeTable.appendChild(row);
            });
        } else {
            console.error('Error loading income: ', data.message);
        }
    } catch (error) {
        console.error('Error loading income: ', error);
    }
}

// Hàm format ngày tháng
function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString('vi-VN');
}

// Hàm format tiền
function formatMoney(amount) {
    return new Intl.NumberFormat('vi-VN', {
        style: 'currency',
        currency: 'VND'
    }).format(amount);
}

// Load danh sách thu nhập khi trang được tải
document.addEventListener('DOMContentLoaded', loadIncomes); 