<?php
session_start();

include_once("../../class/clssanpham.php");

$p = new sanpham();

// Kiểm tra quyền Admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 1) {
    header('location: ../dangnhap/login.php');
    exit();
}

// Khi bấm Post sản phẩm
if (isset($_POST['btnPost'])) {

    $ten_sach = $_POST['ten_sach'];
    $gia_ban = $_POST['gia_ban'];
    $so_luong = $_POST['so_luong'];
    $mo_ta = $_POST['mo_ta'];
    $nam_xuat_ban = $_POST['nam_xuat_ban'];

    // Lấy hình ảnh
    $hinh_anh = $_FILES['hinh_anh']['name'];
    $tmp_name = $_FILES['hinh_anh']['tmp_name'];

    if ($hinh_anh != '') {

        // Đưa ảnh vào thư mục images
        move_uploaded_file(
            $tmp_name,
            "../../images/" . $hinh_anh
        );

        // Lưu sản phẩm vào database
        $result = $p->postsanpham(
            $ten_sach,
            $gia_ban,
            $so_luong,
            $mo_ta,
            $nam_xuat_ban,
            $hinh_anh
        );

        if ($result) {

            echo '<script>
                    alert("Post sản phẩm thành công!");
                    window.location="../../index.php";
                  </script>';

        } else {

            echo '<script>
                    alert("Post sản phẩm thất bại!");
                  </script>';
        }
    } else {

        echo '<script>
                alert("Vui lòng chọn hình ảnh sản phẩm!");
              </script>';
    }
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Post sản phẩm - SÁCH VIỆT Admin</title>

    <link rel="stylesheet"
          href="../../layout/css/bootstrap.min.css">

    <link rel="stylesheet"
          href="../../layout/css/dashboard-style.css">

</head>

<body>

<!-- ================= HEADER ================= -->

<nav class="navbar navbar-expand-lg admin-navbar py-2 mb-4">

    <div class="container-fluid px-4">

        <!-- Logo -->
        <div class="d-flex align-items-center gap-3">

            <a class="navbar-brand"
               href="dashboard.php">

                <img src="../../images/logo.jpg"
                     alt="Logo">

                <span class="brand-title fs-4 lh-1">
                    SÁCH VIỆT
                </span>

            </a>

            <div class="user-badge d-none d-md-flex align-items-center gap-1">

                <img src="../../images/logoadmin.png"
                     alt="Adminlogo"
                     class="admin-avatar">

                <span>
                    Admin:
                    <strong>
                        <?php echo $_SESSION['user'] ?? 'ADMIN'; ?>
                    </strong>
                </span>

            </div>

        </div>


        <!-- MENU -->

        <div class="d-flex align-items-center gap-1 ms-auto me-3">

            <a href="dashboard.php"
               class="nav-link-custom text-decoration-none">

                Thống kê đơn hàng

            </a>

            <a href="Order processing.php"
               class="nav-link-custom text-decoration-none">

                Xử lý đơn hàng

            </a>

            <a href="sanpham.php"
               class="nav-link-custom text-decoration-none active">

                Post sản phẩm

            </a>

            <a href="baiviet.php"
               class="nav-link-custom text-decoration-none">

                Post bài viết

            </a>

        </div>


        <!-- Đăng xuất -->

        <a href="../dangxuat/logout.php"
           class="btn btn-outline-danger btn-sm rounded-pill px-3 fw-semibold">

            Đăng xuất

        </a>

    </div>

</nav>


<!-- ================= NỘI DUNG ================= -->

<main class="container py-4">

    <div class="row justify-content-center">

        <div class="col-12 col-lg-8">

            <div class="admin-card p-4">

                <h4 class="fw-bold mb-4 text-center"
                    style="color: var(--primary-color);">

                    POST SẢN PHẨM

                </h4>


                <form method="post"
                      enctype="multipart/form-data">


                    <!-- TÊN SÁCH -->

                    <div class="mb-3">

                        <label class="form-label fw-semibold">
                            Tên sách
                        </label>

                        <input type="text"
                               name="ten_sach"
                               class="form-control"
                               placeholder="Nhập tên sách"
                               required>

                    </div>


                    <!-- GIÁ -->

                    <div class="mb-3">

                        <label class="form-label fw-semibold">
                            Giá bán
                        </label>

                        <input type="number"
                               name="gia_ban"
                               class="form-control"
                               placeholder="Nhập giá bán"
                               min="0"
                               required>

                    </div>


                    <!-- SỐ LƯỢNG -->

                    <div class="mb-3">

                        <label class="form-label fw-semibold">
                            Số lượng
                        </label>

                        <input type="number"
                               name="so_luong"
                               class="form-control"
                               placeholder="Nhập số lượng"
                               min="0"
                               required>

                    </div>


                    <!-- MÔ TẢ -->

                    <div class="mb-3">

                        <label class="form-label fw-semibold">
                            Mô tả
                        </label>

                        <textarea name="mo_ta"
                                  class="form-control"
                                  rows="5"
                                  placeholder="Nhập mô tả sản phẩm"
                                  required></textarea>

                    </div>


                    <!-- NĂM XUẤT BẢN -->

                    <div class="mb-3">

                     <label class="form-label fw-semibold">
                      Ngày xuất bản
                    </label>

                     <input type="date"
                     name="nam_xuat_ban"
                     class="form-control"
                      required>

                     </div>


                    <!-- HÌNH ẢNH -->

                    <div class="mb-4">

                        <label class="form-label fw-semibold">
                            Hình ảnh sản phẩm
                        </label>

                        <input type="file"
                               name="hinh_anh"
                               class="form-control"
                               accept="image/*"
                               required>

                    </div>


                    <!-- NÚT -->

                    <div class="d-flex justify-content-between">

                        <a href="dashboard.php"
                           class="btn btn-secondary">

                            Quay lại

                        </a>

                        <button type="submit"
                                name="btnPost"
                                class="btn btn-primary px-4">

                            Post sản phẩm

                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

</main>


<!-- ================= FOOTER ================= -->

<footer class="text-center py-3 text-muted small border-top bg-white mt-auto">

    Bản quyền &copy; 2026 BOOKSTORE.VN

</footer>


<script src="../../layout/js/bootstrap.bundle.min.js"></script>

</body>
</html>