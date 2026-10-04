<?php

include_once("../../class/clssanpham.php");

$p = new sanpham();

$sanpham = null;

if (isset($_GET['id'])) {

    $id = base64_decode($_GET['id']);

    if ($id !== false && is_numeric($id)) {

        $id = intval($id);

        $result = $p->chitietsanpham($id);

        if ($result) {
            $sanpham = mysqli_fetch_assoc($result);
        }
    }
}

?>

<!DOCTYPE html>
<html lang="vi">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Chi tiết sản phẩm - SÁCH VIỆT</title>

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

            <ul class="navbar-nav align-items-center ms-auto">

                <li class="nav-item d-none d-lg-block">
                    <a class="nav-link" href="../../index.php">
                        SÁCH MỚI
                    </a>
                </li>

                <li class="nav-item d-none d-lg-block">
                    <a class="nav-link" href="#">
                        KHÓA HỌC
                    </a>
                </li>

                <li class="nav-item d-none d-lg-block">
                    <a class="nav-link" href="#">
                        GIỚI THIỆU
                    </a>
                </li>

                <li class="nav-item d-none d-lg-block">
                    <a class="nav-link" href="#">
                        TIN TỨC
                    </a>
                </li>

                <li class="nav-item d-none d-lg-block">
                    <a class="nav-link" href="#">
                        LIÊN HỆ
                    </a>
                </li>

                <li class="nav-item ms-3">

                    <a
                        class="btn btn-outline-dark"
                        href="../dangnhap/login.php"
                    >
                        ĐĂNG NHẬP
                    </a>

                </li>

            </ul>

        </div>

    </nav>

</header>


<!-- MENU CHÍNH -->

<div class="content">

    <nav class="navbar navbar-expand navbar-dark bg-primary menu-chinh">

        <div class="container-fluid">

            <ul class="navbar-nav align-items-center w-100">

                <li class="nav-item">

                    <a
                        class="nav-link active"
                        href="../../index.php"
                    >
                        Trang chủ
                    </a>

                </li>

                <li class="nav-item dropdown d-none d-md-block">

                    <a
                        class="nav-link dropdown-toggle active"
                        href="#"
                        data-bs-toggle="dropdown"
                    >
                        Danh mục sách
                    </a>

                    <ul class="dropdown-menu">

                        <li>
                            <a class="dropdown-item" href="#">
                                Lập trình & IT
                            </a>
                        </li>

                        <li>
                            <a class="dropdown-item" href="#">
                                Kinh tế
                            </a>
                        </li>

                        <li>
                            <hr class="dropdown-divider">
                        </li>

                        <li>
                            <a class="dropdown-item" href="#">
                                Sách bán chạy
                            </a>
                        </li>

                    </ul>

                </li>

                <li class="nav-item flex-grow-1 mx-2">

                    <form class="d-flex gap-2">

                        <input
                            class="form-control"
                            type="search"
                            placeholder="Tìm kiếm..."
                        >

                        <input
                            type="submit"
                            value="Tìm kiếm"
                            style="padding:6px; background-color:white; border-radius:5px;"
                        >

                    </form>

                </li>

                <li class="nav-item">

                    <a class="nav-link active" href="#">

                        Giỏ hàng (0)

                    </a>

                </li>

            </ul>

        </div>

    </nav>


    <!-- CHI TIẾT SẢN PHẨM -->

    <div class="container mt-5 mb-5">

        <?php if ($sanpham) { ?>

            <div class="card shadow">

                <div class="card-header text-center">

                    <h3>
                        XEM CHI TIẾT SẢN PHẨM
                    </h3>

                </div>

                <div class="card-body">

                    <div class="row">

                        <!-- HÌNH SẢN PHẨM -->

                        <div class="col-md-5 text-center">

                            <img
                                src="../../images/<?php echo $sanpham['hinh_anh']; ?>"
                                width="300"
                                height="350"
                                style="object-fit: contain;"
                                alt="<?php echo $sanpham['ten_sach']; ?>"
                            >

                        </div>


                        <!-- THÔNG TIN -->

                        <div class="col-md-7">

                            <h2>
                                <?php echo $sanpham['ten_sach']; ?>
                            </h2>

                            <hr>

                            <p>

                                <strong>
                                    Tên sản phẩm:
                                </strong>

                                <?php echo $sanpham['ten_sach']; ?>

                            </p>

                            <p>

                                <strong>
                                    Mô tả:
                                </strong>

                                <?php echo $sanpham['mo_ta']; ?>

                            </p>

                            <p>

                                <strong>
                                    Giá:
                                </strong>

                                <span class="text-danger fw-bold">

                                    <?php
                                    echo number_format(
                                        $sanpham['gia_ban'],
                                        0,
                                        ',',
                                        '.'
                                    );
                                    ?>

                                    VNĐ

                                </span>

                            </p>

                            <p>

                                <strong>
                                    Số lượng:
                                </strong>

                                <?php echo $sanpham['so_luong']; ?>

                            </p>

                            <p>

                                <strong>
                                    Năm xuất bản:
                                </strong>

                                <?php echo $sanpham['nam_xuat_ban']; ?>

                            </p>


                            <!-- SỐ LƯỢNG MUA -->

                            <div class="mt-4">

                                <label class="form-label">
                                    Số lượng mua
                                </label>

                                <input
                                    type="number"
                                    value="1"
                                    min="1"
                                    max="<?php echo $sanpham['so_luong']; ?>"
                                    class="form-control"
                                    style="width:100px;"
                                >

                            </div>


                            <!-- NÚT -->

                            <div class="mt-4">

                                <button class="btn btn-primary">

                                    Thêm Vào Giỏ Hàng

                                </button>

                                <a
                                    href="../../index.php"
                                    class="btn btn-secondary"
                                >

                                    Mua sản phẩm khác

                                </a>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        <?php } else { ?>

            <div class="alert alert-danger text-center">

                Không tìm thấy sản phẩm!

            </div>

        <?php } ?>

    </div>

</div>


<!-- FOOTER -->

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