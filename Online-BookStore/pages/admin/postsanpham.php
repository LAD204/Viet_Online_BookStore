<?php
include_once("../../class/clssanpham.php");

$p = new sanpham();

if (isset($_POST['btnThem'])) {

    $ten_sach = $_POST['ten_sach'];
    $gia_ban = $_POST['gia_ban'];
    $so_luong = $_POST['so_luong'];
    $mo_ta = $_POST['mo_ta'];
    $nam_xuat_ban = $_POST['nam_xuat_ban'];

    // Lấy tên hình ảnh
    $hinh_anh = $_FILES['hinh_anh']['name'];
    $tmp_name = $_FILES['hinh_anh']['tmp_name'];

    // Kiểm tra có hình ảnh hay không
    if ($hinh_anh != '') {

        // Đưa hình ảnh vào thư mục images
        move_uploaded_file(
            $tmp_name,
            "../../images/" . $hinh_anh
        );

        // Thêm sản phẩm vào database
        $result = $p->postsanpham(
            $ten_sach,
            $gia_ban,
            $so_luong,
            $mo_ta,
            $nam_xuat_ban,
            $hinh_anh
        );

        // Thông báo kết quả
        if ($result) {

            echo '<script>
                    alert("Đăng sản phẩm thành công!");
                  </script>';

        } else {

            echo '<script>
                    alert("Đăng sản phẩm thất bại!");
                  </script>';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>

    <meta charset="UTF-8">

    <title>Đăng sản phẩm</title>

    <link rel="stylesheet"
          href="../../layout/css/bootstrap.min.css">

</head>

<body>

<div class="container mt-5">

    <h2 class="text-center mb-4">
        ĐĂNG SẢN PHẨM
    </h2>

    <form method="post"
          enctype="multipart/form-data">

        <!-- Tên sách -->
        <div class="mb-3">

            <label class="form-label">
                Tên sách
            </label>

            <input type="text"
                   name="ten_sach"
                   class="form-control"
                   required>

        </div>


        <!-- Giá bán -->
        <div class="mb-3">

            <label class="form-label">
                Giá bán
            </label>

            <input type="number"
                   name="gia_ban"
                   class="form-control"
                   required>

        </div>


        <!-- Số lượng -->
        <div class="mb-3">

            <label class="form-label">
                Số lượng
            </label>

            <input type="number"
                   name="so_luong"
                   class="form-control"
                   required>

        </div>


        <!-- Mô tả -->
        <div class="mb-3">

            <label class="form-label">
                Mô tả
            </label>

            <textarea name="mo_ta"
                      class="form-control"
                      rows="4"
                      required></textarea>

        </div>


        <!-- Năm xuất bản -->
        <div class="mb-3">

            <label class="form-label">
                Năm xuất bản
            </label>

            <input type="number"
                   name="nam_xuat_ban"
                   class="form-control"
                   required>

        </div>


        <!-- Hình ảnh -->
        <div class="mb-3">

            <label class="form-label">
                Hình ảnh
            </label>

            <input type="file"
                   name="hinh_anh"
                   class="form-control"
                   accept="image/*"
                   required>

        </div>


        <!-- Nút -->
        <button type="submit"
                name="btnThem"
                class="btn btn-primary">

            Đăng sản phẩm

        </button>


        <a href="dashboard.php"
           class="btn btn-secondary">

            Quay lại

        </a>

    </form>

</div>


<script src="../../layout/js/bootstrap.bundle.min.js"></script>

</body>

</html>