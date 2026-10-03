<?php
   session_start();
   include('class/clsconnect.php'); 
   $p = new csdl();
   if(isset($_SESSION['id']) && isset($_SESSION['role'])){
        if($_SESSION['role'] == 1){
            header('location: ../admin/dashboard.php');
        }else{
            header('location: ../../index.php');
        }
        exit();
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="layout/css/bootstrap.min.css" rel="stylesheet">
    <link href="layout/css/index-style.css" rel="stylesheet">
</head>
<body>
<header>
    <nav class="navbar navbar-expand">
        <div class="container-fluid">
            <a class="navbar-brand" href="javascript:location.reload();">
                <img src="images/logo.jpg" alt="">
                SÁCH VIỆT
            </a>

            <ul class="navbar-nav align-items-center ms-auto">

                <li class="nav-item d-none d-lg-block"><a class="nav-link" href="index.php">SÁCH MỚI</a></li>
                <li class="nav-item d-none d-lg-block"><a class="nav-link" href="#">KHÓA HỌC</a></li>
                <li class="nav-item d-none d-lg-block"><a class="nav-link" href="#">GIỚI THIỆU</a></li>
                <li class="nav-item d-none d-lg-block"><a class="nav-link" href="#">TIN TỨC</a></li>
                <li class="nav-item d-none d-lg-block"><a class="nav-link" href="#">LIÊN HỆ</a></li>

                <li class="nav-item dropdown d-lg-none">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">MENU</a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="index.ph'">SÁCH MỚI</a></li>
                        <li><a class="dropdown-item" href="#">KHÓA HỌC</a></li>
                        <li><a class="dropdown-item" href="#">GIỚI THIỆU</a></li>
                        <li><a class="dropdown-item" href="#">TIN TỨC</a></li>
                        <li><a class="dropdown-item" href="#">LIÊN HỆ</a></li>
                        <li class="d-md-none"><hr class="dropdown-divider"></li>
                        <li class="d-md-none"><a class="dropdown-item" href="pages/dangnhap/login.php">ĐĂNG NHẬP</a></li>
                    </ul>
                </li>

                <li class="nav-item ms-3 d-none d-md-block">
                    <a class="btn btn-outline-dark" href="pages/dangnhap/login.php">ĐĂNG NHẬP</a>
                </li>
            </ul>
        </div>
    </nav>
</header>

<div class="content">
    <nav class="navbar navbar-expand navbar-dark bg-primary menu-chinh">
        <div class="container-fluid">
            <ul class="navbar-nav align-items-center w-100">

                <li class="nav-item dropdown d-lg-none">
                    <a class="nav-link" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" aria-label="Mở menu">
                        <span class="navbar-toggler-icon"></span>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="javascript:location.reload();">Trang chủ</a></li>

                        <li class="d-md-none"><hr class="dropdown-divider"></li>
                        <li class="d-md-none"><h6 class="dropdown-header">Danh mục sách</h6></li>
                        <li class="d-md-none"><a class="dropdown-item" href="#">Lập trình &amp; IT</a></li>
                        <li class="d-md-none"><a class="dropdown-item" href="#">Kinh tế</a></li>
                        <li class="d-md-none"><a class="dropdown-item" href="#">Sách bán chạy</a></li>
                        <li class="d-md-none"><hr class="dropdown-divider"></li>
                        <li class="d-md-none"><a class="dropdown-item" href="#">Giỏ hàng (0)</a></li>

                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="pages/dangnhap/login.php">Đăng nhập</a></li>
                    </ul>
                </li>

                <li class="nav-item d-none d-lg-block">
                    <a class="nav-link active" aria-current="page" href="javascript:location.reload();">Trang chủ</a>
                </li>

                <li class="nav-item dropdown d-none d-md-block">
                    <a class="nav-link dropdown-toggle active" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Danh mục sách
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#">Lập trình &amp; IT</a></li>
                        <li><a class="dropdown-item" href="#">Kinh tế</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="#">Sách bán chạy</a></li>
                    </ul>
                </li>

                <li class="nav-item flex-grow-1 mx-2">
                    <form class="d-flex align-items-center gap-2 search-form" role="search" action="#" method="get">
                        <input class="form-control" type="search" name="search" placeholder="Tìm kiếm...">
                        <input type="submit" value="Tìm kiếm" name="tim" style="padding:6px; background-color:white; border-radius: 5px;">
                    </form>
                </li>
                <li class="nav-item d-none d-md-block">
                    <a class="nav-link active" href="#">Giỏ hàng (0)</a>
                </li>

                <li class="nav-item ms-2 d-none d-lg-block">
                    <a class="btn btn-outline-light btn-dang-nhap" href="#">
                        <i class="bi bi-person"></i> Đăng nhập
                    </a>
                </li>
            </ul>
        </div>
    </nav>
    <div class="body-content d-flex flex-wrap gap-4 justify-content-center">
        <?php
             if(isset($_GET['tim']) && isset($_GET['search'])){
    
                $ten_tim_kiem = $_GET['search'];
                $p->search($ten_tim_kiem); 
                
            } else {
                $p->export_product('SELECT * FROM sanpham ORDER BY ten_sach ASC');
                
            }
            if(isset($_GET['them'])){
                switch($_GET['nut']){
                    case'Thêm':{
                        //Chỗ này cần bổ sung
                        break;
                    }
                    case'Xem chi tiết':{
                        // Chỗ này cần bổ sung
                        break;
                    }
                }
            }
            
        ?>
    </div>
</div>
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
                         src="images/DnT1595939596727.png"
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
                        <a class="footer-link" href="mailto:[email]">ovuvuevuevueenyetuenwuevueugbemugbemosas@gmail.com</a>
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
        Copyright © 2026 Sách Việt.
    </div>
</footer>
<script src="layout/js/bootstrap.bundle.min.js"></script>
</body>
</html>