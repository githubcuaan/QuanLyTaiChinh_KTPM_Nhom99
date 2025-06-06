const container = document.getElementById('container');
const registerBtn = document.getElementById('register');
const loginBtn = document.getElementById('login');

const successModal = document.getElementById('successModal');
const confirmSuccessBtn = document.getElementById('confirmSuccess');

registerBtn.addEventListener('click', () => {
    container.classList.add('active');
});

loginBtn.addEventListener('click', () => {
    container.classList.remove('active');
});

//Xử lý form đăng ký bằng async function -> xử lý api
async function handleSignup(event) {
    event.preventDefault();

    const form = event.target;
    const formData = new FormData(form);

    try {
        const response = await fetch('signin.php', {
            method: 'POST',
            body: formData
        });

        const data = await response.json();

        //Xóa thông báo cũ
        const oldError = form.querySelector('.error-message');
        if(oldError) {
            oldError.remove();
        }

        if(data.success === 'true') {
            // Hiển thị model thành công
            successModal.style.display = 'block';
            // Đợi một chút để đảm bảo display: block đã được áp dụng
            setTimeout(() => {
                successModal.classList.add('show');
            }, 10);
        } else {
            // Hiển thị lỗi
            const errorDiv = document.createElement('div');
            errorDiv.className = 'error-message';
            errorDiv.innerHTML = `<i class="fas fa-exclamation-circle"></i> ${data.message}`;

            // Thêm thông báo lỗi mới vào sau span 
            const span = form.querySelector('span');
            span.after(errorDiv);
        }
    } catch (error) {
        console.error('Error:', error);
        // Hiển thị lỗi kết nối
        const errorDiv = document.createElement('div');
        errorDiv.className = 'error-message';
        errorDiv.innerHTML = '<i class="fas fa-exclamation-circle"></i> Có lỗi xảy ra, vui lòng thử lại';
        
        const span = form.querySelector('span');
        span.after(errorDiv);
    } 
    return false;
} 

// Xử lý nút xác nhận trong model
if (confirmSuccessBtn) {
    confirmSuccessBtn.addEventListener('click', () => {
        successModal.classList.remove('show');
        setTimeout(() => {
            successModal.style.display = 'none';
            window.location.href = '../index.php';
        }, 300);
    });
}

// Close modal when clicking outside
window.addEventListener('click', (event) => {
    if (event.target === successModal) {
        successModal.classList.remove('show');
        setTimeout(() => {
            successModal.style.display = 'none';
        }, 300);
    }
});
