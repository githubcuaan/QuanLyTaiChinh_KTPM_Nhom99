//1. lấy các phần tử DOM 
const addExpenseBtn = document.querySelector('.add-button'); 
const expenseModal = document.getElementById('expense-form-container');
const expenseEditModal = document.getElementById('expense-edit-container');
const closeBtns = document.querySelectorAll('.close');
const cancelBtn = document.getElementById('cancel-expense');
const cancelEditBtn = document.getElementById('cancel-edit-expense');
const saveEditBtn = document.getElementById('save-edit-expense');

//2. Function để mở model thêm chi tiêu
function openExpenseModal() {
    // Reset form fields
    document.getElementById('expense-date').valueAsDate = new Date(); // lấy date là ngày hnay
    document.getElementById('expense-jar-select').value = 'jar-thietyeu'; // chọn mặc định là thiết yếu
    document.getElementById('expense-amount').value = ''; // amount thêm vào để trống
    document.getElementById('expense-description').value = ''; // description để trống
    
    // Show modal
    expenseModal.style.display = 'block';
    setTimeout(() => {
        expenseModal.classList.add('show');
    }, 10);
}

//3. Function để đóng modal
function closeExpenseModal() {
    expenseModal.classList.remove('show');
    setTimeout(() => {
        expenseModal.style.display = 'none';
    }, 300);
}

function closeExpenseEditModal() {
    expenseEditModal.classList.remove('show');
    setTimeout(() => {
        expenseEditModal.style.display = 'none';
    }, 300);
}

//4. sử dụng các hàm bằng event listeners
// Event listeners
addExpenseBtn.addEventListener('click', openExpenseModal);

// Close modals when clicking the X button
closeBtns.forEach(btn => {
    btn.addEventListener('click', () => {
        closeExpenseModal();
        closeExpenseEditModal();
    });
});

// Handle cancel buttons
cancelBtn.addEventListener('click', closeExpenseModal);
cancelEditBtn.addEventListener('click', closeExpenseEditModal);

// Close modal when clicking outside
window.addEventListener('click', (event) => {
    if (event.target === expenseModal) {
        closeExpenseModal();
    }
    if (event.target === expenseEditModal) {
        closeExpenseEditModal();
    }
});

// Hàm mở form sửa chi tiêu
function openExpenseEditForm() {
    expenseEditModal.style.display = 'block';
    setTimeout(() => {
        expenseEditModal.classList.add('show');
    }, 10);
}

// Hàm lưu chi tiêu
function saveExpense() {
    const date = document.getElementById('expense-date').value;
    const jar = document.getElementById('expense-jar-select').value;
    const amount = document.getElementById('expense-amount').value;
    const description = document.getElementById('expense-description').value;

    // Validate form
    if (!date || !jar || !amount || !description) {
        alert('Vui lòng điền đầy đủ thông tin!');
        return;
    }

    // Format the date
    const formattedDate = new Date(date).toLocaleDateString('vi-VN');

    // Format the amount
    const formattedAmount = parseFloat(amount).toLocaleString('vi-VN') + ' đ';

    // Get jar name from value
    const jarName = document.querySelector(`#expense-jar-select option[value="${jar}"]`).textContent;

    // Create new row in the table
    const table = document.querySelector('.table-chitieu tbody');
    const newRow = document.createElement('tr');
    newRow.innerHTML = `
        <td>${formattedDate}</td>
        <td class="category">${jarName}</td>
        <td>${description}</td>
        <td>${formattedAmount}</td>
        <td class="actions">
            <button id="expense-edit-btn" onclick="editExpenseRow(this)">✏️</button>
            <button id="expense-delete-btn" onclick="deleteExpenseRow(this)">🗑️</button>
        </td>
    `;
    table.insertBefore(newRow, table.firstChild);

    // Hide modal
    closeExpenseModal();
}

// Hàm lưu chi tiêu đã sửa
async function saveEditExpense() {
    const date = document.getElementById('edit-expense-date').value;
    const jar = document.getElementById('edit-expense-jar-select').value;
    const amount = document.getElementById('edit-expense-amount').value;
    const description = document.getElementById('edit-expense-description').value;

    // Validate form
    if (!date || !jar || !amount || !description) {
        alert('Vui lòng điền đầy đủ thông tin!');
        return;
    }

    // Lấy expense_id từ hàng đang được sửa
    const editingRow = document.querySelector('.table-chitieu tbody tr.editing');
    if (!editingRow) {
        alert('Không tìm thấy chi tiêu cần sửa!');
        return;
    }
    const expenseId = editingRow.getAttribute('data-expense-id');

    try {
        const response = await fetch('/QuanLyTaiChinh_KTPM_Nhom99/jfins/api/expense/update_expense.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                expense_id: expenseId,
                date: date,
                amount: amount,
                description: description,
                jar_id: jar
            })
        });

        const data = await response.json();

        if (data.success) {
            alert(data.message);
            // Đóng modal
            closeExpenseEditModal();
            // Load lại danh sách chi tiêu
            loadExpenses();
            // // Load lại số dư các hũ
            // loadJarBalances();
        } else {
            alert(data.message || 'Có lỗi xảy ra khi sửa chi tiêu');
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Có lỗi xảy ra, xin vui lòng thử lại');
    }
}

// Hàm sửa chi tiêu
function editExpenseRow(button) {
    const row = button.closest('tr');
    const cells = row.cells;
    const expenseId = row.getAttribute('data-expense-id');
    
    if (!expenseId) {
        console.error('No expense_id found on row');
        alert('Có lỗi xảy ra: Không tìm thấy ID của chi tiêu');
        return;
    }

    // Get current values
    const dateText = cells[0].textContent;
    const jarText = cells[1].textContent.trim();
    const description = cells[2].textContent;
    const amountText = cells[3].textContent;

    // Set values in edit form
    const editDate = document.getElementById('edit-expense-date');
    const editJar = document.getElementById('edit-expense-jar-select');
    const editAmount = document.getElementById('edit-expense-amount');
    const editDescription = document.getElementById('edit-expense-description');

    // Convert date from dd/mm/yyyy to yyyy-mm-dd
    const [day, month, year] = dateText.split('/');
    editDate.value = `${year}-${month.padStart(2, '0')}-${day.padStart(2, '0')}`;
    
    // Set jar value based on text
    const jarValue = getJarValue(jarText);
    editJar.value = jarValue;
    
    // Convert amount from "1.000.000 đ" to "1000000"
    const amount = amountText.replace(/[^\d]/g, '');
    editAmount.value = amount;
    
    editDescription.value = description;

    // Store the expense_id for later use
    row.classList.add('editing');
    row.setAttribute('data-editing-id', expenseId);

    // Show edit modal
    const editModal = document.getElementById('expense-edit-container');
    editModal.style.display = 'block';
    setTimeout(() => {
        editModal.classList.add('show');
    }, 10);
}

// Hàm xóa chi tiêu
async function deleteExpenseRow(button) {
    if (confirm("Bạn có chắc muốn xóa mục chi tiêu này không?")) {
        const row = button.closest('tr');
        const expenseId = row.getAttribute('data-expense-id');

        if (!expenseId) {
            console.error('No expense_id found on row');
            alert('Có lỗi xảy ra: Không tìm thấy ID của chi tiêu');
            return;
        }

        try {
            const response = await fetch('/QuanLyTaiChinh_KTPM_Nhom99/jfins/api/expense/delete_expense.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    expense_id: expenseId
                })
            });

            const data = await response.json();

            if (data.success) {
                alert(data.message);
                // Xóa hàng khỏi bảng
                row.remove();
                // Load lại danh sách chi tiêu
                loadExpenses();
            } else {
                alert(data.message || 'Có lỗi xảy ra khi xóa chi tiêu');
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Có lỗi xảy ra, xin vui lòng thử lại');
        }
    }
}

// Helper function to get jar icon
function getJarIcon(jarValue) {
    const jarIcons = {
        'jar-thietyeu': 'home-48',
        'jar-tudotaichinh': 'money-bag-48',
        'jar-giaoduc': 'books-50',
        'jar-huongthu': 'pub-50',
        'jar-thientam': 'gift-50',
        'jar-tietkiem': 'saving-money-64'
    };
    return jarIcons[jarValue] || 'home-48';
}

// Helper function to get jar value from text
function getJarValue(jarText) {
    const jarValues = {
        'Thiết yếu': '1',
        'Tự Do Tài Chính': '2',
        'Giáo Dục': '3',
        'Hưởng Thụ': '4',
        'Thiện Tâm': '5',
        'Tiết Kiệm': '6'
    };
    return jarValues[jarText] || '1';
}

// Add event listener for save edit button
saveEditBtn.addEventListener('click', saveEditExpense);

// 1. Các hàm để hiển thị modal
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded, initializing expense modal functionality...');

    // Get modal elements
    const expenseModal = document.getElementById('expense-form-container');
    const expenseEditModal = document.getElementById('expense-edit-container');
    const closeBtns = document.querySelectorAll('.close');
    const cancelExpenseBtn = document.getElementById('cancel-expense');
    const saveExpenseBtn = document.getElementById('save-expense');
    const addExpenseBtn = document.querySelector('.add-button');
    const cancelEditExpenseBtn = document.getElementById('cancel-edit-expense');
    const saveEditExpenseBtn = document.getElementById('save-edit-expense');
    window.expenseTable = document.querySelector('.table-chitieu tbody');

    // Get form elements
    const expenseDate = document.getElementById('expense-date');
    const expenseAmount = document.getElementById('expense-amount');
    const expenseDescription = document.getElementById('expense-description');
    const expenseJarSelect = document.getElementById('expense-jar-select');
    const expenseJarBalance = document.getElementById('expense-jar-balance');

    // Set today's date as default
    expenseDate.valueAsDate = new Date();

    // Hàm cập nhật số dư khi chọn hũ
    async function updateJarBalance(jarId) {
        try {
            console.log('Getting balance for jar:', jarId);
            const response = await fetch('/QuanLyTaiChinh_KTPM_Nhom99/jfins/api/jar/get_jar_balance.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    jar_id: jarId
                })
            });

            const data = await response.json();
            console.log('Balance response:', data);

            if (data.success) {
                // Format số dư theo định dạng tiền tệ Việt Nam
                const formattedBalance = new Intl.NumberFormat('vi-VN', {
                    style: 'currency',
                    currency: 'VND',
                    minimumFractionDigits: 0,
                    maximumFractionDigits: 0
                }).format(data.balance);
                
                console.log('Formatted balance:', formattedBalance);
                expenseJarBalance.value = formattedBalance;
            } else {
                console.error('Error getting jar balance:', data.message);
                expenseJarBalance.value = '0 đ';
            }
        } catch (error) {
            console.error('Error:', error);
            expenseJarBalance.value = '0 đ';
        }
    }

    // Thêm event listener cho việc chọn hũ
    expenseJarSelect.addEventListener('change', (e) => {
        const selectedJarId = e.target.value;
        console.log('Selected jar changed to:', selectedJarId);
        if (selectedJarId) {
            updateJarBalance(selectedJarId);
        } else {
            expenseJarBalance.value = '0 đ';
        }
    });

    // Function to show modal
    function showModal(modal) {
        modal.style.display = 'block';
        modal.offsetHeight;
        modal.classList.add('show');
    }

    // Function to hide modal
    function hideModal(modal) {
        modal.classList.remove('show');
        setTimeout(() => {
            modal.style.display = 'none';
        }, 300);
    }

    // Show modal when clicking add expense button
    addExpenseBtn.addEventListener('click', () => {
        // Reset form
        expenseDate.valueAsDate = new Date();
        expenseAmount.value = '';
        expenseDescription.value = '';
        expenseJarSelect.value = '';
        expenseJarBalance.value = '0 đ';
        
        // Show modal
        showModal(expenseModal);
    });

    // Close modals when clicking the X button
    closeBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            hideModal(expenseModal);
            hideModal(expenseEditModal);
        });
    });

    // Close modals when clicking outside
    window.addEventListener('click', (event) => {
        if (event.target === expenseModal) {
            hideModal(expenseModal);
        }
        if (event.target === expenseEditModal) {
            hideModal(expenseEditModal);
        }
    });

    // Handle cancel buttons
    cancelExpenseBtn.addEventListener('click', () => {
        hideModal(expenseModal);
    });

    cancelEditExpenseBtn.addEventListener('click', () => {
        hideModal(expenseEditModal);
    });

    // 2. HÀM THÊM CHI TIÊU
    // Handle save button for new expense
    saveExpenseBtn.addEventListener('click', async () => {
        const date = document.getElementById('expense-date').value;
        const amount = document.getElementById('expense-amount').value;
        const description = document.getElementById('expense-description').value;
        const jarId = document.getElementById('expense-jar-select').value;

        // Kiểm tra input
        if(!date || !amount || !description || !jarId) {
            alert('Vui lòng nhập đủ thông tin');
            return;
        }

        try {
            const response = await fetch('/QuanLyTaiChinh_KTPM_Nhom99/jfins/api/expense/add_expense.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    date,
                    amount,
                    description,
                    jar_id: jarId
                })
            });

            const data = await response.json();

            if(data.success) {
                alert(data.message);
                hideModal(expenseModal);
                // Reset form
                document.getElementById('expense-date').value = '';
                document.getElementById('expense-amount').value = '';
                document.getElementById('expense-description').value = '';
                document.getElementById('expense-jar-select').value = '';
                document.getElementById('expense-jar-balance').value = '';
                // Load lại danh sách chi tiêu
                loadExpenses();
            } else {
                alert(data.message || 'Có lỗi xảy ra khi thêm chi tiêu');
            }
        } catch (error) {
            console.error('Error: ', error);
            alert('Có lỗi xảy ra, xin vui lòng thử lại');
        }
    });
});

// 3. HÀM GET EXPENSES
// Hàm load danh sách chi tiêu
async function loadExpenses() {
    try {
        const response = await fetch('/QuanLyTaiChinh_KTPM_Nhom99/jfins/api/expense/get_expenses.php');
        const data = await response.json();

        if(data.success) {
            // Xóa nội dung cũ
            expenseTable.innerHTML = '';
            
            // Thêm data mới
            data.data.forEach(expense => {
                const row = document.createElement('tr');
                row.setAttribute('data-expense-id', expense.expense_id);
                row.innerHTML = `
                    <td>${formatDate(expense.expense_date)}</td>
                    <td class="category">
                        ${expense.jar_name}
                    </td>
                    <td>${expense.description}</td>
                    <td>${formatMoney(expense.amount)}</td>
                    <td class="actions">
                        <button class="thunhap-action-btn" onclick="editExpenseRow(this)">✏️</button>
                        <button class="thunhap-action-btn" onclick="deleteExpenseRow(this)">🗑️</button>
                    </td>    
                `;
                expenseTable.appendChild(row);
            });

            // Thêm sự kiện tìm kiếm
            const searchInput = document.getElementById('expense-search');
            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    const searchTerm = this.value.toLowerCase();
                    const rows = expenseTable.getElementsByTagName('tr');
                    
                    for (let row of rows) {
                        const description = row.cells[2].textContent.toLowerCase();
                        if (description.includes(searchTerm)) {
                            row.style.display = '';
                        } else {
                            row.style.display = 'none';
                        }
                    }
                });
            }
        } else {
            console.error('Error loading expenses: ', data.message);
        }
    } catch (error) {
        console.error('Error loading expenses: ', error);
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

// Load danh sách chi tiêu khi trang được tải
document.addEventListener('DOMContentLoaded', loadExpenses);