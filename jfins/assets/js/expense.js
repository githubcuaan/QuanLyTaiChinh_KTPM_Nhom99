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
        <td class="category"><img src="../assets/icon/jars/icons8-${getJarIcon(jar)}.png" class="category-icon"/>${jarName}</td>
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
function saveEditExpense() {
    const date = document.getElementById('edit-expense-date').value;
    const jar = document.getElementById('edit-expense-jar-select').value;
    const amount = document.getElementById('edit-expense-amount').value;
    const description = document.getElementById('edit-expense-description').value;

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
    const jarName = document.querySelector(`#edit-expense-jar-select option[value="${jar}"]`).textContent;

    // Update the row
    const currentRow = document.querySelector('.table-chitieu tbody tr.editing');
    if (currentRow) {
        currentRow.innerHTML = `
            <td>${formattedDate}</td>
            <td class="category"><img src="../assets/icon/jars/icons8-${getJarIcon(jar)}.png" class="category-icon"/>${jarName}</td>
            <td>${description}</td>
            <td>${formattedAmount}</td>
            <td class="actions">
                <button id="expense-edit-btn" onclick="editExpenseRow(this)">✏️</button>
                <button id="expense-delete-btn" onclick="deleteExpenseRow(this)">🗑️</button>
            </td>
        `;
        currentRow.classList.remove('editing');
    }

    // Hide modal
    closeExpenseEditModal();
}

// Hàm sửa chi tiêu
function editExpenseRow(button) {
    const row = button.closest('tr');
    const cells = row.cells;
    
    // Get current values
    const date = cells[0].textContent;
    const jarText = cells[1].textContent;
    const description = cells[2].textContent;
    const amount = cells[3].textContent.replace(' đ', '').replace(/\./g, '');

    // Set values in edit form
    const editDate = document.getElementById('edit-expense-date');
    const editJar = document.getElementById('edit-expense-jar-select');
    const editAmount = document.getElementById('edit-expense-amount');
    const editDescription = document.getElementById('edit-expense-description');

    // Convert date from dd/mm/yyyy to yyyy-mm-dd
    const [day, month, year] = date.split('/');
    editDate.value = `${year}-${month}-${day}`;
    
    // Set jar value based on text
    const jarValue = getJarValue(jarText);
    editJar.value = jarValue;
    
    editAmount.value = amount;
    editDescription.value = description;

    // Mark row as being edited
    row.classList.add('editing');

    // Show edit modal
    expenseEditModal.style.display = 'block';
    setTimeout(() => {
        expenseEditModal.classList.add('show');
    }, 10);
}

// Hàm xóa chi tiêu
function deleteExpenseRow(button) {
    if (confirm("Bạn có chắc muốn xóa mục chi tiêu này không?")) {
        const row = button.closest('tr');
        row.remove();
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
        '🏠 Thiết yếu': 'jar-thietyeu',
        '💰 Tự Do Tài Chính': 'jar-tudotaichinh',
        '📘 Giáo Dục': 'jar-giaoduc',
        '🎉 Hưởng Thụ': 'jar-huongthu',
        '🎁 Thiện Tâm': 'jar-thientam',
        '📋 Tiết Kiệm': 'jar-tietkiem'
    };
    return jarValues[jarText] || 'jar-thietyeu';
}

// Add event listener for save edit button
saveEditBtn.addEventListener('click', saveEditExpense);