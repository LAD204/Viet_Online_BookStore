<?php
session_start();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giới thiệu - SÁCH VIỆT</title>
    <link href="../../layout/css/bootstrap.min.css" rel="stylesheet">
    <link href="../../layout/css/index-style.css" rel="stylesheet">
</head>
<body>

<!-- HEADER -->
<header>
    <nav class="navbar navbar-expand">
        <div class="container-fluid">
            <a class="navbar-brand" href="../../index.php">
                <img src="../../images/logo.jpg" alt="">
                SÁCH VIỆT
            </a>
        </div>
    </nav>
</header>

<!-- MENU CHÍNH -->
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
                        <li><a class="dropdown-item" href="../../index.php">Trang chủ</a></li>
                        <li><a class="dropdown-item" href="../giohang/giohang.php">Giỏ hàng (<?= isset($_SESSION['cart']) ? array_sum($_SESSION['cart']) : 0 ?>)</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <?php if(isset($_SESSION['user'])): ?>
                            <li><a class="dropdown-item text-danger" href="../dangxuat/logout.php">Đăng xuất (<?php echo $_SESSION['user']; ?>)</a></li>
                        <?php else: ?>
                            <li><a class="dropdown-item" href="../dangnhap/login.php">Đăng nhập</a></li>
                        <?php endif; ?>
                    </ul>
                </li>

                <!-- Trang chủ: Hiện ở Tablet và PC, ẩn ở Mobile (d-none d-md-block) -->
                <li class="nav-item d-none d-md-block">
                    <a class="nav-link" aria-current="page" href="../../index.php">Trang chủ</a>
                </li>

                <!-- Danh mục: Hiện ở Tablet và PC, ẩn ở Mobile (d-none d-md-block) -->
                <li class="nav-item dropdown d-none d-md-block">
                    <a class="nav-link dropdown-toggle active" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Danh mục 
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="../../index.php">Sách mới</a></li>
                        <li><a class="dropdown-item" href="gioithieu.php">Giới thiệu</a></li>
                    </ul>
                </li>

                <!-- Thanh tìm kiếm: LUÔN HIỆN -->
                <li class="nav-item flex-grow-1 mx-2">
                    <form class="d-flex align-items-center gap-2 search-form" role="search" action="../../index.php" method="get">
                        <input class="form-control" type="search" name="search" placeholder="Tìm kiếm...">
                        <input type="submit" value="Tìm kiếm" name="tim" style="padding:6px; background-color:white; border-radius: 5px; border: none;">
                    </form>
                </li>

                <!-- Giỏ hàng: Hiện ở Tablet và PC, ẩn ở Mobile (d-none d-md-block) -->
                <li class="nav-item d-none d-md-block">
                    <a class="nav-link active" href="../giohang/giohang.php">
                        Giỏ hàng (<?= isset($_SESSION['cart']) ? array_sum($_SESSION['cart']) : 0 ?>)
                    </a>
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
                                    <a class="dropdown-item text-danger" href="../dangxuat/logout.php">
                                        <i class="bi bi-box-arrow-right"></i> Đăng xuất
                                    </a>
                                </li>
                            </ul>
                        </div>
                    <?php else: ?>
                        <a class="btn btn-outline-light btn-dang-nhap" href="../dangnhap/login.php">
                            <i class="bi bi-person"></i> Đăng nhập
                        </a>
                    <?php endif; ?>
                </li>
            </ul>
        </div>
    </nav>

    <!-- NỘI DUNG GIỚI THIỆU -->
    <div class="container my-5">
        <div class="card shadow border-0 p-4">
            <h2 class="text-primary fw-bold mb-4 text-center">VỀ CHÚNG TÔI - NHÀ SÁCH SÁCH VIỆT</h2>
            <div class="row align-items-center g-4">
                <div class="col-md-6">
                    <h4 class="fw-bold">Sứ mệnh lan tỏa tri thức</h4>
                    <p class="text-muted leading-relaxed">
                        Sách Việt là nhà sách trực tuyến mang đến sách hay, sách mới và các tài liệu chuyên ngành chất lượng cho độc giả. Chúng tôi luôn nỗ lực lan tỏa văn hóa đọc và đồng hành cùng bạn trên hành trình học tập, phát triển bản thân mỗi ngày.
                    </p>
                    <p class="text-muted leading-relaxed">
                        Với phương châm đặt trải nghiệm độc giả lên hàng đầu, Sách Việt cam kết 100% sách chính hãng, đóng gói cẩn thận và hỗ trợ giao hàng tận nơi nhanh chóng trên toàn quốc.
                    </p>
                    <div class="row text-center mt-4 g-2">
                        <div class="col-4">
                            <div class="p-3 bg-light rounded">
                                <h3 class="fw-bold text-primary mb-0">10k+</h3>
                                <small class="text-muted">Đầu sách</small>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="p-3 bg-light rounded">
                                <h3 class="fw-bold text-primary mb-0">50k+</h3>
                                <small class="text-muted">Độc giả</small>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="p-3 bg-light rounded">
                                <h3 class="fw-bold text-primary mb-0">99%</h3>
                                <small class="text-muted">Hài lòng</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 text-center">
                    <img src="../../images/logo.jpg" alt="Sách Việt" class="img-fluid rounded shadow-sm" style="max-height: 320px;" onerror="this.src='../../images/logo.jpg'">
                </div>
            </div>
        </div>
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
                    <li class="mb-2"><a class="footer-link" href="gioithieu.php">Về chúng tôi</a></li>
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

<script src="../../layout/js/bootstrap.bundle.min.js"></script>
</body>
</html>