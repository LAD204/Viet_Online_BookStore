<?php
session_start(); // Bắt buộc phải có để đếm số lượng giỏ hàng trên Navbar
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

// Hàm tính tổng số lượng sách đang có trong SESSION giỏ hàng để hiển thị lên Navbar
$tong_soluong_giohang = 0;
if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $id_sp => $qty) {
        $tong_soluong_giohang += $qty;
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
                <img src="../../images/logo.jpg" alt=""> SÁCH VIỆT
            </a>
            <ul class="navbar-nav align-items-center ms-auto">
                <li class="nav-item d-none d-lg-block"><a class="nav-link" href="../../index.php">SÁCH MỚI</a></li>
                <li class="nav-item d-none d-lg-block"><a class="nav-link" href="#">KHÓA HỌC</a></li>
                <li class="nav-item d-none d-lg-block"><a class="nav-link" href="#">GIỚI THIỆU</a></li>
                <li class="nav-item d-none d-lg-block"><a class="nav-link" href="#">TIN TỨC</a></li>
                <li class="nav-item d-none d-lg-block"><a class="nav-link" href="#">LIÊN HỆ</a></li>
                <li class="nav-item ms-3">
                    <a class="btn btn-outline-dark" href="../dangnhap/login.php">ĐĂNG NHẬP</a>
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
                <li class="nav-item"><a class="nav-link active" href="../../index.php">Trang chủ</a></li>
                <li class="nav-item dropdown d-none d-md-block">
                    <a class="nav-link dropdown-toggle active" href="#" data-bs-toggle="dropdown">Danh mục sách</a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#">Lập trình & IT</a></li>
                        <li><a class="dropdown-item" href="#">Kinh tế</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="#">Sách bán chạy</a></li>
                    </ul>
                </li>
                <li class="nav-item flex-grow-1 mx-2">
                    <form class="d-flex gap-2">
                        <input class="form-control" type="search" placeholder="Tìm kiếm...">
                        <input type="submit" value="Tìm kiếm" style="padding:6px; background-color:white; border-radius:5px;">
                    </form>
                </li>
                <li class="nav-item">
                    <!-- Cập nhật số lượng giỏ hàng thực tế -->
                    <a class="nav-link active" href="../giohang/giohang.php">
                        Giỏ hàng (<?php echo $tong_soluong_giohang; ?>)
                    </a>
                </li>
                <li class="nav-item ms-2 d-none d-lg-block">
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
                        <a class="btn btn-outline-light btn-dang-nhap" href="#">
                            <i class="bi bi-person"></i> Chưa đăng nhập
                        </a>
                    <?php endif; ?>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container mt-5 mb-5">
        <?php if ($sanpham) { ?>
            <div class="card shadow">
                <div class="card-header text-center">
                    <h3>XEM CHI TIẾT SẢN PHẨM</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- HÌNH SẢN PHẨM -->
                        <div class="col-md-5 text-center">
                            <img src="../../images/<?php echo htmlspecialchars($sanpham['hinh_anh']); ?>" width="300" height="350" style="object-fit: contain;" alt="<?php echo htmlspecialchars($sanpham['ten_sach']); ?>">
                        </div>

                        <!-- THÔNG TIN -->
                        <div class="col-md-7">
                            <h2><?php echo htmlspecialchars($sanpham['ten_sach']); ?></h2>
                            <hr>
                            <p><strong>Tên sản phẩm:</strong> <?php echo htmlspecialchars($sanpham['ten_sach']); ?></p>
                            <p><strong>Mô tả:</strong> <?php echo htmlspecialchars($sanpham['mo_ta']); ?></p>
                            <p><strong>Giá:</strong> <span class="text-danger fw-bold"><?php echo number_format($sanpham['gia_ban'], 0, ',', '.'); ?> VNĐ</span></p>
                            <p><strong>Năm xuất bản:</strong> <?php echo htmlspecialchars($sanpham['nam_xuat_ban']); ?></p>
                            
                            <p><strong>Số lượng trong kho:</strong> <?php echo $sanpham['so_luong']; ?></p>

                            <!-- BẮT ĐẦU FORM GỬI THÔNG TIN GIỎ HÀNG -->
                            <form action="../giohang/giohang.php" method="POST">
                                <!-- Truyền action và ID sản phẩm ẩn đi -->
                                <input type="hidden" name="action" value="add">
                                <input type="hidden" name="id" value="<?php echo $sanpham['id']; ?>">

                                <!-- SỐ LƯỢNG MUA -->
                                <div class="mt-4">
                                    <label class="form-label">Số lượng mua</label>
                                    <input type="number" name="so_luong" value="1" min="1" max="<?php echo $sanpham['so_luong']; ?>" class="form-control" style="width:100px;">
                                </div>

                                <!-- NÚT -->
                                <div class="mt-4">
                                    <button type="submit" class="btn btn-primary">Thêm Vào Giỏ Hàng</button>
                                    <a href="../../index.php" class="btn btn-secondary">Mua sản phẩm khác</a>
                                </div>
                            </form>
                            <!-- KẾT THÚC FORM -->

                        </div>
                    </div>
                </div>
            </div>
        <?php } else { ?>
            <div class="alert alert-danger text-center">Không tìm thấy sản phẩm!</div>
        <?php } ?>
    </div>
</div>

<!-- FOOTER ... (Phần Footer của bạn giữ nguyên) -->
<footer class="footer">
    <div class="container-fluid px-5">
        <div class="row g-4">
            <div class="col-12 col-md-6 col-lg-3">
                <h5 class="footer-title">Giới thiệu</h5>
                <p class="footer-text">Sách Việt là nhà sách trực tuyến mang đến sách hay...</p>
            </div>
            <!-- ... Các cột khác ... -->
        </div>
    </div>
    <div class="footer-bottom text-center">Copyright © 2026 Sách Việt.</div>
</footer>
<script src="../../layout/js/bootstrap.bundle.min.js"></script>
</body>
</html>