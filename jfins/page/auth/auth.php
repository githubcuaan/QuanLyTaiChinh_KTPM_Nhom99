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
    <title>JFINs - Quản lý tài chính</title>
  </head>

  <body>
    <div class="container" id="container">
      <div class="form-container sign-up">
        <form action="signin.php" method="POST" id="signupForm">
          <h1>Tạo tài khoản</h1>
          <div class="social-icons">
            <a href="#" class="icon"
              ><i class="fa-brands fa-google-plus-g"></i
            ></a>
            <a href="#" class="icon"><i class="fa-brands fa-facebook-f"></i></a>
            <a href="#" class="icon"><i class="fa-brands fa-github"></i></a>
            <a href="#" class="icon"
              ><i class="fa-brands fa-linkedin-in"></i
            ></a>
          </div>
          <span>hoặc đăng kí bằng email</span>
          <?php if(isset($error)): ?>
            <div class="error-message">
              <i class="fas fa-exclamation-circle"></i>
              <?php echo $error; ?>
            </div>
          <?php endif; ?>
          <input type="text" name="username" placeholder="Username" />
          <input type="email" name="email" placeholder="Email" />
          <input type="password" name="password" placeholder="Password" />
          <input type="password" name="confirm_password" placeholder="Nhập lại mật khẩu" />
          <button>Đăng ký</button>
        </form>
      </div>
      <div class="form-container sign-in">
        <form action="login.php" method="POST" id="loginForm"> 
          <h1>Đăng nhập</h1>
          <div class="social-icons">
            <a href="#" class="icon"
              ><i class="fa-brands fa-google-plus-g"></i
            ></a>
            <a href="#" class="icon"><i class="fa-brands fa-facebook-f"></i></a>
            <a href="#" class="icon"><i class="fa-brands fa-github"></i></a>
            <a href="#" class="icon"
              ><i class="fa-brands fa-linkedin-in"></i
            ></a>
          </div>
          <span>hoặc đăng nhập bằng email</span>
          <?php if(isset($error)): ?>
            <div class="error-message">
              <i class="fas fa-exclamation-circle"></i>
              <?php echo $error; ?>
            </div>
          <?php endif; ?>
          <input type="email" name="email" placeholder="Email" />
          <input type="password" name="password" placeholder="Password" />
          <a href="#">Quên Mật Khẩu?</a>
          <button>Đăng nhập</button>
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
            <p>
              Đăng kí tài khoản để sử dụng tất cả các tính năng
            </p>
            <button class="hidden" id="register">Sign Up</button>
          </div>
        </div>
      </div>
    </div>

    <script src="../../assets/js/login.js"></script>
  </body>
</html>
