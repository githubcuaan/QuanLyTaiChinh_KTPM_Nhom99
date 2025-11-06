<header>
      <img
        class="header-icon"
        src="../assets/icon/icons8-money-bag-50.png"
        alt="icon"
        style="width: 40px"
      />
      <p class="header-title">JFINs - Quản lý tài chính</p>
      
      <!-- user-profile -->
      <div class="user-profile">
        <button class="profile-btn">
          <i class="fa-solid fa-user"></i>
          <p class="username"><?php echo htmlspecialchars($_SESSION['username']); ?></p>
        </button>
        <div class="dropdown-menu">
          <button class="dropdown-item" id="profile-btn">
            <i class="fa-solid fa-user-circle"></i>
            Đổi mật khẩu
          </button>
          <button class="dropdown-item" id="logout-btn">
            <i class="fa-solid fa-right-from-bracket"></i>
            Đăng xuất
          </button>
        </div>
      </div>
      
    </header>
