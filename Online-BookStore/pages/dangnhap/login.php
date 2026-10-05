<?php

session_start();
include_once("../../class/clslogin.php");
$p = new login();

$error = '';
# Nếu đã đăng nhập dã có 
if(isset($_SESSION['id']) && isset($_SESSION['role'])){
    if($_SESSION['role'] == 1){
        header('location: ../admin/dashboard.php');
    }else{
        header('location: ../../index.php');
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
                header('location: ../admin/dashboard.php');
            }else{
              header('location: ../../index.php');
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
<link rel="stylesheet" href="../../layout/css/bootstrap.min.css">
<link rel="stylesheet" href="../../layout/css/login-style.css">

  
</head>
<body class="d-flex flex-column min-vh-100">

  <!-- Header / Navigation tinh chỉnh đẹp mắt -->
   <header>
        <nav class="navbar navbar-expand-lg">
            <div class="container-fluid">
                <a class="navbar-brand" href="../../index.php">
                    <img src="../../images/logo.jpg" alt="">
                    SÁCH VIỆT
                </a>

                <div class="collapse navbar-collapse justify-content-end">
                    <ul class="navbar-nav mb-2 mb-lg-0 align-items-center">
                        <li class="nav-item"><a class="nav-link" href="../../index.php">SÁCH MỚI</a></li>
                        <li class="nav-item"><a class="nav-link" href="#">KHÓA HỌC</a></li>
                        <li class="nav-item"><a class="nav-link" href="../gioithieu/gioithieu.php">GIỚI THIỆU</a></li>
                        <li class="nav-item"><a class="nav-link" href="#">TIN TỨC</a></li>
                        <li class="nav-item"><a class="nav-link" href="#">LIÊN HỆ</a></li>
                        <li class="nav-item ms-3">
                            <a class="btn btn-outline-dark" href="../dangky/signup.php">ĐĂNG KÝ</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>


  
    <main class="py-4">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-12 col-sm-10 col-md-8 col-lg-5 col-xl-4">
          <div class="login-card">
            <h4 class="text-center fw-bold mb-4">
              Đăng Nhập <span style="color: var(--primary-color);">SÁCH VIỆT</span>
            </h4>
            <?php 
            if (!empty($error)){
                echo "<div class='alert alert-danger py-2 text-center'>$error</div>";
            }
             ?>
            <form action="login.php" method="POST" autocomplete="on">
              <div class="mb-3">
                <label for="user" class="form-label fw-semibold">Tên tài khoản</label>
                <input type="text" class="form-control" id="user" name="user" placeholder="Ví dụ: Nguyễn Văn A" required autofocus>
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

              <div class="login-link">
                Bạn chưa có tài khoản? <a href="../dangky/signup.php">Đăng ký ngay</a>
            </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </main>


  <div class="footer mt-auto py-3 text-center border-top">
      <a href="#" class="text-decoration-none mx-2 text-muted">Trợ giúp</a>
      <a href="#" class="text-decoration-none mx-2 text-muted">Điều khoản</a>
      <span class="text-muted mx-2">Bản quyền © 2026 SÁCH VIỆT</span>
  </div>


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