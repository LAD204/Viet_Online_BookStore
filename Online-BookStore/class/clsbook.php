<?php 
class clsbook{
    public function connect(){
        $con = mysqli_connect('localhost', 'usersachonline', 'sachonline123456');
            if(!$con){
                echo '<script>alert("Lấy thông tin thất bại");</script>';
                exit();
            }
            else{
                mysqli_select_db($con,'bookstore_db');
                mysqli_query($con,'SET NAMES UTF8');
                return $con;
        }
    }
}

?>