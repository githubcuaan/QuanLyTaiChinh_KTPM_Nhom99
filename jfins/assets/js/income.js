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

    // Get form elements
    const incomeDate = document.getElementById('income-date');
    const incomeAmount = document.getElementById('income-amount');
    const incomeDescription = document.getElementById('income-description');
    const editIncomeDate = document.getElementById('edit-income-date');
    const editIncomeAmount = document.getElementById('edit-income-amount');
    const editIncomeDescription = document.getElementById('edit-income-description');

    // Set today's date as default
    incomeDate.valueAsDate = new Date();

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

    // Handle save button for new income
    saveIncomeBtn.addEventListener('click', () => {
        // Validate form
        if (!incomeDate.value || !incomeAmount.value || !incomeDescription.value) {
            alert('Vui lòng điền đầy đủ thông tin!');
            return;
        }

        // Format the date
        const date = new Date(incomeDate.value);
        const formattedDate = date.toLocaleDateString('vi-VN');

        // Format the amount
        const amount = parseFloat(incomeAmount.value).toLocaleString('vi-VN') + ' đ';

        // Create new row in the table
        const table = document.querySelector('.thunhap-table tbody');
        const newRow = document.createElement('tr');
        newRow.innerHTML = `
            <td>${formattedDate}</td>
            <td>${incomeDescription.value}</td>
            <td>${amount}</td>
            <td>
                <button id="income-edit-btn" class="thunhap-action-btn" onclick="editIncomeRow(this)">✏️</button>
                <button id="income-delete-btn" class="thunhap-action-btn" onclick="deleteIncomeRow(this)">🗑️</button>
            </td>
        `;
        table.insertBefore(newRow, table.firstChild);

        // Hide modal
        hideModal(incomeModal);

        // TODO: Add actual data saving functionality here
        console.log('Income saved:', {
            date: incomeDate.value,
            amount: incomeAmount.value,
            description: incomeDescription.value
        });
    });

    // Handle save button for editing income
    saveEditIncomeBtn.addEventListener('click', () => {
        // Validate form
        if (!editIncomeDate.value || !editIncomeAmount.value || !editIncomeDescription.value) {
            alert('Vui lòng điền đầy đủ thông tin!');
            return;
        }

        // Format the date
        const date = new Date(editIncomeDate.value);
        const formattedDate = date.toLocaleDateString('vi-VN');

        // Format the amount
        const amount = parseFloat(editIncomeAmount.value).toLocaleString('vi-VN') + ' đ';

        // Update the row
        const currentRow = document.querySelector('.thunhap-table tbody tr.editing');
        if (currentRow) {
            currentRow.innerHTML = `
                <td>${formattedDate}</td>
                <td>${editIncomeDescription.value}</td>
                <td>${amount}</td>
                <td>
                    <button id="income-edit-btn" class="thunhap-action-btn" onclick="editIncomeRow(this)">✏️</button>
                    <button id="income-delete-btn" class="thunhap-action-btn" onclick="deleteIncomeRow(this)">🗑️</button>
                </td>
            `;
            currentRow.classList.remove('editing');
        }

        // Hide modal
        hideModal(incomeEditModal);

        // TODO: Add actual data saving functionality here
        console.log('Income updated:', {
            date: editIncomeDate.value,
            amount: editIncomeAmount.value,
            description: editIncomeDescription.value
        });
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

// Function to edit income row
function editIncomeRow(button) {
    const row = button.closest('tr');
    const cells = row.cells;
    
    // Get current values
    const date = cells[0].textContent;
    const description = cells[1].textContent;
    const amount = cells[2].textContent.replace(' đ', '').replace(/\./g, '');

    // Set values in edit form
    const editDate = document.getElementById('edit-income-date');
    const editAmount = document.getElementById('edit-income-amount');
    const editDescription = document.getElementById('edit-income-description');

    // Convert date from dd/mm/yyyy to yyyy-mm-dd
    const [day, month, year] = date.split('/');
    editDate.value = `${year}-${month}-${day}`;
    
    editAmount.value = amount;
    editDescription.value = description;

    // Mark row as being edited
    row.classList.add('editing');

    // Show edit modal
    const editModal = document.getElementById('income-edit-container');
    editModal.style.display = 'block';
    editModal.offsetHeight;
    editModal.classList.add('show');
}

// Function to delete income row
function deleteIncomeRow(button) {
    if (confirm("Bạn có chắc muốn xóa mục thu nhập này không?")) {
        const row = button.closest('tr');
        row.remove();
    }
} 