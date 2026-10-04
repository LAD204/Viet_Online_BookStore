<?php
    session_start();
    include('../../class/clsconnect.php');
    $p = new csdl();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tạo Tài Khoản SÁCH VIỆT</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../../layout/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../layout/css/signup-style.css">
</head>
<body>

    <header>
        <nav class="navbar navbar-expand-lg">
            <div class="container-fluid">
                <a class="navbar-brand" href="#">
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
                            <a class="btn btn-outline-dark" href="../dangnhap/login.php">ĐĂNG NHẬP</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>
    
    <div class="container">
        <div class="register-container">
            <h3 class="register-title">Tạo Tài Khoản <span>SÁCH VIỆT</span></h3>
            
            <form action="#" method="post" name="formdangky">
                <div class="mb-3">
                    <label class="form-label">Họ và Tên</label>
                    <input type="text" class="form-control" placeholder="Ví dụ: Nguyễn Văn A" name="name" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Địa chỉ Gmail</label>
                    <input type="email" class="form-control" placeholder="Ví dụ: username@gmail.com" name="gmail" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Số điện thoại</label>
                    <input type="tel" class="form-control" placeholder="Ví dụ: 090xxxxxxx" name="telephone" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Giới tính</label>
                    <select class="form-select" name="sex" required>
                        <option value="" selected disabled>Chọn giới tính...</option>
                        <option value="nam">Nam</option>
                        <option value="nu">Nữ</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Mật khẩu</label>
                    <div class="password-container">
                        <input type="password" class="form-control" placeholder="Nhập mật khẩu" name="password" required id="password">
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label">Xác nhận mật khẩu</label>
                    <div class="password-container">
                        <input type="password" class="form-control" placeholder="Nhập lại mật khẩu" name="confirm-password" required id="confirm-password">
                    </div>
                </div>

                <input type="submit" value="Đăng ký" name="dangky" class="btn-register">
            </form>
            <div class="login-link">
                Bạn đã có tài khoản? <a href="../dangnhap/login.php">Đăng nhập ngay</a>
            </div>
        </div>
    </div>

    <div class="footer">
        <a href="#">Trợ giúp</a>
        <a href="#">Điều khoản</a>
        <span>Bản quyền © 2024 SACHVIET.VN</span>
    </div>
    <?php
        switch(isset($_POST['dangky']))
        {
            case 'Đăng ký':{
                if(isset($_POST['name']) && $_POST['name']!=""){
                    $username = $_POST['name'];
                }
                if(isset($_POST['gmail']) && $_POST['gmail']!=""){
                    $gmail = $_POST['gmail'];
                }
                if(isset($_POST['telephone']) && $_POST['telephone']!=""){
                    $telephone = $_POST['telephone'];
                }
                if(isset($_POST['sex']) && $_POST['sex']!=""){
                    $sex = $_POST['sex'];
                }
                if(isset($_POST['password']) && isset($_POST['confirm-password']) && $_POST['password'] === $_POST['confirm-password'] && $_POST['password']!="" && $_POST['confirm-password']!=""){
                    $password = $_POST['password'];
                }
                $ketqua = $p->add_user($username, $password, $sex, $telephone,  $gmail);
                if($ketqua){
                    echo'<script>alert("Tạo tài khoản thành công");</script>';
                }
                else{
                    echo'<script>alert("Tạo tài khoản thất bại");</script>';
                    echo'<script>document.getElementById("formdangky").addEventListener("submit", function(event) {
                        event.preventDefault();
                    });</script>';
                }
                break;
            }
        }
    ?>
    <script src="../../layout/js/bootstrap.bundle.min.js"></script>
</body>

</html>