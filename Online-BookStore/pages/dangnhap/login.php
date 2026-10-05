<?php
// login.php
session_start();
include_once("clslogin.php");
$p = new login();

$error = '';
# Nếu đã đăng nhập từ trước
if(isset($_SESSION['id']) && isset($_SESSION['role'])){
    if($_SESSION['role'] == 1){
        header('location: dashboard.php');
    }else{
        header('location: trangchu.php');
    }
    exit();
}

if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btn_login'])){
    $user = trim($_POST['user'] ?? '');
    $pass = $_POST['password'] ?? '';

    if($user != '' && $pass != ''){
        $result = $p->mylogin($user,$pass);
        if($result == 1){
            if($_SESSION['role']==1){
                header('location: dashboard.php');
            }else{
                header('location: trangchu.php');
            }
            exit();
        }else{
            $error = 'Tên tài khoản hoặc mật khẩu không chính xác!';
        }
        
    }else{
        $error = 'Vui lòng nhập đầy đủ tên tài khoản và mật khẩu!';
    }
}

?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Đăng Nhập - BookStore</title>

  <!-- 1. Nhúng Bootstrap CSS -->
  <link rel="stylesheet" href="css/bootstrap.min.css">

  <style>
    :root {
      --primary-color: #1e4276;
      --primary-hover: #153056;
      --bg-color: #f6f8fa;
    }
    body {
      font-family: system-ui, -apple-system, "Segoe UI", Roboto, Arial, sans-serif;
      background-color: var(--bg-color);
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }
    /* Style Navbar tinh chỉnh nhẹ nhàng */
    .navbar {
      background-color: #fff;
      box-shadow: 0 2px 10px rgba(0,0,0,0.04);
    }
    .brand-title {
      font-weight: 800;
      color: var(--primary-color);
      letter-spacing: 0.5px;
    }
    .brand-logo {
      height: 42px;
      width: auto;
      object-fit: contain;
    }
    .nav-link-item {
      color: #4a5568;
      font-weight: 600;
      font-size: 0.88rem;
      letter-spacing: 0.3px;
      padding: 6px 14px !important;
      border-radius: 20px;
      transition: all 0.2s ease;
    }
    .nav-link-item:hover {
      color: var(--primary-color);
      background-color: rgba(30, 66, 118, 0.06);
    }
    
    /* Login Card */
    .login-card {
      background: #fff;
      border: 1px solid #e1e4e8;
      border-radius: 12px;
      padding: 36px 32px;
      box-shadow: 0 4px 20px rgba(0,0,0,0.03);
    }
    .form-control {
      border-radius: 6px;
      padding: 10px 14px;
      border: 1px solid #d0d7de;
    }
    .form-control:focus {
      border-color: var(--primary-color);
      box-shadow: 0 0 0 3px rgba(30, 66, 118, 0.15);
    }
    .btn-submit {
      background-color: var(--primary-color);
      color: #fff;
      border: none;
      padding: 10px;
      font-weight: 600;
      border-radius: 6px;
      transition: background-color 0.2s;
    }
.btn-submit:hover {
      background-color: var(--primary-hover);
      color: #fff;
    }
    .password-wrapper {
      position: relative;
    }
    .btn-toggle-eye {
      position: absolute;
      right: 12px;
      top: 50%;
      transform: translateY(-50%);
      background: none;
      border: none;
      cursor: pointer;
      color: #6c757d;
      font-size: 0.9rem;
      padding: 0;
    }
  </style>
</head>
<body>

  <!-- Header / Navigation tinh chỉnh đẹp mắt -->
  <nav class="navbar navbar-expand-lg py-2 sticky-top">
    <div class="container">
      <a class="navbar-brand d-flex align-items-center gap-2" href="index.php">
        <!-- Thay thế Logo mới -->
        <img src="images/logo.jpg" alt="BookStore Logo" class="brand-logo">
        <span class="brand-title fs-4 lh-1">BOOKSTORE</span>
      </a>

      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarMain">
        <ul class="navbar-nav ms-auto align-items-center gap-1">
          <li class="nav-item"><a class="nav-link nav-link-item" href="#">SÁCH MỚI</a></li>
          <li class="nav-item"><a class="nav-link nav-link-item" href="#">KHÓA HỌC</a></li>
          <li class="nav-item"><a class="nav-link nav-link-item" href="#">GIỚI THIỆU</a></li>
          <li class="nav-item"><a class="nav-link nav-link-item" href="#">TIN TỨC</a></li>
          <li class="nav-item"><a class="nav-link nav-link-item" href="#">LIÊN HỆ</a></li>
          <li class="nav-item ms-lg-2 mt-2 mt-lg-0">
             <a href="register.php" class="btn btn-outline-primary btn-sm px-3 rounded-pill fw-semibold">Đăng ký</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Login Form -->
  <main class="flex-grow-1 d-flex align-items-center py-5">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-12 col-sm-10 col-md-8 col-lg-5 col-xl-4">
          <div class="login-card">
            <h4 class="text-center fw-bold mb-4">
              Đăng Nhập <span style="color: var(--primary-color);">BookStore</span>
            </h4>
            <?php 
            if (!empty($error)){
                echo "<div class='alert alert-danger py-2 text-center'>$error</div>";
            }
             ?>
            <form action="login.php" method="POST" autocomplete="on">
              <div class="mb-3">
                <label for="user" class="form-label fw-semibold">Tên tài khoản</label>
                <input type="text" class="form-control" id="user" name="user" placeholder="Ví dụ: hotro@bookstore.vn" required autofocus>
              </div>

              <div class="mb-3">
                <div class="d-flex justify-content-between">
                  <label for="password" class="form-label fw-semibold">Mật khẩu</label>
<a href="forgot-password.php" class="text-decoration-none small text-muted">Quên mật khẩu?</a>
                </div>
                <div class="password-wrapper">
                  <input type="password" class="form-control pe-5" id="password" name="password" placeholder="Nhập mật khẩu" required>
                  <button type="button" class="btn-toggle-eye" id="togglePassword">Hiện</button>
                </div>
              </div>

              <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="remember" name="remember">
                <label class="form-check-label text-muted small" for="remember">Ghi nhớ đăng nhập</label>
              </div>

              <button type="submit" name="btn_login" class="btn btn-submit w-100 mb-3">Đăng nhập</button>

              <div class="text-center text-muted small">
                Bạn chưa có tài khoản? <a href="register.php" class="fw-semibold text-decoration-none">Đăng ký ngay</a>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </main>

  <footer class="text-center py-3 text-muted small border-top bg-white">
    Bản quyền &copy; <?= date('Y') ?> BOOKSTORE.VN
  </footer>

  <script src="../../layout/js/bootstrap.bundle.min.js"></script>
  <script>
    const toggleBtn = document.getElementById('togglePassword');
    const pwdInput = document.getElementById('password');

    toggleBtn?.addEventListener('click', function() {
      if (pwdInput.type === 'password') {
        pwdInput.type = 'text';
        this.textContent = 'Ẩn';
      } else {
        pwdInput.type = 'password';
        this.textContent = 'Hiện';
      }
    });
  </script>
</body>
</html>