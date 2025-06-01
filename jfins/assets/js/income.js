// Wait for DOM to be fully loaded
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded, initializing income modal functionality...');

    // Get modal elements
    const incomeModal = document.getElementById('income-form-container');
    const closeBtn = incomeModal.querySelector('.close');
    const cancelIncomeBtn = document.getElementById('cancel-income');
    const saveIncomeBtn = document.getElementById('save-income');
    const addIncomeBtn = document.querySelector('.thunhap-add-btn');

    // Get form elements
    const incomeDate = document.getElementById('income-date');
    const incomeAmount = document.getElementById('income-amount');
    const incomeDescription = document.getElementById('income-description');

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

    // Close modal when clicking the X button
    closeBtn.addEventListener('click', () => {
        hideModal(incomeModal);
    });

    // Close modal when clicking outside
    window.addEventListener('click', (event) => {
        if (event.target === incomeModal) {
            hideModal(incomeModal);
        }
    });

    // Handle cancel button
    cancelIncomeBtn.addEventListener('click', () => {
        hideModal(incomeModal);
    });

    // Handle save button
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
                <button class="thunhap-action-btn">✏️</button>
                <button class="thunhap-action-btn">🗑️</button>
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
}); 