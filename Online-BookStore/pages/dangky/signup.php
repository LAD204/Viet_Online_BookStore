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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body {
            background-color: #f4f6f9; 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-image: url('https://www.transparenttextures.com/patterns/cubes.png');
        }
        
        .navbar {
            background-color: white;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            padding: 15px 50px;
        }
        .navbar-brand {
            display: flex;
            align-items: center;
            font-weight: bold;
            color: #1a568c;
            font-size: 1.5rem;
        }
        .navbar-brand img {
            width: 40px;
            margin-right: 10px;
        }
        .nav-link {
            color: #333;
            font-weight: 600;
            font-size: 0.9rem;
            margin: 0 10px;
            text-transform: uppercase;
        }
        .btn-outline-dark {
            border-radius: 20px;
            font-weight: 600;
            padding: 6px 20px;
        }

        .register-container {
            max-width: 500px;
            margin: 40px auto;
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .register-title {
            text-align: center;
            font-weight: 700;
            color: #111;
            margin-bottom: 30px;
        }
        .register-title span {
            color: #1a568c;
        }
        
        .form-label {
            font-weight: 600;
            font-size: 0.9rem;
            color: #333;
            margin-bottom: 5px;
        }
        .form-control, .form-select {
            border-radius: 6px;
            padding: 10px 15px;
            font-size: 0.95rem;
            border: 1px solid #ced4da;
        }
        .form-control:focus, .form-select:focus {
            box-shadow: none;
            border-color: #1a568c;
        }
        
        .password-container {
            position: relative;
        }
        .password-icon {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #6c757d;
            cursor: pointer;
        }

        .btn-register {
            background-color: #154c79;
            color: white;
            border: none;
            border-radius: 8px;
            padding: 12px;
            font-weight: 600;
            width: 100%;
            margin-top: 15px;
        }
        .btn-register:hover {
            background-color: #0f3656;
            color: white;
        }
        
        .login-link {
            text-align: center;
            margin-top: 20px;
            font-size: 0.9rem;
        }
        .login-link a {
            color: #1a568c;
            text-decoration: none;
            font-weight: 600;
        }

        .footer {
            text-align: center;
            margin-top: 40px;
            padding-bottom: 20px;
            font-size: 0.85rem;
            color: #666;
        }
        .footer a {
            color: #666;
            text-decoration: none;
            margin: 0 10px;
        }
        .footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">
                <i class="fas fa-book-open" style="color: #4da6ff; margin-right: 8px;"></i>
                SÁCH VIỆT<span style="font-size: 0.8rem; vertical-align: super;">.VN</span>
            </a>
            
            <div class="collapse navbar-collapse justify-content-end">
                <ul class="navbar-nav mb-2 mb-lg-0 align-items-center">
                    <li class="nav-item"><a class="nav-link" href="#">SÁCH MỚI</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">KHÓA HỌC</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">GIỚI THIỆU</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">TIN TỨC</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">LIÊN HỆ</a></li>
                    <li class="nav-item ms-3">
                        <a class="btn btn-outline-dark" href="#">ĐĂNG NHẬP</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

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
                Bạn đã có tài khoản? <a href="#">Đăng nhập ngay</a>
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
    <script>
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.css"></script>
</body>

</html>