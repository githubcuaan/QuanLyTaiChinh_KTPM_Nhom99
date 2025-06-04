//1. Get DOM elements
const profileModal = document.getElementById('profile-modal');
const profileBtn = document.getElementById('profile-btn');
const profileCloseBtns = document.querySelectorAll('#profile-modal .close');
const cancelProfileBtn = document.getElementById('cancel-profile');
const saveProfileBtn = document.getElementById('save-profile');
const changeAvatarBtn = document.querySelector('.change-avatar-btn');
const profileAvatarImg = document.getElementById('profile-avatar-img');

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



// Initialize event listeners
function initializeProfileModal() {
    console.log('Initializing profile modal');
    
    // Profile button click
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
}

// Initialize when DOM is loaded
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initializeProfileModal);
} else {
    initializeProfileModal();
} 