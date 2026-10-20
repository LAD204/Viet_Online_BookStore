<?php
session_start();

// Kiểm tra quyền truy cập
if (!isset($_SESSION['role']) || $_SESSION['role'] != 1) {
    header('Location: ../dangnhap/login.php');
    exit();
}

// Kết nối DB
$conn = @mysqli_connect('localhost', 'usersachonline', 'sachonline123456', 'bookstore_db');
if (!$conn) {
    die('Không thể kết nối database. Vui lòng kiểm tra lại cấu hình.');
}
mysqli_set_charset($conn, 'utf8mb4');

// Hàm an toàn hiển thị dữ liệu
function e($value)
{
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

// Hàm upload ảnh
function uploadAnh($file)
{
    if (!isset($file) || $file['error'] === UPLOAD_ERR_NO_FILE) {
        return null;
    }
    if ($file['error'] !== UPLOAD_ERR_OK || $file['size'] > 5 * 1024 * 1024) {
        return false;
    }
    $thongTinAnh = @getimagesize($file['tmp_name']);
    if ($thongTinAnh === false) {
        return false;
    }
    $loaiAnh = [
        IMAGETYPE_JPEG => 'jpg',
        IMAGETYPE_PNG => 'png',
        IMAGETYPE_GIF => 'gif',
        IMAGETYPE_WEBP => 'webp'
    ];
    $loai = $thongTinAnh[2];
    if (!isset($loaiAnh[$loai])) {
        return false;
    }
    $tenAnh = bin2hex(random_bytes(12)) . '.' . $loaiAnh[$loai];
    $thuMucAnh = __DIR__ . '/../../images/';
    if (!is_dir($thuMucAnh)) {
        return false;
    }
    if (!move_uploaded_file($file['tmp_name'], $thuMucAnh . $tenAnh)) {
        return false;
    }
    return $tenAnh;
}

/* TOKEN BẢO VỆ FORM */
if (empty($_SESSION['token_quanly'])) {
    $_SESSION['token_quanly'] = bin2hex(random_bytes(32));
}

/* XÁC ĐỊNH KHÓA CHÍNH BẢNG POSTER */
$khoaPoster = null;
$kiemTraPoster = mysqli_query($conn, "SHOW COLUMNS FROM poster");
if ($kiemTraPoster) {
    while ($cot = mysqli_fetch_assoc($kiemTraPoster)) {
        if ($cot['Key'] === 'PRI') {
            $khoaPoster = $cot['Field'];
            break;
        }
    }
}

/* XỬ LÝ CÁC THAO TÁC POST */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['token']) || !hash_equals($_SESSION['token_quanly'], $_POST['token'])) {
        die('Yêu cầu không hợp lệ. Hãy tải lại trang.');
    }

    $hanhDong = $_POST['hanhdong'] ?? '';
    $thongBao = '';
    $stmt = false;

    /* SỬA SẢN PHẨM */
    if ($hanhDong === 'sua_sanpham') {
        $id = (int)($_POST['id'] ?? 0);
        $ten = trim($_POST['ten_sach'] ?? '');
        $gia = (float)($_POST['gia_ban'] ?? 0);
        $soLuong = (int)($_POST['so_luong'] ?? 0);
        $moTa = trim($_POST['mo_ta'] ?? '');
        $nam = trim($_POST['nam_xuat_ban'] ?? '');

        $anh = uploadAnh($_FILES['hinh_anh'] ?? null);

        if ($id <= 0 || $ten === '' || $gia < 0 || $soLuong < 0 || $nam === '') {
            $thongBao = 'Thông tin sản phẩm không hợp lệ.';
        } elseif ($anh === false) {
            $thongBao = 'Ảnh không hợp lệ hoặc tải ảnh thất bại.';
        } else {
            if ($anh === null) {
                $stmt = mysqli_prepare($conn, "UPDATE sanpham SET ten_sach=?, gia_ban=?, so_luong=?, mo_ta=?, nam_xuat_ban=? WHERE id=?");
                if ($stmt) mysqli_stmt_bind_param($stmt, 'sdissi', $ten, $gia, $soLuong, $moTa, $nam, $id);
            } else {
                $stmt = mysqli_prepare($conn, "UPDATE sanpham SET ten_sach=?, gia_ban=?, so_luong=?, mo_ta=?, nam_xuat_ban=?, hinh_anh=? WHERE id=?");
                if ($stmt) mysqli_stmt_bind_param($stmt, 'sdisssi', $ten, $gia, $soLuong, $moTa, $nam, $anh, $id);
            }

            if ($stmt) {
                $thongBao = mysqli_stmt_execute($stmt) ? 'Cập nhật sản phẩm thành công!' : 'Cập nhật sản phẩm thất bại.';
                mysqli_stmt_close($stmt);
            } else {
                $thongBao = 'Lỗi cập nhật CSDL: ' . mysqli_error($conn);
            }
        }
        $_SESSION['thongbao_quanly'] = $thongBao;
        header('Location: quanlysanpham.php?tab=sanpham');
        exit();

    /* XÓA SẢN PHẨM */
    } elseif ($hanhDong === 'xoa_sanpham') {
        $id = (int)($_POST['id'] ?? 0);
        $stmt = mysqli_prepare($conn, "DELETE FROM sanpham WHERE id=?");
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, 'i', $id);
            $thongBao = mysqli_stmt_execute($stmt) ? 'Đã xóa sản phẩm.' : 'Không thể xóa. Sản phẩm có thể đang thuộc về một đơn hàng.';
            mysqli_stmt_close($stmt);
        } else {
            $thongBao = 'Lỗi xóa CSDL: ' . mysqli_error($conn);
        }
        $_SESSION['thongbao_quanly'] = $thongBao;
        header('Location: quanlysanpham.php?tab=sanpham');
        exit();

    /* THÊM POSTER */
    } elseif ($hanhDong === 'them_poster') {
        $anh = uploadAnh($_FILES['hinh_anh'] ?? null);

        if ($anh === false) {
            $thongBao = 'Ảnh không hợp lệ hoặc tải ảnh thất bại. Tối đa 5MB (JPG, PNG, GIF, WEBP).';
        } elseif ($anh === null) {
            $thongBao = 'Vui lòng chọn một ảnh để làm poster.';
        } else {
            $stmt = mysqli_prepare($conn, "INSERT INTO poster (hinh_anh) VALUES (?)");
            if ($stmt) {
                mysqli_stmt_bind_param($stmt, 's', $anh);
                $thongBao = mysqli_stmt_execute($stmt) ? 'Thêm poster mới thành công!' : 'Thêm poster thất bại.';
                mysqli_stmt_close($stmt);
            } else {
                $thongBao = 'Lỗi thêm Poster: ' . mysqli_error($conn);
            }
        }
        $_SESSION['thongbao_quanly'] = $thongBao;
        header('Location: quanlysanpham.php?tab=poster');
        exit();

    /* SỬA POSTER */
    } elseif ($hanhDong === 'sua_poster') {
        if (!$khoaPoster) {
            $thongBao = 'Bảng poster chưa có khóa chính nên không thể sửa an toàn.';
        } else {
            $idPoster = trim($_POST['id_poster'] ?? '');
            $anh = uploadAnh($_FILES['hinh_anh'] ?? null);

            if ($idPoster === '') {
                $thongBao = 'Không tìm thấy poster cần sửa.';
            } elseif ($anh === false) {
                $thongBao = 'Ảnh không hợp lệ hoặc tải ảnh thất bại.';
            } elseif ($anh === null) {
                $thongBao = 'Vui lòng chọn ảnh mới.';
            } else {
                $tenCot = str_replace('`', '', $khoaPoster);
                $stmt = mysqli_prepare($conn, "UPDATE poster SET hinh_anh=? WHERE `$tenCot`=?");
                if ($stmt) {
                    mysqli_stmt_bind_param($stmt, 'ss', $anh,$idPoster);
                    $thongBao = mysqli_stmt_execute($stmt) ? 'Cập nhật poster thành công!' : 'Cập nhật poster thất bại.';
                    mysqli_stmt_close($stmt);
                } else {
                    $thongBao = 'Lỗi cập nhật Poster: ' . mysqli_error($conn);
                }
            }
        }
        $_SESSION['thongbao_quanly'] =$thongBao;
        header('Location: quanlysanpham.php?tab=poster');
        exit();

    /* XÓA POSTER */
    } elseif ($hanhDong === 'xoa_poster') {
        if (!$khoaPoster) {$thongBao = 'Bảng poster chưa có khóa chính nên không thể xóa an toàn.';
        } else {
            $idPoster = trim($_POST['id_poster'] ?? '');$tenCot = str_replace('`', '', $khoaPoster);
            $stmt = mysqli_prepare($conn, "DELETE FROM poster WHERE `$tenCot`=?");
            if ($stmt) {
                mysqli_stmt_bind_param($stmt, 's', $idPoster);
                $thongBao = mysqli_stmt_execute($stmt) ? 'Đã xóa poster.' : 'Xóa poster thất bại.';
                mysqli_stmt_close($stmt);
            } else {
                $thongBao = 'Lỗi xóa Poster: ' . mysqli_error($conn);
            }
        }
        $_SESSION['thongbao_quanly'] = $thongBao;
        header('Location: quanlysanpham.php?tab=poster');
        exit();
    }

    $_SESSION['thongbao_quanly'] = 'Chức năng không hợp lệ.';
    header('Location: quanlysanpham.php');
    exit();
}

/* TAB VÀ TỪ KHÓA GET */
$tabHienTai = $_GET['tab'] ?? 'sanpham';
if (!in_array($tabHienTai, ['sanpham', 'poster'], true)) {
    $tabHienTai = 'sanpham';
}
$tuKhoa = trim($_GET['keyword'] ?? '');
$tuKhoaTim = '%' . $tuKhoa . '%';

/* LẤY DANH SÁCH SẢN PHẨM */
$dsSanPham = false;
$stmtSP = mysqli_prepare($conn, "SELECT * FROM sanpham WHERE CAST(id AS CHAR) LIKE ? OR ten_sach LIKE ? ORDER BY id DESC");
if ($stmtSP) {
    mysqli_stmt_bind_param($stmtSP, 'ss', $tuKhoaTim, $tuKhoaTim);
    mysqli_stmt_execute($stmtSP);
    $dsSanPham = mysqli_stmt_get_result($stmtSP);
}

/* LẤY DANH SÁCH POSTER */
$dsPoster = false;
if ($khoaPoster) {
    $tenCot = str_replace('`', '', $khoaPoster);
    $stmtPoster = mysqli_prepare($conn, "SELECT * FROM poster WHERE CAST(`$tenCot` AS CHAR) LIKE ? OR hinh_anh LIKE ? ORDER BY `$tenCot` DESC");
    if ($stmtPoster) {
        mysqli_stmt_bind_param($stmtPoster, 'ss', $tuKhoaTim,$tuKhoaTim);
        mysqli_stmt_execute($stmtPoster);
        $dsPoster = mysqli_stmt_get_result($stmtPoster);
    }
} else {
    $stmtPoster = mysqli_prepare($conn, "SELECT * FROM poster WHERE hinh_anh LIKE ? ORDER BY hinh_anh DESC");
    if ($stmtPoster) {
        mysqli_stmt_bind_param($stmtPoster, 's',$tuKhoaTim);
        mysqli_stmt_execute($stmtPoster);
        $dsPoster = mysqli_stmt_get_result($stmtPoster);
    }
}

/* THÔNG BÁO TỪ SESSION */
$thongBao =$_SESSION['thongbao_quanly'] ?? '';
unset($_SESSION['thongbao_quanly']);

/* LẤY THÔNG TIN SẢN PHẨM CẦN SỬA */
$suaSanPham = null;
if (isset($_GET['sua_sp'])) {
    $idSua = (int)$_GET['sua_sp'];
    $stmtSua = mysqli_prepare($conn, "SELECT * FROM sanpham WHERE id=?");
    if ($stmtSua) {
        mysqli_stmt_bind_param($stmtSua, 'i',$idSua);
        mysqli_stmt_execute($stmtSua);
        $ketQuaSua = mysqli_stmt_get_result($stmtSua);
        if ($ketQuaSua) {
            $suaSanPham = mysqli_fetch_assoc($ketQuaSua);
        }
        mysqli_stmt_close($stmtSua);
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Quản lý trang chủ - SÁCH VIỆT Admin</title>
    <link rel="stylesheet" href="../../layout/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../layout/css/dashboard-style.css">
    <link rel="stylesheet" href="../../layout/css/Order-style.css">
    <style>
        body { background: #f4f6f9; }
        .admin-card { background: #fff; border-radius: 10px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08); }
        .table { margin-bottom: 0; }
        .table th, .table td { vertical-align: middle; }
        .anh-nho { width: 65px; height: 75px; object-fit: contain; }
        .nav-tabs .nav-link { font-weight: 600; }
        .tieu-de-bang { font-weight: 700; margin-bottom: 16px; }
    </style>
</head>
<body>

    <!-- Admin Header Navigation (ĐÃ CHUẨN HÓA ĐỒNG BỘ) -->
    <nav class="navbar navbar-expand-lg admin-navbar py-2 mb-4">
        <div class="container-fluid px-4">
            
            <!-- Góc Trái: Logo hình ảnh + Tên Admin -->
            <div class="d-flex align-items-center gap-3">
                <a class="navbar-brand" href="dashboard.php">
                    <img src="../../images/logo.jpg" alt="Logo">
                    <span class="brand-title fs-4 lh-1">SÁCH VIỆT</span>      
                </a>

                <div class="user-badge d-none d-md-flex align-items-center gap-1">
                    <img src="../../images/logoadmin.png" alt="Adminlogo" class="admin-avatar">
                    <span>Admin: <strong><?php echo $_SESSION['user'] ?? 'ADMIN'; ?></strong></span>
                </div>
            </div>

            <!-- Menu Điều Hướng Admin -->
            <div class="d-flex align-items-center gap-1 ms-auto me-3">
                <a href="dashboard.php" class="nav-link-custom text-decoration-none">Thống kê đơn hàng</a>
                <a href="Order processing.php" class="nav-link-custom text-decoration-none">Xử lý đơn hàng</a>
                <a href="quanlysanpham.php" class="nav-link-custom text-decoration-none active">Quản lý trang chủ</a>
                <a href="sanpham.php" class="nav-link-custom text-decoration-none">Post sản phẩm</a>
                <a href="postbaiviet.php" class="nav-link-custom text-decoration-none">Post bài viết</a>
            </div>

            <!-- Nút Đăng xuất -->
            <a href="../dangxuat/logout.php" class="btn btn-outline-danger btn-sm rounded-pill px-3 fw-semibold">Đăng xuất</a>
        </div>
    </nav>

    <main class="container-fluid px-4 pb-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="fw-bold mb-0" style="color: var(--primary-color);">QUẢN LÝ TRANG CHỦ</h4>
        </div>

        <?php if ($thongBao !== ''): ?>
            <div class="alert alert-info alert-dismissible fade show py-2 mb-3">
                <?= e($thongBao) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <ul class="nav nav-tabs mb-4">
            <li class="nav-item">
                <a class="nav-link <?= $tabHienTai === 'sanpham' ? 'active' : '' ?>" href="quanlysanpham.php?tab=sanpham">Quản lý sản phẩm</a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= $tabHienTai === 'poster' ? 'active' : '' ?>" href="quanlysanpham.php?tab=poster">Quản lý bài viết / poster</a>
            </li>
        </ul>

        <!-- FORM TÌM KIẾM -->
        <div class="admin-card p-3 mb-4">
            <form action="quanlysanpham.php" method="GET" class="row g-2 align-items-center">
                <input type="hidden" name="tab" value="<?= e($tabHienTai) ?>">
                <div class="col-12 col-md-8">
                    <input type="text" name="keyword" class="form-control" placeholder="<?= $tabHienTai === 'sanpham' ? 'Tìm theo mã sản phẩm, tên sách...' : 'Tìm theo ID hoặc tên file poster...' ?>" value="<?= e($tuKhoa) ?>">
                </div>
                <div class="col-6 col-md-2">
                    <button type="submit" class="btn btn-primary w-100">Tìm kiếm</button>
                </div>
                <div class="col-6 col-md-2">
                    <a href="quanlysanpham.php?tab=<?= e($tabHienTai) ?>" class="btn btn-secondary w-100">Xóa lọc</a>
                </div>
            </form>
        </div>

        <?php if ($tabHienTai === 'sanpham'): ?>
            <!-- FORM SỬA SẢN PHẨM -->
            <?php if ($suaSanPham): ?>
                <section class="admin-card p-3 mb-4">
                    <h5 class="fw-bold mb-3">SỬA SẢN PHẨM</h5>
                    <form method="post" enctype="multipart/form-data">
                        <input type="hidden" name="token" value="<?= e($_SESSION['token_quanly'] ?? '') ?>">
                        <input type="hidden" name="hanhdong" value="sua_sanpham">
                        <input type="hidden" name="id" value="<?= (int)($suaSanPham['id'] ?? 0) ?>">

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Tên sách</label>
                                <input type="text" name="ten_sach" class="form-control" required value="<?= e($suaSanPham['ten_sach'] ?? '') ?>">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Giá bán</label>
                                <input type="number" name="gia_ban" class="form-control" min="0" step="any" required value="<?= e($suaSanPham['gia_ban'] ?? 0) ?>">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Số lượng</label>
                                <input type="number" name="so_luong" class="form-control" min="0" required value="<?= e($suaSanPham['so_luong'] ?? 0) ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Ngày xuất bản</label>
                                <input type="date" name="nam_xuat_ban" class="form-control" required value="<?= e($suaSanPham['nam_xuat_ban'] ?? '') ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Thay hình ảnh (không bắt buộc)</label>
                                <input type="file" name="hinh_anh" class="form-control" accept="image/jpeg,image/png,image/gif,image/webp">
                            </div>
                            <div class="col-12">
                                <label class="form-label">Mô tả</label>
                                <textarea name="mo_ta" class="form-control" rows="3" required><?= e($suaSanPham['mo_ta'] ?? '') ?></textarea>
                            </div>
                            <div class="col-12 d-flex gap-2">
                                <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                                <a href="quanlysanpham.php?tab=sanpham" class="btn btn-secondary">Hủy sửa</a>
                            </div>
                        </div>
                    </form>
                </section>
            <?php endif; ?>

            <section class="admin-card p-3 mb-4">
                <h5 class="tieu-de-bang">DANH SÁCH SẢN PHẨM</h5>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Ảnh</th>
                                <th>Tên sách</th>
                                <th>Giá bán</th>
                                <th>Số lượng</th>
                                <th>Ngày xuất bản</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($dsSanPham && mysqli_num_rows($dsSanPham) > 0): ?>
                                <?php while ($sp = mysqli_fetch_assoc($dsSanPham)): ?>
                                    <tr>
                                        <td><?= (int)($sp['id'] ?? 0) ?></td>
                                        <td>
                                            <img class="anh-nho" src="../../images/<?= rawurlencode(basename((string)($sp['hinh_anh'] ?? ''))) ?>" alt="Ảnh sản phẩm">
                                        </td>
                                        <td><?= e($sp['ten_sach'] ?? '') ?></td>
                                        <td><?= number_format((float)($sp['gia_ban'] ?? 0), 0, ',', '.') ?>đ</td>
                                        <td><?= (int)($sp['so_luong'] ?? 0) ?></td>
                                        <td><?= e($sp['nam_xuat_ban'] ?? '') ?></td>
                                        <td>
                                            <a class="btn btn-primary btn-sm mb-1" href="quanlysanpham.php?tab=sanpham&sua_sp=<?= (int)($sp['id'] ?? 0) ?>&keyword=<?= urlencode($tuKhoa) ?>">Sửa</a>
                                            <form method="post" class="d-inline" onsubmit="return confirm('Bạn chắc chắn muốn xóa sản phẩm này?')">
                                                <input type="hidden" name="token" value="<?= e($_SESSION['token_quanly'] ?? '') ?>">
                                                <input type="hidden" name="hanhdong" value="xoa_sanpham">
                                                <input type="hidden" name="id" value="<?= (int)($sp['id'] ?? 0) ?>">
                                                <button type="submit" class="btn btn-outline-danger btn-sm">Xóa</button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7" class="text-center py-4">Không tìm thấy sản phẩm nào.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </section>

        <?php else: ?>

            <!-- CHỨC NĂNG THÊM POSTER -->
            <section class="admin-card p-3 mb-4 border-start border-success border-4">
                <h5 class="fw-bold mb-3 text-success">THÊM POSTER MỚI</h5>
                <form method="post" enctype="multipart/form-data" class="row g-3 align-items-end">
                    <input type="hidden" name="token" value="<?= e($_SESSION['token_quanly'] ?? '') ?>">
                    <input type="hidden" name="hanhdong" value="them_poster">
                    
                    <div class="col-md-9">
                        <label class="form-label">Chọn hình ảnh tải lên (Tối đa 5MB)</label>
                        <input type="file" name="hinh_anh" class="form-control" accept="image/jpeg,image/png,image/gif,image/webp" required>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-success w-100">
                            Thêm Poster
                        </button>
                    </div>
                </form>
            </section>

            <section class="admin-card p-3 mb-4">
                <h5 class="tieu-de-bang">DANH SÁCH POSTER HIỆN CÓ</h5>
                <?php if (!$khoaPoster): ?>
                    <div class="alert alert-warning">Bảng poster chưa có khóa chính. Cần kiểm tra cấu trúc database để bật chức năng sửa và xóa.</div>
                <?php endif; ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <?php if ($khoaPoster): ?><th>ID</th><?php endif; ?>
                                <th>Hình ảnh</th>
                                <th>Tên file</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($dsPoster && mysqli_num_rows($dsPoster) > 0): ?>
                                <?php while ($posterRow = mysqli_fetch_assoc($dsPoster)): ?>
                                    <tr>
                                        <?php if ($khoaPoster): ?>
                                            <td><?= e($posterRow[$khoaPoster] ?? '') ?></td>
                                        <?php endif; ?>
                                        <td>
                                            <img class="anh-nho" src="../../images/<?= rawurlencode(basename((string)($posterRow['hinh_anh'] ?? ''))) ?>" alt="Poster">
                                        </td>
                                        <td><?= e($posterRow['hinh_anh'] ?? '') ?></td>
                                        <td>
                                            <?php if ($khoaPoster): ?>
                                                <form method="post" enctype="multipart/form-data" class="mb-2">
                                                    <input type="hidden" name="token" value="<?= e($_SESSION['token_quanly'] ?? '') ?>">
                                                    <input type="hidden" name="hanhdong" value="sua_poster">
                                                    <input type="hidden" name="id_poster" value="<?= e($posterRow[$khoaPoster] ?? '') ?>">
                                                    <input type="file" name="hinh_anh" class="form-control form-control-sm mb-1" accept="image/jpeg,image/png,image/gif,image/webp" required>
                                                    <button type="submit" class="btn btn-primary btn-sm">Đổi ảnh</button>
                                                </form>
                                                <form method="post" onsubmit="return confirm('Bạn chắc chắn muốn xóa poster này?')">
                                                    <input type="hidden" name="token" value="<?= e($_SESSION['token_quanly'] ?? '') ?>">
                                                    <input type="hidden" name="hanhdong" value="xoa_poster">
                                                    <input type="hidden" name="id_poster" value="<?= e($posterRow[$khoaPoster] ?? '') ?>">
                                                    <button type="submit" class="btn btn-outline-danger btn-sm">Xóa poster</button>
                                                </form>
                                            <?php else: ?>
                                                <span class="text-muted">Chưa xác định khóa chính</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="<?= $khoaPoster ? 4 : 3 ?>" class="text-center py-4">Không có poster nào trên hệ thống.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </section>

        <?php endif; ?>

    </main>

    <footer class="text-center py-3 text-muted small border-top bg-white mt-4">
        Bản quyền &copy; 2026 BOOKSTORE.VN
    </footer>

    <script src="../../layout/js/bootstrap.bundle.min.js"></script>
</body>
</html>