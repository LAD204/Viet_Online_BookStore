<?php 
include("clsbook.php");
class login extends clsbook{
<<<<<<< HEAD
    public function mylogin($user,$pass){
        $link = $this->connect();
=======
    public function mylogin($user, $pass){
    $link = $this->connect();
>>>>>>> cc25674f09b4d273ecc9b03fa638300c1ef75a91

    // Chống SQL Injection cho username
    $user = mysqli_real_escape_string($link, $user);

    // Chỉ tìm tài khoản dựa trên username
    $sql = "SELECT id, name, password, role FROM user WHERE name = '$user' LIMIT 1";

    $result = mysqli_query($link, $sql);

    // Nếu tìm thấy tài khoản
    if(mysqli_num_rows($result) == 1){
        $row = mysqli_fetch_assoc($result);
        
        // Lấy mật khẩu đã băm từ database
        $hashed_password_db = $row['password'];

        // Kiểm tra mật khẩu nhập vào có khớp với mã băm không
        if(password_verify($pass, $hashed_password_db)){
            
            // Khớp -> Đăng nhập thành công, khởi tạo Session
            $_SESSION['id'] = $row['id'];
            $_SESSION['user'] = $row['name'];
            $_SESSION['role'] = (int)$row['role'];
            return 1;
        } else {
            return 0;
        }
    } else {
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