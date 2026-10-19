<?php
   session_start();
   include('class/clsconnect.php'); 
   $p = new csdl();
   $tong_soluong_giohang = 0;
    if (isset($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $id_sp => $qty) {
            $tong_soluong_giohang += $qty;
        }
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
            <a class="navbar-brand" href="index.php">
                <img src="images/logo.jpg" alt="">
                SÁCH VIỆT
            </a>
        </div>
    </nav>
</header>

<div class="content">
    <nav class="navbar navbar-expand navbar-dark bg-primary menu-chinh">
        <div class="container-fluid">
            <ul class="navbar-nav align-items-center w-100">

                <!-- Nút 3 gạch (Mobile): Chỉ hiện ở Mobile, ẩn ở Tablet và PC (d-md-none) -->
                <li class="nav-item dropdown d-md-none">
                    <a class="nav-link" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" aria-label="Mở menu">
                        <span class="navbar-toggler-icon"></span>
                    </a>
                    <!-- Nội dung của nút 3 gạch -->
                    <ul class="dropdown-menu shadow">
                        <li><a class="dropdown-item" href="index.php">Trang chủ</a></li>
                        <li><a class="dropdown-item" href="pages/giohang/giohang.php">Giỏ hàng (<?= $tong_soluong_giohang ?>)</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <?php if(isset($_SESSION['user'])): ?>
                            <li><a class="dropdown-item text-danger" href="pages/dangxuat/logout.php">Đăng xuất (<?php echo $_SESSION['user']; ?>)</a></li>
                        <?php else: ?>
                            <li><a class="dropdown-item" href="pages/dangnhap/login.php">Đăng nhập</a></li>
                        <?php endif; ?>
                    </ul>
                </li>

                <!-- Trang chủ: Hiện ở Tablet và PC, ẩn ở Mobile (d-none d-md-block) -->
                <li class="nav-item d-none d-md-block">
                    <a class="nav-link active" aria-current="page" href="index.php">Trang chủ</a>
                </li>

                <!-- Danh mục: Hiện ở Tablet và PC, ẩn ở Mobile (d-none d-md-block) -->
                <li class="nav-item dropdown d-none d-md-block">
                    <a class="nav-link dropdown-toggle active" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Danh mục 
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="index.php">Sách mới</a></li>
                        <li><a class="dropdown-item" href="pages/gioithieu/gioithieu.php">Giới thiệu</a></li>
                    </ul>
                </li>

                <!-- Thanh tìm kiếm: LUÔN HIỆN -->
                <li class="nav-item flex-grow-1 mx-2">
                    <form class="d-flex align-items-center gap-2 search-form" role="search" action="#" method="get">
                        <input class="form-control" type="search" name="search" placeholder="Tìm kiếm...">
                        <input type="submit" value="Tìm kiếm" name="tim" style="padding:6px; background-color:white; border-radius: 5px; border: none;">
                    </form>
                </li>

                <!-- Giỏ hàng: Hiện ở Tablet và PC, ẩn ở Mobile (d-none d-md-block) -->
                <li class="nav-item d-none d-md-block">
                    <a class="nav-link active" href="pages/giohang/giohang.php">Giỏ hàng (<?= $tong_soluong_giohang ?>)</a>
                </li>

                <!-- Đăng nhập/Đăng xuất: Hiện ở Tablet và PC, ẩn ở Mobile (d-none d-md-block) -->
                <li class="nav-item ms-2 d-none d-md-block">
                    <?php if(isset($_SESSION['user'])): ?>
                        <div class="dropdown">
                            <button class="btn btn-outline-light dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-person-check-fill"></i> <?php echo $_SESSION['user']; ?>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                                <li>
                                    <a class="dropdown-item text-danger" href="pages/dangxuat/logout.php">
                                        <i class="bi bi-box-arrow-right"></i> Đăng xuất
                                    </a>
                                </li>
                            </ul>
                        </div>
                    <?php else: ?>
                        <a class="btn btn-outline-light btn-dang-nhap" href="pages/dangnhap/login.php">
                            <i class="bi bi-person"></i> Đăng nhập
                        </a>
                    <?php endif; ?>
                </li>
            </ul>
        </div>
    </nav>
    
    <div id="bannerTinTuc" class="carousel slide shadow-sm mb-4 rounded overflow-hidden"
            data-bs-ride="carousel"
            data-bs-interval="3000"
            style="width:80%; margin:0 auto; margin-top:20px;">

        <div class="carousel-inner">

            <?php
                $p->poster('SELECT * FROM poster');
            ?>

        </div>

        <button class="carousel-control-prev"
                type="button"
                data-bs-target="#bannerTinTuc"
                data-bs-slide="prev">

            <span class="carousel-control-prev-icon bg-dark rounded-circle p-3 bg-opacity-75"
                aria-hidden="true">
            </span>

            <span class="visually-hidden">Poster trước</span>
        </button>
        <button class="carousel-control-next"
                type="button"
                data-bs-target="#bannerTinTuc"
                data-bs-slide="next">

            <span class="carousel-control-next-icon bg-dark rounded-circle p-3 bg-opacity-75"
                aria-hidden="true">
            </span>

            <span class="visually-hidden">Poster sau</span>
        </button>
    </div>
    <div class="body-content d-flex flex-wrap gap-4 justify-content-center">
        <?php
             if(isset($_GET['tim']) && isset($_GET['search'])){
    
                $ten_tim_kiem = $_GET['search'];
                $p->search($ten_tim_kiem); 
                
            } else {
                $p->export_product('SELECT * FROM sanpham ORDER BY ten_sach ASC');
                
            }
        ?>
    </div>
</div>
    <footer class="footer mt-5">
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
                         alt="Đã thông báo Bộ Công Thương" style="max-width: 150px;">
                </a>
            </div>

            <div class="col-12 col-md-6 col-lg-3">
                <h5 class="footer-title">Liên kết</h5>
                <ul class="list-unstyled mb-0">
                    <li class="mb-2"><a class="footer-link text-decoration-none" href="#">Facebook Sách Việt</a></li>
                    <li class="mb-2"><a class="footer-link text-decoration-none" href="#">Instagram Sách Việt</a></li>
                    <li class="mb-2"><a class="footer-link text-decoration-none" href="#">Chính sách bảo mật</a></li>
                    <li class="mb-2"><a class="footer-link text-decoration-none" href="#">Điều khoản dịch vụ</a></li>
                    <li class="mb-2"><a class="footer-link text-decoration-none" href="#">Chính sách bảo hành đổi trả &amp; hoàn tiền</a></li>
                    <li class="mb-2"><a class="footer-link text-decoration-none" href="#">Chính sách mua hàng</a></li>
                    <li class="mb-2"><a class="footer-link text-decoration-none" href="#">Chính sách giao hàng</a></li>
                    <li class="mb-2"><a class="footer-link text-decoration-none" href="#">Về chúng tôi</a></li>
                </ul>
            </div>

            <div class="col-12 col-md-6 col-lg-3">
                <h5 class="footer-title">Thông tin liên hệ</h5>
                <ul class="list-unstyled mb-0">
                    <li class="footer-text d-flex mb-3">
                        <i class="bi bi-geo-alt-fill footer-icon me-2">📍</i>
                        <span>Số 4 Nguyễn Văn Bảo, Phường 1, Quận Gò Vấp, TP. HCM</span>
                    </li>
                    <li class="footer-text d-flex mb-3">
                        <i class="bi bi-telephone-fill footer-icon me-2">📞</i>
                        <a class="footer-link text-decoration-none" href="tel:[số điện thoại]">18001122</a>
                    </li>
                    <li class="footer-text d-flex mb-3">
                        <i class="bi bi-envelope-fill footer-icon me-2">✉️</i>
                        <a class="footer-link text-decoration-none" href="mailto:[email]">ovuvuevuevueenyetuenwuevueugbemugbemosas@gmail.com</a>
                    </li>
                </ul>
            </div>

            <div class="col-12 col-md-6 col-lg-3">
                <h5 class="footer-title">Gặp gỡ Sách Việt ở</h5>
                <div class="d-flex flex-wrap gap-2">
                    <a class="footer-social text-decoration-none" href="#"><i class="bi bi-facebook me-1"></i> Facebook</a>
                    <a class="footer-social text-decoration-none" href="#"><i class="bi bi-twitter-x me-1"></i> Twitter</a>
                    <a class="footer-social text-decoration-none" href="#"><i class="bi bi-google me-1"></i> Google</a>
                    <a class="footer-social text-decoration-none" href="#"><i class="bi bi-instagram me-1"></i> Instagram</a>
                </div>
            </div>

        </div>
    </div>

    <div class="footer-bottom text-center mt-3 pt-3 border-top">
        Copyright © 2026 Sách Việt.
    </div>
</footer>
<script src="layout/js/bootstrap.bundle.min.js"></script>
</body>
</html>