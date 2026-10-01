<?php 
include("clsbook.php");
class login extends clsbook{
    public function mylogin($user,$pass){
        $link = $this ->connect();

        // Chống SQL Injection cơ bản khi chạy trên host
        $user = mysqli_real_escape_string($link,$user);

        // Kiểm tra đăng nhập bằng username (name)
        $sql = "select id,name,password,role from user 
                    where name = '$user' and password = '$pass' limit 1";

        $result = mysqli_query($link, $sql);
        $num = mysqli_num_rows($result);

        if($num == 1){
            $row = mysqli_fetch_assoc($result);

            $_SESSION['id'] = $row['id'];
            $_SESSION['user'] = $row['name'];
            $_SESSION['pass'] = $row['password'];
            $_SESSION['role'] = (int)$row['role'];
            return 1;
        }else{
            return 0;
        }
    }

    public function confirmlogin($id, $user, $pass, $role = null) {
        $link = $this->connect();
        $id = (int)$id;
                // Chống SQL Injection cơ bản khi chạy trên host
        $user = mysqli_real_escape_string($link, $user);
        $pass = mysqli_real_escape_string($link, $pass);

        $sql = "select id, role from user where id = '$id' and name ='$user' and pass = '$pass' limit 1";

        $result = mysqli_query($link,$sql);
        $num = mysqli_num_rows($result);

        // Nếu thông tin không khớp -> đá về login
        if ($num != 1) {
            header('location: ../dangnhap/login.php');
            exit();
        }

    }

}


?>