//1. lấy các phần tử DOM 
const addExpenseBtn = document.querySelector('.add-button'); 
const expenseModal = document.getElementById('expense-form-container');
const closeBtn = expenseModal.querySelector('.close');
const cancelBtn = document.getElementById('cancel-expense');

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

//3. Function để đóng
// Hàm đóng form thêm chi tiêu
function closeExpenseForm() {
    const overlay = document.getElementById('expenseFormOverlay');
    overlay.classList.remove('show');
    setTimeout(() => {
      overlay.style.display = 'none';
      // Reset form
      document.getElementById('expense-date').value = new Date().toISOString().split('T')[0];
      document.getElementById('expense-jar').value = '';
      document.getElementById('expense-amount').value = '';
      document.getElementById('expense-description').value = '';
    }, 300);
  }

//4. sử dụng các hàm bằng event listeners
// Event listeners
addExpenseBtn.addEventListener('click', openExpenseModal);
closeBtn.addEventListener('click', closeExpenseModal);
cancelBtn.addEventListener('click', closeExpenseModal);


// Close modal when clicking outside
window.addEventListener('click', (event) => {
    if (event.target === expenseModal) {
        closeExpenseModal();
    }
});

// Hàm lưu chi tiêu
function saveExpense() {
    const date = document.getElementById('expense-date').value;
    const jar = document.getElementById('expense-jar').value;
    const amount = document.getElementById('expense-amount').value;
    const description = document.getElementById('expense-description').value;
  }