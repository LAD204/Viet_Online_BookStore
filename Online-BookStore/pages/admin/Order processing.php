<?php
session_start();
include_once("../../class/clslogin.php");
include_once("../../class/clsdonhang.php");


if (!isset($_SESSION['role']) || $_SESSION['role'] != 1) {
    header('location: ../dangnhap/login.php');
    exit();
}

$dh = new donhang();


$keyword = $_GET['keyword'] ?? '';
$status_filter = $_GET['status'] ?? '';

;

$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btn_update_status'])) {
    $donhang_id = $_POST['donhang_id'] ?? 0;
    $trang_thai = $_POST['trang_thai'] ?? '';

    if ($dh->trang_thai($donhang_id, $trang_thai)) {
        $msg = 'Cập nhật trạng thái đơn hàng thành công!';
    } else {
        $msg = 'Cập nhật thất bại!';
    }
}

?>


<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Xử Lý Đơn Hàng - BookStore Admin</title>

  <!-- Bootstrap CSS cục bộ -->
  <link rel="stylesheet" href="../../layout/css/bootstrap.min.css"> 
  <link rel="stylesheet" href="../../layout/css/Order-style.css"> 
 
</head>
<body>

  <!-- Admin Header Navigation -->
  <nav class="navbar navbar-expand-lg admin-navbar py-2 mb-4">
    <div class="container-fluid px-4">
      
      <!-- Góc Trái: Logo hình ảnh và Tên Admin -->
      <div class="d-flex align-items-center gap-3">
         <a class="navbar-brand" href="dashboard.php">
          <img src="../../images/logo.jpg" alt="">
          <span class="brand-title fs-4 lh-1">SÁCH VIỆT</span>      
          </a>

        <div class="user-badge d-none d-md-flex align-items-center gap-1">
          <img src="../../images/logoadmin.png" alt="Adminlogo" class="admin-avatar">
          <span>Admin: <strong><?php echo $_SESSION['user'] ?? 'Admin' ?></strong></span> 
        </div>
      </div>

      <!-- Menu Điều Hướng Admin -->
      <div class="d-flex align-items-center gap-1 ms-auto me-3">
        <a href="dashboard.php" class="nav-link-custom text-decoration-none">Thống kê đơn hàng</a>
        <a href="Order processing.php" class="nav-link-custom text-decoration-none active">Xử lý đơn hàng</a>
        <a href="sanpham.php" class="nav-link-custom text-decoration-none">Post sản phẩm</a>
        <a href="baiviet.php" class="nav-link-custom text-decoration-none">Post bài viết</a>
      </div>

      <!-- Nút Đăng xuất -->
      <a href="logout.php" class="btn btn-outline-danger btn-sm rounded-pill px-3 fw-semibold">Đăng xuất</a>
    </div>
  </nav>

  <!-- Main Content -->
  <main class="container-fluid px-4 flex-grow-1">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h4 class="fw-bold mb-0" style="color: var(--primary-color);">Quản Lý & Xử Lý Đơn Hàng</h4>
    </div>

    <!-- Thông báo trạng thái khi thay đổi trạng thái -->
    <?php 
        if (!empty($msg)) { 
        ?>
          <div class="alert alert-success alert-dismissible fade show py-2 mb-3" role="alert">
            <?= $msg ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
          </div>
    <?php 
} 
?>

    <!-- Form Tìm Kiếm & Lọc  -->
    <div class="admin-card p-3 mb-4">
      <form action="Order processing.php" method="GET" class="row g-2 align-items-center">
        <div class="col-12 col-md-5">
          <input type="text" name="keyword" class="form-control" placeholder="Tìm theo Mã đơn, Tên khách hàng, SĐT..." value="<?php echo $keyword; ?>">
        </div>
        <div class="col-6 col-md-4">
          <select name="status" class="form-select">
            <option value="">-- Tất cả trạng thái --</option>
            <?php 
              echo $dh ->option_trangthai($status_filter);
            ?>
          </select>
        </div>
        <div class="col-6 col-md-3">
          <button type="submit" class="btn btn-brand w-100">Lọc đơn hàng</button>
        </div>
      </form>
    </div>

    <div class="admin-card p-3 mb-4">
      <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
          <thead class="table-light">
            <tr>
              <th>Mã Đơn</th>
              <th>Khách Hàng</th>
              <th>Số Điện Thoại</th>
              <th>Địa Chỉ Giao</th>
              <th>Tổng Tiền</th>
              <th>Ngày Đặt</th>
              <th>Trạng Thái</th>
              <th class="text-center">Thao Tác Admin</th>
            </tr>
          </thead>
          <tbody>
            <?php $dh->bang_donhang($keyword, $status_filter); ?>
          </tbody>
        </table>
      </div>
    </div>


    
  </main>

  <?php $dh-> ds_modal($keyword, $status_filter); ?>
  
  <footer class="text-center py-3 text-muted small border-top bg-white mt-auto">
    Bản quyền &copy; 2026 BOOKSTORE.VN
  </footer>

  <script src="../../layout/js/bootstrap.bundle.min.js"></script>
</body>
</html>