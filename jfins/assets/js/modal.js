// Wait for DOM to be fully loaded
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded, initializing modal functionality...');

    // Get modal elements
    const jarModal = document.getElementById('jarModal');
    const expenseModal = document.getElementById('expense-form-container');
    const closeBtns = document.getElementsByClassName('close');
    const jarName = document.getElementById('jar-name');
    const jarBalance = document.getElementById('jar-balance');
    const jarPercent = document.getElementById('jar-percent');
    const jarDescription = document.getElementById('jar-description');

    // Get expense form elements
    const expenseDate = document.getElementById('expense-date');
    const expenseJarSelect = document.getElementById('expense-jar-select');
    const expenseJarBalance = document.getElementById('expense-jar-balance');
    const expenseAmount = document.getElementById('expense-amount');
    const expenseDescription = document.getElementById('expense-description');
    const cancelExpenseBtn = document.getElementById('cancel-expense');
    const saveExpenseBtn = document.getElementById('save-expense');

    // Set today's date as default
    expenseDate.valueAsDate = new Date();

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

    // Jar descriptions
    const jarDescriptions = {
        'Thiết Yếu': 'Chi tiêu cho các nhu cầu thiết yếu hàng ngày như ăn uống, đi lại, nhà ở...',
        'Tự Do Tài Chính': 'Đầu tư để tạo ra thu nhập thụ động và đạt được tự do tài chính',
        'Giáo Dục': 'Đầu tư cho việc học tập, phát triển kỹ năng và kiến thức',
        'Hưởng Thụ': 'Chi tiêu cho các hoạt động giải trí, mua sắm và hưởng thụ',
        'Thiện Tâm': 'Quyên góp từ thiện và giúp đỡ người khác',
        'Tiết Kiệm': 'Tiết kiệm cho các mục tiêu dài hạn và dự phòng khẩn cấp'
    };

    // Jar value mapping
    const jarValueMapping = {
        'Thiết Yếu': 'jar-thietyeu',
        'Tự Do Tài Chính': 'jar-tudotaichinh',
        'Giáo Dục': 'jar-giaoduc',
        'Hưởng Thụ': 'jar-huongthu',
        'Thiện Tâm': 'jar-thientam',
        'Tiết Kiệm': 'jar-tietkiem'
    };

    // Add click event to all jars
    const jars = document.querySelectorAll('.jar');
    console.log('Found jars:', jars.length);

    jars.forEach(jar => {
        jar.addEventListener('click', () => {
            console.log('Jar clicked:', jar);
            const name = jar.querySelector('p').textContent;
            const balance = jar.querySelector('strong').textContent;
            const percent = jar.querySelector('span').textContent;
            
            console.log('Jar details:', { name, balance, percent });
            
            // Update modal content
            jarName.textContent = name;
            jarBalance.textContent = balance;
            jarPercent.textContent = percent;
            jarDescription.textContent = jarDescriptions[name] || '';

            // Show modal
            showModal(jarModal);
        });
    });

    // Handle spend from jar button click
    document.querySelector('.spend-from-jar').addEventListener('click', () => {
        // Hide jar modal
        hideModal(jarModal);
        
        // Set expense form values
        const selectedJar = jarName.textContent;
        expenseJarSelect.value = jarValueMapping[selectedJar];
        expenseJarBalance.value = jarBalance.textContent;
        
        // Show expense modal
        showModal(expenseModal);
    });

    // Close modals when clicking the X button
    Array.from(closeBtns).forEach(btn => {
        btn.addEventListener('click', () => {
            hideModal(jarModal);
            hideModal(expenseModal);
        });
    });

    // Close modals when clicking outside
    window.addEventListener('click', (event) => {
        if (event.target === jarModal) {
            hideModal(jarModal);
        }
        if (event.target === expenseModal) {
            hideModal(expenseModal);
        }
    });

    // Handle cancel button
    cancelExpenseBtn.addEventListener('click', () => {
        hideModal(expenseModal);
    });

    // Handle save button
    saveExpenseBtn.addEventListener('click', () => {
        // TODO: Implement saving expense functionality
        alert('Chức năng lưu chi tiêu sẽ được thêm vào sau!');
        hideModal(expenseModal);
    });
}); 
const deleteConfirmModal = document.getElementById('deleteConfirmModal');
const confirmDeleteBtn = document.getElementById('confirm-delete-btn');
const cancelDeleteBtn = document.getElementById('cancel-delete-btn');
const confirmDeleteInput = document.getElementById('confirm-delete-input');
const closeDeleteModal = deleteConfirmModal.querySelector('.close');
const deleteAllBtn = document.querySelector('.delete-btn:enabled'); // Nút xóa chính

// Hàm mở modal xác nhận xóa
function openDeleteConfirmModal() {
  deleteConfirmModal.classList.add('show');
}

// Hàm đóng modal xác nhận xóa
function closeDeleteConfirmModal() {
  deleteConfirmModal.classList.remove('show');
  confirmDeleteInput.value = '';
  confirmDeleteBtn.disabled = true;
}

// Sự kiện khi người dùng nhập vào ô xác nhận
confirmDeleteInput.addEventListener('input', function() {
  if (this.value.trim().toUpperCase() === 'XÓA') {
    confirmDeleteBtn.disabled = false;
  } else {
    confirmDeleteBtn.disabled = true;
  }
});

// Sự kiện khi nhấn nút Xóa tất cả
confirmDeleteBtn.addEventListener('click', function() {
  // Gọi hàm xử lý xóa dữ liệu thực tế ở đây
  deleteAllData();
  closeDeleteConfirmModal();
});

// Sự kiện khi nhấn nút Hủy hoặc nút đóng
cancelDeleteBtn.addEventListener('click', closeDeleteConfirmModal);
closeDeleteModal.addEventListener('click', closeDeleteConfirmModal);

// Sự kiện click bên ngoài modal để đóng
window.addEventListener('click', function(event) {
  if (event.target === deleteConfirmModal) {
    closeDeleteConfirmModal();
  }
});

// Kết nối với nút xóa chính
if (deleteAllBtn) {
  deleteAllBtn.addEventListener('click', openDeleteConfirmModal);
}
// Hàm xử lý khi checkbox xác nhận thay đổi
const dangerCheckbox = document.querySelector('.danger-checkbox');
const deleteBtn = document.querySelector('.delete-btn');

dangerCheckbox.addEventListener('change', function() {
  deleteBtn.disabled = !this.checked;
  
  // Khi enabled, thêm sự kiện click
  if (!this.checked) {
    deleteBtn.removeEventListener('click', openDeleteConfirmModal);
  } else {
    deleteBtn.addEventListener('click', openDeleteConfirmModal);
  }
});

// Hàm xóa dữ liệu (ví dụ)
function deleteAllData() {
  // Thực hiện xóa dữ liệu ở đây
  alert('Đã xóa toàn bộ dữ liệu!');
  
  // Reset checkbox và nút
  dangerCheckbox.checked = false;
  deleteBtn.disabled = true;
}

