<?php 
session_start();
include_once("../../class/clslogin.php");
include_once("../../class/clsdash.php");
$dash = new dash();

// 1. Kiểm tra quyền Admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 1) {
    header('location: ../dangnhap/login.php');
    exit();
}

// 2. Lấy dữ liệu KPI từ CSDL thông qua class dash
$tong_don      = $dash->count_order();
$cho_xacnhan  = $dash->count_order('Chờ xác nhận');
$dang_giao     = $dash->count_order('Đang giao');
$da_hoanthanh = $dash->count_order('Đã hoàn thành');
$da_huy        = $dash->count_order('Đã hủy');

// 3. Lấy doanh thu thực tế 
$doanh_thu = $dash->sum_status('Đã hoàn thành');
?>



<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Thống Kê Đơn Hàng - SÁCH VIỆT Admin</title>

<link rel="stylesheet" href="../../layout/css/bootstrap.min.css">
<link rel="stylesheet" href="../../layout/css/dashboard-style.css">
 
</head>
<body>

  <!-- Admin Header Navigation -->
  <nav class="navbar navbar-expand-lg admin-navbar py-2 mb-4">
    <div class="container-fluid px-4">
      
      <!-- Góc Trái: Logo hình ảnh mới + Tên Admin -->
      <div class="d-flex align-items-center gap-3">
        <a class="navbar-brand" href="dashboard.php">
          <img src="../../images/logo.jpg" alt="">
          <span class="brand-title fs-4 lh-1">SÁCH VIỆT</span>      
          </a>

        <div class="user-badge d-none d-md-flex align-items-center gap-1">
          <img src="../../images/logoadmin.png" alt="Adminlogo" class="admin-avatar">
          <span>Admin: <strong><?php echo $_SESSION['user'] ?? 'ADMIN' ;?></strong></span>
        </div>
      </div>

      <!-- Menu Điều Hướng Admin -->
      <div class="d-flex align-items-center gap-1 ms-auto me-3">
        <a href="dashboard.php" class="nav-link-custom text-decoration-none active">Thống kê đơn hàng</a>
        <a href="Order processing.php" class="nav-link-custom text-decoration-none">Xử lý đơn hàng</a>
        <a href="sanpham.php" class="nav-link-custom text-decoration-none">Post sản phẩm</a>
        <a href="baiviet.php" class="nav-link-custom text-decoration-none">Post bài viết</a> 
      </div>

      <!-- Nút Đăng xuất -->
      <a href="../dangxuat/logout.php" class="btn btn-outline-danger btn-sm rounded-pill px-3 fw-semibold">Đăng xuất</a>
    </div>
  </nav>

  <!-- Main Content -->
  <main class="container-fluid px-4 flex-grow-1">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h4 class="fw-bold mb-0" style="color: var(--primary-color);">Báo Cáo & Thống Kê Tổng Quan</h4>
    </div>

    <!-- Hàng Thẻ KPI Thông Số Đơn Hàng -->
    <div class="row g-3 mb-4">
      <div class="col-12 col-sm-6 col-md-4 col-xl-2.4">
        <div class="stat-card border-start border-primary border-4">
          <div class="stat-title">TỔNG ĐƠN HÀNG</div>
          <div class="stat-number text-primary"><?php echo $tong_don ;?></div>
        </div>
      </div>
      <div class="col-12 col-sm-6 col-md-4 col-xl-2.4">
        <div class="stat-card border-start border-warning border-4">
          <div class="stat-title">CHỜ XỬ LÝ</div>
          <div class="stat-number text-warning"><?php echo $cho_xacnhan ;?></div>
        </div>
      </div>
      <div class="col-12 col-sm-6 col-md-4 col-xl-2.4">
        <div class="stat-card border-start border-info border-4">
          <div class="stat-title">ĐANG GIAO HÀNG</div>
          <div class="stat-number text-info"><?php echo $dang_giao ;?></div>
        </div>
      </div>
      <div class="col-12 col-sm-6 col-md-4 col-xl-2.4">
        <div class="stat-card border-start border-success border-4">
          <div class="stat-title">ĐÃ HOÀN THÀNH</div>
          <div class="stat-number text-success"><?php echo $da_hoanthanh ;?></div>
        </div>
      </div>
      <div class="col-12 col-sm-6 col-md-4 col-xl-2.4">
        <div class="stat-card border-start border-danger border-4">
          <div class="stat-title">ĐƠN ĐÃ HỦY</div>
          <div class="stat-number text-danger"><?php echo $da_huy ;?></div>
        </div>
      </div>
    </div>

    <!-- Hàng Khối Doanh Thu & Biểu Đồ Tròn Trạng Thái Đơn Hàng -->
    <div class="row g-4 mb-4">
      <div class="col-12 col-lg-5 col-xl-4">
        <div class="admin-card h-100 p-3">
          <h6 class="fw-bold mb-3 text-center" style="color: var(--primary-color);">Tỷ Lệ Trạng Thái Đơn Hàng</h6>
          <div style="position: relative; height:240px; width:100%; display: flex; justify-content: center;">
            <canvas id="orderStatusChart"></canvas>
          </div>
          <div class="text-center mt-3 text-muted small">
            Biểu đồ trực quan hóa tỷ lệ phần trăm theo từng trạng thái đơn hàng.
          </div>
        </div>
      </div>

      <div class="col-12 col-lg-7 col-xl-8">
        <div class="admin-card h-100 p-3 d-flex flex-column justify-content-between">
          <div>
            <div class="d-flex justify-content-between align-items-center mb-3">
              <h6 class="fw-bold mb-0" style="color: var(--primary-color);">Chi Tiết Tỷ Lệ & Doanh Thu</h6>
              <span class="badge bg-success fs-6 p-2">Tổng Doanh Thu: <?php echo number_format($doanh_thu, 0, ',', '.'); ?>đ</span>
            </div>

            <table class="table table-bordered align-middle mb-0">
              <thead class="table-light">
                <tr>
                  <th>Trạng Thái Đơn Hàng</th>
                  <th class="text-center">Số Lượng</th>
                  <th class="text-center">Tỷ Lệ %</th>
                  <th class="text-end">Giá Trị Dự Kiến</th>
                </tr>
              </thead>
              <tbody>
                <?php $dash -> dashboard(); ?>
              </tbody>
            </table>
          </div>

          <div class="mt-3 text-end">
            <a href="Order processing.php" class="btn btn-sm btn-primary fw-semibold" style="background-color: var(--primary-color); border: none;">Đi tới trang Xử lý đơn hàng &rarr;</a>
          </div>
        </div>
      </div>

    </div>
  </main>

  <footer class="text-center py-3 text-muted small border-top bg-white mt-auto">
    Bản quyền &copy; 2026 BOOKSTORE.VN
  </footer>

  <script src="../../layout/js/bootstrap.bundle.min.js"></script>
  <!-- Nhúng file Char.js -->
  <script src="../../layout/js/chart.js"></script>

  <script>
    const ctx = document.getElementById('orderStatusChart').getContext('2d');
    new Chart(ctx, {
      type: 'doughnut',
      data: {
        labels: ['Chờ xác nhận', 'Đang giao', 'Đã hoàn thành', 'Đã hủy'],
        datasets: [{
          data: <?php echo json_encode([$cho_xacnhan, $dang_giao, $da_hoanthanh, $da_huy]); ?>,
          backgroundColor: ['#ffc107', '#0dcaf0', '#198754', '#dc3545'],
          borderWidth: 2,
          borderColor: '#ffffff'
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            position: 'bottom',
            labels: { boxWidth: 12, font: { size: 12 } }
          }
        }
      }
    });
  </script>
</body>
</html>