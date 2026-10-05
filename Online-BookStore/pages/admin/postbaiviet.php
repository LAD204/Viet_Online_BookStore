<?php
session_start();

include_once("../../class/clsposter.php");

$p = new poster();

// Kiểm tra quyền Admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 1) {
    header('location: ../dangnhap/login.php');
    exit();
}

// Khi bấm Post Poster
if (isset($_POST['btnPost'])) {

    // Lấy tên hình ảnh
    $hinh_anh = $_FILES['hinh_anh']['name'];
    $tmp_name = $_FILES['hinh_anh']['tmp_name'];

    if ($hinh_anh != '') {

        // Lưu hình vào thư mục images
        $upload = move_uploaded_file(
            $tmp_name,
            "../../images/" . $hinh_anh
        );

        if ($upload) {

            // Lưu tên hình vào bảng poster
            $result = $p->postposter($hinh_anh);

            if ($result) {

                echo '<script>
                        alert("Post poster thành công!");
                        window.location="postbaiviet.php";
                      </script>';

            } else {

                echo '<script>
                        alert("Post poster thất bại!");
                      </script>';
            }

        } else {

            echo '<script>
                    alert("Không thể upload hình ảnh!");
                  </script>';
        }

    } else {

        echo '<script>
                alert("Vui lòng chọn poster!");
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

    <title>Post Bài Viết - SÁCH VIỆT Admin</title>

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
               class="nav-link-custom text-decoration-none">

                Post sản phẩm

            </a>

            <a href="postbaiviet.php"
               class="nav-link-custom text-decoration-none active">

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

<main class="container-fluid px-4">

    <div class="row justify-content-center">

        <div class="col-12 col-md-8 col-lg-6">

            <div class="admin-card p-4">

                <h4 class="fw-bold mb-4 text-center"
                    style="color: var(--primary-color);">

                    POST BÀI VIẾT

                </h4>


                <form method="post"
                      enctype="multipart/form-data">


                    <!-- CHỌN POSTER -->

                    <div class="mb-4">

                        <label class="form-label fw-semibold">
                            Chọn poster
                        </label>

                        <input type="file"
                               name="hinh_anh"
                               class="form-control"
                               accept="image/*"
                               required>

                        <div class="form-text">
                            Chọn hình ảnh poster muốn đăng lên trang chủ.
                        </div>

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

                            Post Poster

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
```
