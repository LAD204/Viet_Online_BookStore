<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tạo Tài Khoản SÁCH VIỆT</title>
    <!-- Thêm Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Thêm FontAwesome cho icon con mắt -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body {
            background-color: #f4f6f9; /* Màu nền xám nhạt */
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-image: url('https://www.transparenttextures.com/patterns/cubes.png'); /* Pattern nền mờ giống trong ảnh */
        }
        
        /* Navbar styling */
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

        /* Form styling */
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
        
        /* Chỉnh sửa container cho icon mật khẩu */
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

        /* Footer styling */
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

    <!-- Header / Navbar -->
    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid">
            <!-- Bạn có thể thay đường dẫn ảnh logo thật của bạn vào phần src -->
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

    <!-- Main Registration Form -->
    <div class="container">
        <div class="register-container">
            <h3 class="register-title">Tạo Tài Khoản <span>SÁCH VIỆT</span></h3>
            
            <form action="#" method="POST">
                <!-- Họ và Tên -->
                <div class="mb-3">
                    <label class="form-label">Họ và Tên</label>
                    <input type="text" class="form-control" placeholder="Ví dụ: Nguyễn Văn A" required>
                </div>

                <!-- Địa chỉ Gmail -->
                <div class="mb-3">
                    <label class="form-label">Địa chỉ Gmail</label>
                    <input type="email" class="form-control" placeholder="Ví dụ: username@gmail.com" required>
                </div>

                <!-- Số điện thoại -->
                <div class="mb-3">
                    <label class="form-label">Số điện thoại</label>
                    <input type="tel" class="form-control" placeholder="Ví dụ: 090xxxxxxx" required>
                </div>

                <!-- Giới tính -->
                <div class="mb-3">
                    <label class="form-label">Giới tính</label>
                    <select class="form-select" required>
                        <option value="" selected disabled>Chọn giới tính...</option>
                        <option value="nam">Nam</option>
                        <option value="nu">Nữ</option>
                        <option value="khac">Khác</option>
                    </select>
                </div>

                <!-- Mật khẩu -->
                <div class="mb-3">
                    <label class="form-label">Mật khẩu</label>
                    <div class="password-container">
                        <input type="password" class="form-control" placeholder="Nhập mật khẩu" required id="password">
                        <i class="fa-solid fa-eye-slash password-icon" onclick="togglePassword('password', this)"></i>
                    </div>
                </div>

                <!-- Xác nhận mật khẩu -->
                <div class="mb-4">
                    <label class="form-label">Xác nhận mật khẩu</label>
                    <div class="password-container">
                        <input type="password" class="form-control" placeholder="Nhập lại mật khẩu" required id="confirm-password">
                        <i class="fa-solid fa-eye-slash password-icon" onclick="togglePassword('confirm-password', this)"></i>
                    </div>
                </div>

                <!-- Nút Đăng ký -->
                <button type="submit" class="btn btn-register">Đăng ký</button>
            </form>

            <!-- Link Đăng nhập -->
            <div class="login-link">
                Bạn đã có tài khoản? <a href="#">Đăng nhập ngay</a>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        <a href="#">Trợ giúp</a>
        <a href="#">Điều khoản</a>
        <span>Bản quyền © 2024 SACHVIET.VN</span>
    </div>

    <!-- Script ẩn/hiện mật khẩu -->
    <script>
        function togglePassword(inputId, iconElement) {
            const input = document.getElementById(inputId);
            if (input.type === "password") {
                input.type = "text";
                iconElement.classList.remove("fa-eye-slash");
                iconElement.classList.add("fa-eye");
            } else {
                input.type = "password";
                iconElement.classList.remove("fa-eye");
                iconElement.classList.add("fa-eye-slash");
            }
        }
    </script>
    
    <!-- Thêm Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.css"></script>
</body>
</html>