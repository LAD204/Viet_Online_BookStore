<?php

class sanpham
{
    private function connect()
    {
        $con = mysqli_connect(
            'localhost',
            'usersachonline',
            'sachonline123456',
            'bookstore_db'
        );

        if (!$con) {
            echo '<script>alert("Lấy thông tin thất bại");</script>';
            exit();
        }

        mysqli_query($con, 'SET NAMES UTF8');

        return $con;
    }

    // Lấy thông tin chi tiết sản phẩm
    public function chitietsanpham($id)
    {
        $con = $this->connect();

        $id = intval($id);

        $sql = "SELECT * FROM sanpham WHERE id = $id";

        $result = mysqli_query($con, $sql);

        return $result;
    }

    // Thêm sản phẩm
public function postsanpham($ten_sach, $gia_ban, $so_luong, $mo_ta, $nam_xuat_ban, $hinh_anh)
{
    $con = $this->connect();

    $ten_sach = mysqli_real_escape_string($con, $ten_sach);
    $gia_ban = mysqli_real_escape_string($con, $gia_ban);
    $so_luong = mysqli_real_escape_string($con, $so_luong);
    $mo_ta = mysqli_real_escape_string($con, $mo_ta);
    $nam_xuat_ban = mysqli_real_escape_string($con, $nam_xuat_ban);
    $hinh_anh = mysqli_real_escape_string($con, $hinh_anh);

    $sql = "INSERT INTO sanpham
            (ten_sach, gia_ban, so_luong, mo_ta, nam_xuat_ban, hinh_anh)
            VALUES
            ('$ten_sach', '$gia_ban', '$so_luong', '$mo_ta', '$nam_xuat_ban', '$hinh_anh')";

    $result = mysqli_query($con, $sql);

    return $result;
}
}
?>
