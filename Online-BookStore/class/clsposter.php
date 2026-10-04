<?php

class poster
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
            echo '<script>alert("Kết nối cơ sở dữ liệu thất bại");</script>';
            exit();
        }

        mysqli_query($con, 'SET NAMES UTF8');

        return $con;
    }

    public function postposter($hinh_anh)
    {
        $con = $this->connect();

        $hinh_anh = mysqli_real_escape_string($con, $hinh_anh);

        $sql = "INSERT INTO poster (hinh_anh)
                VALUES ('$hinh_anh')";

        $result = mysqli_query($con, $sql);

        return $result;
    }
}

?>