//1. Get DOM elements
// Profile elements
const profileModal = document.getElementById('profile-modal');
const profileBtn = document.getElementById('profile-btn');
const profileCloseBtns = document.querySelectorAll('#profile-modal .close');
const cancelProfileBtn = document.getElementById('cancel-profile');
const saveProfileBtn = document.getElementById('save-profile');
const changeAvatarBtn = document.querySelector('.change-avatar-btn');
const profileAvatarImg = document.getElementById('profile-avatar-img');

// Logout elements
const logoutModal = document.getElementById('logout-modal');
const logoutBtn = document.getElementById('logout-btn');
const logoutCloseBtns = document.querySelectorAll('#logout-modal .close');
const cancelLogoutBtn = document.getElementById('cancel-logout');
const confirmLogoutBtn = document.getElementById('confirm-logout');

//2. Function to open profile modal
function openProfileModal() {
    if (profileModal) {
        profileModal.style.display = 'block';
        profileModal.classList.add('show');
    }
}

//3. Function to close profile modal
function closeProfileModal() {
    if (profileModal) {
        profileModal.classList.remove('show');
        profileModal.style.display = 'none';
        // Clear form fields when closing
        document.getElementById('current-password').value = '';
        document.getElementById('new-password').value = '';
        document.getElementById('confirm-password').value = '';
    }
}

//4. Function to open logout modal
function openLogoutModal() {
    if (logoutModal) {
        logoutModal.style.display = 'block';
        logoutModal.classList.add('show');
    }
}

//5. Function to close logout modal
function closeLogoutModal() {
    if (logoutModal) {
        logoutModal.classList.remove('show');
        logoutModal.style.display = 'none';
    }
}

//6. Function to handle logout
function handleLogout() {
    window.location.href = './auth/logout.php';
}

// Khởi tạo profilemodel
function initializeProfileModal() {
    console.log('Initializing profile modal');

    // * Cho phần profile

    // nút profile -> mở profile model
    if (profileBtn) {
        console.log('Adding click listener to profile button');
        profileBtn.addEventListener('click', (e) => {
            e.preventDefault();
            openProfileModal();
        });
    }

    // Close buttons
    profileCloseBtns.forEach(btn => {
        btn.addEventListener('click', closeProfileModal);
    });

    // Cancel button
    if (cancelProfileBtn) {
        cancelProfileBtn.addEventListener('click', closeProfileModal);
    }

    // Close modal when clicking outside
    window.addEventListener('click', (event) => {
        if (event.target === profileModal) {
            closeProfileModal();
        }
    });

    // Handle save password
    if (saveProfileBtn) {
        saveProfileBtn.addEventListener('click', () => {
            if (validatePassword()) {
                // Here you would typically make an API call to change the password
                alert('Mật khẩu đã được thay đổi thành công!');
                closeProfileModal();
            }
        });
    }

    // * Cho phần logout

    // Logout button click -> mở logout modelmodel
    if (logoutBtn) {
        logoutBtn.addEventListener('click', (e) => {
            e.preventDefault();
            openLogoutModal();
        });
    }

    // Close buttons for logout modal
    logoutCloseBtns.forEach(btn => {
        btn.addEventListener('click', closeLogoutModal);
    });

    // Cancel button for logout modal
    if (cancelLogoutBtn) {
        cancelLogoutBtn.addEventListener('click', closeLogoutModal);
    }

    // Close logout modal when clicking outside
    window.addEventListener('click', (event) => {
        if (event.target === logoutModal) {
            closeLogoutModal();
        }
    });
    
    // Confirm logout button
    if (confirmLogoutBtn) {
        confirmLogoutBtn.addEventListener('click', handleLogout);
    }
}

// Initialize when DOM is loaded
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initializeProfileModal);
} else {
    initializeProfileModal();
} 