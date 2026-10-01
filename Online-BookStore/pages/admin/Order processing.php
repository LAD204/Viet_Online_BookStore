<?php
session_start();
include_once("../../class/clslogin.php");
include_once("../../class/clsdonhang.php");

// 1. Kiểm tra quyền Admin (role = 1)
if (!isset($_SESSION['role']) || $_SESSION['role'] != 1) {
    header('location: ../dangnhap/login.php');
    exit();
}

$dh = new donhang();

// 3. Lấy giá trị lọc từ Form (GET)
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

  <style>
    :root {
      --primary-color: #1e4276;
      --primary-hover: #153056;
      --accent-color: #2b589a;
      --bg-color: #f6f8fa;
      --card-border: #e1e4e8;
    }

    body {
      background-color: var(--bg-color);
      font-family: system-ui, -apple-system, "Segoe UI", Roboto, sans-serif;
      color: #333;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }

    .admin-navbar {
      background-color: #ffffff;
      border-bottom: 1px solid var(--card-border);
      box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }

    .brand-title {
      font-weight: 700;
      color: var(--primary-color);
      letter-spacing: 0.5px;
    }

    .brand-logo {
      height: 36px;
      width: auto;
      object-fit: contain;
    }

    .nav-link-custom {
      color: #555;
      font-weight: 600;
      padding: 8px 14px;
      border-radius: 6px;
      transition: all 0.2s;
      font-size: 0.95rem;
    }

    .nav-link-custom:hover {
      color: var(--primary-color);
      background-color: rgba(30, 66, 118, 0.05);
    }

    .nav-link-custom.active {
      color: #ffffff !important;
      background-color: var(--primary-color) !important;
    }

    .user-badge {
      background-color: rgba(30, 66, 118, 0.08);
      color: var(--primary-color);
      font-weight: 600;
      font-size: 0.9rem;
      padding: 6px 12px;
      border-radius: 20px;
      border: 1px solid rgba(30, 66, 118, 0.15);
    }

    .admin-card {
      background: #ffffff;
      border: 1px solid var(--card-border);
      border-radius: 8px;
      padding: 24px;
      box-shadow: 0 4px 15px rgba(0,0,0,0.02);
    }

    .admin-avatar {
      width: 30px;
      height: 30px;
      border-radius: 50%;
    }

    .btn-brand {
      background-color: var(--primary-color);
      color: #ffffff;
      font-weight: 600;
      border: none;
    }

    .btn-brand:hover {
      background-color: var(--primary-hover);
      color: #ffffff;
    }

    .form-control:focus, .form-select:focus {
      border-color: var(--primary-color);
      box-shadow: 0 0 0 3px rgba(30, 66, 118, 0.15);
    }

    .btn-action {
      padding: 3px 10px;
      font-size: 0.85rem;
    }
  </style>
</head>
<body>

  <!-- Admin Header Navigation -->
  <nav class="navbar navbar-expand-lg admin-navbar py-2 mb-4">
    <div class="container-fluid px-4">
      
      <!-- Góc Trái: Logo hình ảnh và Tên Admin -->
      <div class="d-flex align-items-center gap-3">
        <a class="navbar-brand d-flex align-items-center gap-2 m-0" href="dashboard.php">
          <img src="../../images/logo.jpg" alt="BookStore Logo" class="brand-logo">
          <span class="brand-title fs-4 lh-1">BOOKSTORE ADMIN</span>
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