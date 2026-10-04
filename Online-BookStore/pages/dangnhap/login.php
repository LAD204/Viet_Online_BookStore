<?php
// login.php
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
<body>

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
                        <li class="nav-item"><a class="nav-link" href="#">GIỚI THIỆU</a></li>
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


  <main class="flex-grow-1 d-flex align-items-center py-5">
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

   <footer class="footer">
    <div class="container-fluid px-5">
        <div class="row g-4">

            <div class="col-12 col-md-6 col-lg-3">
                <h5 class="footer-title">Giới thiệu</h5>
                <p class="footer-text">
                    Sách Việt là nhà sách trực tuyến mang đến sách hay, sách mới và các khóa học
                    chất lượng cho độc giả Việt Nam. Chúng tôi luôn nỗ lực lan tỏa văn hóa đọc
                    và đồng hành cùng bạn trên hành trình học tập mỗi ngày.
                </p>

                <p class="footer-text fw-semibold mb-1">Đăng ký Hộ Kinh Doanh số 12345678, cấp bởi UBND Q. Gò Vấp</p>
                <p class="footer-text fw-semibold mb-1">Mã số thuế: 8077675962-001</p>
                <p class="footer-text fw-semibold mb-1">Địa chỉ: Số 4 Nguyễn Văn Bảo, Phường 1, Quận Gò Vấp, TP. HCM</p>
                <p class="footer-text fw-semibold">Làm việc từ: 8h đến 23h</p>

                <a href="http://online.gov.vn/Home/WebDetails/XXXXX" target="_blank" rel="noopener">
                    <img class="footer-bct"
                         src="../../images/DnT1595939596727.png"
                         alt="Đã thông báo Bộ Công Thương">
                </a>
            </div>

            <div class="col-12 col-md-6 col-lg-3">
                <h5 class="footer-title">Liên kết</h5>
                <ul class="list-unstyled mb-0">
                    <li class="mb-2"><a class="footer-link" href="#">Facebook Sách Việt</a></li>
                    <li class="mb-2"><a class="footer-link" href="#">Instagram Sách Việt</a></li>
                    <li class="mb-2"><a class="footer-link" href="#">Chính sách bảo mật</a></li>
                    <li class="mb-2"><a class="footer-link" href="#">Điều khoản dịch vụ</a></li>
                    <li class="mb-2"><a class="footer-link" href="#">Chính sách bảo hành đổi trả &amp; hoàn tiền</a></li>
                    <li class="mb-2"><a class="footer-link" href="#">Chính sách mua hàng</a></li>
                    <li class="mb-2"><a class="footer-link" href="#">Chính sách giao hàng</a></li>
                    <li class="mb-2"><a class="footer-link" href="#">Về chúng tôi</a></li>
                </ul>
            </div>

            <div class="col-12 col-md-6 col-lg-3">
                <h5 class="footer-title">Thông tin liên hệ</h5>
                <ul class="list-unstyled mb-0">
                    <li class="footer-text d-flex mb-3">
                        <i class="bi bi-geo-alt-fill footer-icon me-2"></i>
                        <span>Số 4 Nguyễn Văn Bảo, Phường 1, Quận Gò Vấp, TP. HCM</span>
                    </li>
                    <li class="footer-text d-flex mb-3">
                        <i class="bi bi-telephone-fill footer-icon me-2"></i>
                        <a class="footer-link" href="tel:[số điện thoại]">18001122</a>
                    </li>
                    <li class="footer-text d-flex mb-3">
                        <i class="bi bi-envelope-fill footer-icon me-2"></i>
                        <a class="footer-link" href="mailto:[email]">SachViet@gmail.com</a>
                    </li>
                </ul>
            </div>

            <div class="col-12 col-md-6 col-lg-3">
                <h5 class="footer-title">Gặp gỡ Sách Việt ở</h5>
                <div class="d-flex flex-wrap gap-2">
                    <a class="footer-social" href="#"><i class="bi bi-facebook me-1"></i> Facebook</a>
                    <a class="footer-social" href="#"><i class="bi bi-twitter-x me-1"></i> Twitter</a>
                    <a class="footer-social" href="#"><i class="bi bi-google me-1"></i> Google</a>
                    <a class="footer-social" href="#"><i class="bi bi-instagram me-1"></i> Instagram</a>
                </div>
            </div>

        </div>
    </div>

    <div class="footer-bottom text-center">
        Bản quyền © <?= date('Y') ?> SACHVIET.VN
    </div>
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