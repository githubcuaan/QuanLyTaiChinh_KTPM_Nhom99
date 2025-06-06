<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"
    />
    <link rel="stylesheet" href="../../assets/css/login.css" />
    <link rel="stylesheet" href="../../assets/css/model.css" />
    <title>JFINs - Quản lý tài chính</title>
  </head>

  <body <?php if(isset($signup_success) && $signup_success): ?>data-signup-success="true"<?php endif; ?>>
    <div class="container" id="container">
      <div class="form-container sign-up">
        <form action="signin.php" method="POST" id="signupForm" onsubmit="return handleSignup(event)">
          <h1>Tạo tài khoản</h1>
          <div class="social-icons">
            <a href="#" class="icon"><i class="fa-brands fa-google-plus-g"></i></a>
            <a href="#" class="icon"><i class="fa-brands fa-facebook-f"></i></a>
            <a href="#" class="icon"><i class="fa-brands fa-github"></i></a>
            <a href="#" class="icon"><i class="fa-brands fa-linkedin-in"></i></a>
          </div>
          <span>hoặc đăng kí bằng email</span>
          <input type="text" name="username" placeholder="Tên đăng nhập" />
          <input type="email" name="email" placeholder="Email" />
          <input type="password" name="password" placeholder="Nhập mật khẩu" />
          <input type="password" name="confirm_password" placeholder="Nhập lại mật khẩu" />
          <button>Đăng ký</button>
        </form>
      </div>
      <div class="form-container sign-in">
        <form id="loginForm">
          <h1>Đăng nhập</h1>
          <div class="social-icons">
            <a href="#" class="icon"><i class="fa-brands fa-google-plus-g"></i></a>
            <a href="#" class="icon"><i class="fa-brands fa-facebook-f"></i></a>
            <a href="#" class="icon"><i class="fa-brands fa-github"></i></a>
            <a href="#" class="icon"><i class="fa-brands fa-linkedin-in"></i></a>
          </div>
          <span>hoặc đăng nhập bằng email</span>
          <input type="email" name="email" placeholder="Email" />
          <input type="password" name="password" placeholder="Nhập mật khẩu" />
          <a href="#">Quên Mật Khẩu?</a>
          <button type="submit">Đăng nhập</button>
        </form>
      </div>
      <div class="toggle-container">
        <div class="toggle">
          <div class="toggle-panel toggle-left">
            <h1>Chào mừng trở lại!</h1>
            <p>Đăng nhập để sử dụng tất cả tính năng</p>
            <button class="hidden" id="login">Đăng nhập</button>
          </div>
          <div class="toggle-panel toggle-right">
            <h1>Chào bạn iu!</h1>
            <p>Đăng kí tài khoản để sử dụng tất cả các tính năng</p>
            <button class="hidden" id="register">Đăng ký</button>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal thông báo đăng ký thành công -->
    <div id="successModal" class="modal">
      <div class="modal-content">
        <div class="modal-header">
          <h2>Đăng nhập thành công!</h2>
        </div>
        <div class="modal-body">
          <i class="fas fa-check-circle" style="color: #2da0a8; font-size: 48px;"></i>
          <p>Chào mừng bạn quay trở lại!</p>
        </div>
        <div class="modal-footer">
          <button id="confirmSuccess" class="btn btn-primary">Xác nhận</button>
        </div>
      </div>
    </div>

    <script src="../../assets/js/login.js"></script>
  </body>
</html>
