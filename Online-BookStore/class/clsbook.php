<?php 
class clsbook{
    public function connect(){
        $con = mysqli_connect('localhost', 'root', '');
        if(!$con){
            echo 'Loi ket noi';
            exit();
        }else{
            mysqli_select_db($con,'bookstore_db');
            mysqli_query($con,"SET NAMES UTF8");
            return $con;
        }
    }

}

?>