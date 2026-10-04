<?php
session_start();
include_once("../../class/clssanpham.php");
include_once("../../class/clsbook.php");

$p = new sanpham();
$db = new clsbook();

// Bắt buộc đăng nhập để lấy thông tin user_id hợp lệ
if (!isset($_SESSION['id'])) {
    header("Location: ../dangnhap/login.php");
    exit();
}

$user_id = intval($_SESSION['id']);
$conn = $db->connect();
$thongbao = '';
$is_success = false;
$payment_method = '';
$donhang_id = 0;
$tong_tien_luu = 0;

// 1. Lấy thông tin user hiện tại
$sql_user = "SELECT * FROM user WHERE id = $user_id LIMIT 1";
$res_user = mysqli_query($conn, $sql_user);
$user_info = mysqli_fetch_assoc($res_user);

// 2. Lấy địa chỉ gần nhất nếu có
$sql_dc_old = "SELECT * FROM diachi WHERE user_id = $user_id ORDER BY id DESC LIMIT 1";
$res_dc_old = mysqli_query($conn, $sql_dc_old);
$old_address = ($res_dc_old && mysqli_num_rows($res_dc_old) > 0) ? mysqli_fetch_assoc($res_dc_old)['dia_chi'] : '';

// 3. Tính tổng tiền giỏ hàng hiện tại
$tong_tien = 0;
if (!empty($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $id => $qty) {
        $res = $p->chitietsanpham($id);
        if ($res && $item = mysqli_fetch_assoc($res)) {
            $tong_tien += $item['gia_ban'] * $qty;
        }
    }
}

// 4. Xử lý đặt hàng
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btn_dathang'])) {
    $dia_chi = trim($_POST['dia_chi'] ?? '');
    $phuong_thuc = trim($_POST['phuong_thuc'] ?? 'Thanh toán khi nhận hàng (COD)');

    if (empty($_SESSION['cart'])) {
        $thongbao = 'Giỏ hàng của bạn đang trống!';
    } elseif ($dia_chi !== '') {
        $dia_chi_safe = mysqli_real_escape_string($conn, $dia_chi);
        $phuong_thuc_safe = mysqli_real_escape_string($conn, $phuong_thuc);
        $tong_tien_luu = $tong_tien;
        $payment_method = $phuong_thuc;

        // Chèn bảng diachi
        $sql_ins_dc = "INSERT INTO diachi (user_id, dia_chi) VALUES ($user_id, '$dia_chi_safe')";
        mysqli_query($conn, $sql_ins_dc);
        $dia_chi_id = mysqli_insert_id($conn);

        // Chèn bảng giohang
        $sql_ins_gh = "INSERT INTO giohang (user_id, ngay_tao) VALUES ($user_id, CURDATE())";
        mysqli_query($conn, $sql_ins_gh);
        $giohang_id = mysqli_insert_id($conn);

        // Chèn bảng donhang
        $sql_ins_dh = "INSERT INTO donhang (user_id, ngay_dat, trang_thai, tong_tien, dia_chi_id) 
                       VALUES ($user_id, CURDATE(), 'Chờ xác nhận', $tong_tien_luu, $dia_chi_id)";
        if (mysqli_query($conn, $sql_ins_dh)) {
            $donhang_id = mysqli_insert_id($conn);

            // Chèn bảng chitietgiohang & giảm tồn kho
            foreach ($_SESSION['cart'] as $sanpham_id => $so_luong) {
                $sanpham_id = intval($sanpham_id);
                $so_luong = intval($so_luong);
                $sql_ins_ct = "INSERT INTO chitietgiohang (giohang_id, donhang_id, sanpham_id, so_luong) 
                               VALUES ($giohang_id, $donhang_id, $sanpham_id, $so_luong)";
                mysqli_query($conn, $sql_ins_ct);

                mysqli_query($conn, "UPDATE sanpham SET so_luong = GREATEST(0, so_luong - $so_luong) WHERE id = $sanpham_id");
            }

            // Ghi nhận bảng thanhtoan
            $ma_gd = 'GD' . date('ymd') . rand(1000, 9999);
            $trang_thai_tt = ($phuong_thuc === 'Chuyển khoản Ngân hàng') ? 'Chờ thanh toán' : 'Chưa thanh toán';
            $sql_ins_tt = "INSERT INTO thanhtoan (donhang_id, phuong_thuc, so_tien, trang_thai, ngay_thanh_toan, ma_giao_dich) 
                           VALUES ($donhang_id, '$phuong_thuc_safe', $tong_tien_luu, '$trang_thai_tt', CURDATE(), '$ma_gd')";
            mysqli_query($conn, $sql_ins_tt);

            // Xóa sạch giỏ hàng trong session sau khi đã lưu DB thành công
            unset($_SESSION['cart']);
            $is_success = true;
        } else {
            $thongbao = 'Lỗi tạo đơn hàng: ' . mysqli_error($conn);
        }
    } else {
        $thongbao = 'Vui lòng nhập đầy đủ địa chỉ giao hàng!';
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thanh toán đơn hàng - SÁCH VIỆT</title>
    <link href="../../layout/css/bootstrap.min.css" rel="stylesheet">
    <link href="../../layout/css/index-style.css" rel="stylesheet">
    <style>
        .checkout-title {
            font-size: 1.8rem;
            font-weight: 700;
        }
        .form-label {
            font-size: 1.1rem;
            font-weight: 600;
        }
        .form-control, .form-select {
            font-size: 1.1rem;
            padding: 12px 16px;
        }
        .qr-card {
            background-color: #f8faff;
            border: 2px dashed #0d6efd;
            border-radius: 12px;
            padding: 25px;
        }
    </style>
</head>
<body>
<header>
    <nav class="navbar navbar-expand">
        <div class="container-fluid px-5">
            <a class="navbar-brand fs-3" href="../../index.php">
                <img src="../../images/logo.jpg" alt="Logo" width="45"> SÁCH VIỆT
            </a>
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="btn btn-outline-secondary fs-6" href="../../index.php">Trang chủ</a>
                </li>
            </ul>
        </div>
    </nav>
</header>

<div class="container my-5" style="max-width: 1100px;">
    <?php if ($is_success): ?>
        <?php if ($payment_method === 'Chuyển khoản Ngân hàng'): ?>
            <!-- GIAO DIỆN HIỂN THỊ MÃ QR KHI CHỌN THANH TOÁN ONLINE -->
            <div class="card shadow border-0 p-4 p-md-5 my-4">
                <div class="text-center mb-4">
                    <h2 class="text-primary fw-bold mb-2">QUÉT MÃ QR ĐỂ HOÀN TẤT THANH TOÁN</h2>
                    <p class="text-muted fs-5">Đơn hàng <strong class="text-danger">#DH<?= $donhang_id ?></strong> đã được khởi tạo. Vui lòng chuyển khoản để đơn hàng được xử lý sớm nhất.</p>
                </div>

                <div class="row align-items-center justify-content-center g-4">
                    <!-- Ảnh QR Code -->
                    <div class="col-md-5 text-center">
                        <div class="p-3 bg-white border rounded shadow-sm d-inline-block">
                            <img src="https://api.vietqr.io/image/970422-0901234567-compact2.jpg?amount=<?= $tong_tien_luu ?>&addInfo=DH<?= $donhang_id ?>%20SACHVIET" 
                                 alt="Mã QR Chuyển Khoản" 
                                 class="img-fluid rounded" 
                                 style="max-width: 280px;">
                        </div>
                        <div class="small text-muted mt-2">Mở app Ngân hàng hoặc Ví MoMo để quét</div>
                    </div>

                    <!-- Thông tin tài khoản -->
                    <div class="col-md-7">
                        <div class="qr-card">
                            <h4 class="fw-bold text-primary mb-3">Thông tin chuyển khoản</h4>
                            <div class="fs-5 mb-2">
                                <span class="text-muted">Ngân hàng:</span> <strong>MBBank (Ngân hàng Quân Đội)</strong>
                            </div>
                            <div class="fs-5 mb-2">
                                <span class="text-muted">Số tài khoản:</span> <strong class="text-danger fs-4">0901234567</strong>
                            </div>
                            <div class="fs-5 mb-2">
                                <span class="text-muted">Chủ tài khoản:</span> <strong>NHA SACH SACH VIET</strong>
                            </div>
                            <div class="fs-5 mb-2">
                                <span class="text-muted">Số tiền cần thanh toán:</span> 
                                <strong class="text-danger fs-3"><?= number_format($tong_tien_luu, 0, ',', '.') ?> đ</strong>
                            </div>
                            <div class="fs-5 mb-3">
                                <span class="text-muted">Nội dung chuyển khoản:</span> 
                                <span class="badge bg-primary fs-5 px-3 py-2">DH<?= $donhang_id ?> SACHVIET</span>
                            </div>
                            <div class="alert alert-warning mb-0 fs-6">
                                <em>Lưu ý: Giữ nguyên nội dung chuyển khoản để hệ thống tự động nhận diện thanh toán của bạn.</em>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="text-center mt-5">
                    <a href="../../index.php" class="btn btn-primary btn-lg px-5 py-3 fs-5">Tôi đã chuyển khoản xong</a>
                </div>
            </div>
        <?php else: ?>
            <!-- GIAO DIỆN KHI CHỌN THANH TOÁN TIỀN MẶT COD -->
            <div class="card shadow border-0 p-5 text-center my-4">
                <h1 class="text-success fw-bold mb-3">🎉 ĐẶT HÀNG THÀNH CÔNG!</h1>
                <p class="text-muted fs-4">Mã đơn hàng: <strong class="text-primary">#DH<?= $donhang_id ?></strong></p>
                <p class="text-secondary fs-5">Phương thức thanh toán: <strong>Thanh toán khi nhận hàng (COD)</strong></p>
                <p class="text-muted">Đơn hàng của bạn đã được lưu và đang chờ xác nhận để vận chuyển.</p>
                <div class="mt-4">
                    <a href="../../index.php" class="btn btn-primary btn-lg px-5 py-3 fs-5">Tiếp tục mua hàng</a>
                </div>
            </div>
        <?php endif; ?>

    <?php else: ?>
        <!-- FORM ĐIỀN THÔNG TIN THANH TOÁN -->
        <h2 class="checkout-title text-primary mb-4">THÔNG TIN GIAO HÀNG & THANH TOÁN</h2>

        <?php if ($thongbao): ?>
            <div class="alert alert-danger fs-5 py-3"><?= htmlspecialchars($thongbao) ?></div>
        <?php endif; ?>

        <form method="POST" action="thanhtoan.php">
            <div class="row g-4">
                <!-- Cột trái: Thông tin nhận hàng & chọn phương thức -->
                <div class="col-lg-7">
                    <div class="card shadow border-0 p-4 mb-4">
                        <h4 class="fw-bold mb-4 text-primary border-bottom pb-2">1. Thông tin người nhận</h4>
                        
                        <div class="mb-3">
                            <label class="form-label">Họ và tên</label>
                            <input type="text" class="form-control bg-light" value="<?= htmlspecialchars($user_info['name'] ?? '') ?>" readonly>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Số điện thoại</label>
                            <input type="text" class="form-control bg-light" value="<?= htmlspecialchars($user_info['telephone'] ?? '') ?>" readonly>
                        </div>
                        
                        <div class="mb-4">
                            <label class="form-label text-danger">Địa chỉ giao hàng (*)</label>
                            <textarea name="dia_chi" rows="3" class="form-control" placeholder="Nhập số nhà, tên đường, phường/xã, quận/huyện..." required><?= htmlspecialchars($old_address) ?></textarea>
                        </div>

                        <h4 class="fw-bold mb-3 text-primary border-bottom pb-2">2. Phương thức thanh toán</h4>
                        <div class="mb-2">
                            <select name="phuong_thuc" id="select_phuongthuc" class="form-select fs-5">
                                <option value="Thanh toán khi nhận hàng (COD)">Thanh toán khi nhận hàng (COD)</option>
                                <option value="Chuyển khoản Ngân hàng">Chuyển khoản Ngân hàng (Quét mã QR)</option>
                            </select>
                        </div>
                        <small class="text-muted fst-italic">* Mã QR thanh toán sẽ hiển thị sau khi bạn bấm Xác nhận đặt hàng.</small>
                    </div>
                </div>

                <!-- Cột phải: Tóm tắt đơn hàng -->
                <div class="col-lg-5">
                    <div class="card shadow border-0 p-4 sticky-top" style="top: 20px;">
                        <h4 class="fw-bold mb-3 border-bottom pb-2">Tóm tắt đơn hàng</h4>
                        
                        <div class="table-responsive mb-3" style="max-height: 350px;">
                            <table class="table align-middle">
                                <tbody>
                                    <?php if (!empty($_SESSION['cart'])): ?>
                                        <?php foreach ($_SESSION['cart'] as $id => $qty): 
                                            $res = $p->chitietsanpham($id);
                                            if ($res && $item = mysqli_fetch_assoc($res)): ?>
                                                <tr>
                                                    <td style="width: 70px;">
                                                        <div style="width: 60px; height: 60px;" class="d-flex align-items-center justify-content-center bg-light rounded border p-1">
                                                            <img src="../../images/<?= htmlspecialchars($item['hinh_anh']) ?>" 
                                                                 alt="<?= htmlspecialchars($item['ten_sach']) ?>"
                                                                 style="max-width: 100%; max-height: 100%; object-fit: contain;" 
                                                                 onerror="this.src='../../images/logo.jpg'">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="fw-bold fs-6"><?= htmlspecialchars($item['ten_sach']) ?></div>
                                                        <small class="text-muted fs-6">SL: x<?= $qty ?></small>
                                                    </td>
                                                    <td class="text-end fw-semibold fs-6 text-danger">
                                                        <?= number_format($item['gia_ban'] * $qty, 0, ',', '.') ?> đ
                                                    </td>
                                                </tr>
                                        <?php endif; endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>

                        <div class="border-top pt-3 mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="fs-5 text-muted">Tạm tính:</span>
                                <span class="fs-5 fw-semibold"><?= number_format($tong_tien, 0, ',', '.') ?> đ</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="fs-5 text-muted">Phí vận chuyển:</span>
                                <span class="fs-5 text-success fw-semibold">Miễn phí</span>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="fs-4 fw-bold">Tổng thanh toán:</span>
                                <span class="text-danger fw-bold fs-3"><?= number_format($tong_tien, 0, ',', '.') ?> đ</span>
                            </div>
                        </div>

                        <button type="submit" name="btn_dathang" class="btn btn-danger btn-lg w-100 py-3 fs-4 fw-bold shadow-sm">
                            XÁC NHẬN ĐẶT HÀNG
                        </button>
                    </div>
                </div>
            </div>
        </form>
    <?php endif; ?>
</div>

<script src="../../layout/js/bootstrap.bundle.min.js"></script>
</body>
</html>