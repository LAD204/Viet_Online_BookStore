<?php
session_start();
include_once("../../class/clssanpham.php");
// include_once("../../class/clsconnect.php"); // Bỏ comment nếu bạn muốn dùng csdl

$p = new sanpham();
// $db = new csdl();
// $db->confirmlogin(); // Gọi hàm kiểm tra đăng nhập ở đây nếu bạn muốn bắt buộc user phải login

// 1. Kiểm tra giỏ hàng, nếu trống thì đá về trang chủ
if (empty($_SESSION['cart'])) {
    echo "<script>alert('Giỏ hàng của bạn đang trống!'); window.location.href='../../index.php';</script>";
    exit();
}

// Tính tổng số lượng hiển thị trên Navbar
$tong_soluong_giohang = 0;
foreach ($_SESSION['cart'] as $id_sp => $qty) {
    $tong_soluong_giohang += $qty;
}

// 2. Xử lý khi người dùng bấm nút "Xác Nhận Đặt Hàng"
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btnDatHang'])) {
    // Lấy thông tin từ form
    $ho_ten = $_POST['ho_ten'];
    $sdt = $_POST['sdt'];
    $dia_chi = $_POST['dia_chi'];
    $ghi_chu = $_POST['ghi_chu'];
    
    // =========================================================================
    // TẠI ĐÂY BẠN SẼ VIẾT CODE INSERT VÀO DATABASE BẢNG "donhang" và "chitietdonhang"
    // Ví dụ:
    // $sql_donhang = "INSERT INTO donhang (ho_ten, sdt, dia_chi, ghi_chu, tong_tien) VALUES (...)";
    // Lấy ID đơn hàng vừa tạo, sau đó dùng foreach($_SESSION['cart']) để INSERT vào bảng "chitietdonhang"
    // =========================================================================

    // Sau khi lưu database thành công thì xóa giỏ hàng
    unset($_SESSION['cart']);

    // Thông báo và chuyển hướng
    echo "<script>
            alert('Đặt hàng thành công! Cảm ơn bạn đã mua sắm tại Sách Việt.');
            window.location.href='../../index.php';
          </script>";
    exit();
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thanh Toán - SÁCH VIỆT</title>
    <link href="../../layout/css/bootstrap.min.css" rel="stylesheet">
    <link href="../../layout/css/index-style.css" rel="stylesheet">
</head>
<body class="bg-light">

<!-- HEADER -->
<header>
    <nav class="navbar navbar-expand bg-white">
        <div class="container-fluid">
            <a class="navbar-brand" href="../../index.php">
                <img src="../../images/logo.jpg" alt="Logo"> SÁCH VIỆT
            </a>
            <ul class="navbar-nav align-items-center ms-auto">
                <li class="nav-item me-3"><a class="nav-link fw-bold text-primary" href="giohang.php">Giỏ hàng (<?= $tong_soluong_giohang ?>)</a></li>
                <li class="nav-item"><a class="nav-link" href="../../index.php">TIẾP TỤC MUA SẮM</a></li>
            </ul>
        </div>
    </nav>
</header>

<div class="container my-5">
    <h3 class="fw-bold mb-4">THANH TOÁN ĐƠN HÀNG</h3>

    <div class="row g-4">
        <!-- CỘT TRÁI: FORM ĐIỀN THÔNG TIN GIAO HÀNG -->
        <div class="col-md-7">
            <div class="card shadow-sm border-0 p-4">
                <h5 class="mb-4 text-primary">Thông tin nhận hàng</h5>
                
                <form action="thanhtoan.php" method="POST">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Họ và tên <span class="text-danger">*</span></label>
                        <input type="text" name="ho_ten" class="form-control" placeholder="Nhập họ và tên người nhận" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Số điện thoại <span class="text-danger">*</span></label>
                        <input type="tel" name="sdt" class="form-control" placeholder="Nhập số điện thoại" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Địa chỉ giao hàng <span class="text-danger">*</span></label>
                        <textarea name="dia_chi" class="form-control" rows="3" placeholder="Nhập chi tiết số nhà, tên đường, phường/xã, quận/huyện..." required></textarea>
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Ghi chú (Tùy chọn)</label>
                        <textarea name="ghi_chu" class="form-control" rows="2" placeholder="Ghi chú thêm cho người giao hàng..."></textarea>
                    </div>

                    <div class="d-grid">
                        <a href="../thanhtoan/thanhtoan.php" class="btn btn-success btn-lg fw-bold">
                            XÁC NHẬN ĐẶT HÀNG
                        </a>
                    </div>
                    <div class="text-center mt-3">
                        <a href="../giohang/giohang.php" class="text-decoration-none text-secondary">« Quay lại giỏ hàng</a>
                    </div>
                </form>
            </div>
        </div>

        <!-- CỘT PHẢI: TÓM TẮT ĐƠN HÀNG -->
        <div class="col-md-5">
            <div class="card shadow-sm border-0 p-4">
                <h5 class="mb-4 text-primary">Đơn hàng của bạn</h5>
                
                <div class="d-flex flex-column gap-3 mb-3">
                    <?php
                    $tong_tien = 0;
                    foreach ($_SESSION['cart'] as $id => $qty):
                        $res = $p->chitietsanpham($id);
                        if ($res && $item = mysqli_fetch_assoc($res)):
                            $gia = $item['gia_ban'];
                            $thanh_tien = $gia * $qty;
                            $tong_tien += $thanh_tien;
                    ?>
                        <!-- Item -->
                        <div class="d-flex justify-content-between align-items-center border-bottom pb-2">
                            <div class="d-flex align-items-center gap-3">
                                <img src="../../images/<?= htmlspecialchars($item['hinh_anh']) ?>" width="45" height="60" style="object-fit: cover;" class="rounded border">
                                <div>
                                    <div class="fw-semibold text-truncate" style="max-width: 200px; font-size: 14px;">
                                        <?= htmlspecialchars($item['ten_sach']) ?>
                                    </div>
                                    <small class="text-muted">SL: <?= $qty ?></small>
                                </div>
                            </div>
                            <div class="fw-bold text-secondary" style="font-size: 15px;">
                                <?= number_format($thanh_tien, 0, ',', '.') ?> đ
                            </div>
                        </div>
                    <?php endif; endforeach; ?>
                </div>

                <!-- Tổng kết tiền -->
                <div class="d-flex justify-content-between mt-4">
                    <span class="fs-5 fw-semibold">Tổng cộng:</span>
                    <span class="fs-4 fw-bold text-danger"><?= number_format($tong_tien, 0, ',', '.') ?> đ</span>
                </div>
                <div class="text-end text-muted mt-1" style="font-size: 13px;">
                    (Đã bao gồm VAT nếu có)
                </div>
            </div>
        </div>
    </div>
</div>

<!-- FOOTER -->
<footer class="footer bg-white border-top mt-5 py-4">
    <div class="container text-center text-muted">
        Copyright © 2026 Sách Việt. Mọi bản quyền được bảo lưu.
    </div>
</footer>

<script src="../../layout/js/bootstrap.bundle.min.js"></script>
</body>
</html>