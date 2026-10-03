<?php
    class csdl
    {
        private function connect(){
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
        public function add_user(string $user_name, string $password, string $sex, string $telephone, string $gmail){
            $link = $this->connect();

            $user_name = trim($user_name);
            $password = trim($password);
            $telephone = trim($telephone);
            $gmail = trim($gmail);

            $user_name = mysqli_real_escape_string($link, $user_name);
            $password = mysqli_real_escape_string($link, $password);
            $telephone = mysqli_real_escape_string($link, $telephone);
            $gmail = mysqli_real_escape_string($link, $gmail);
            $sex = mysqli_real_escape_string($link, $sex);
            
            $hash = password_hash($password, PASSWORD_DEFAULT);
            if($hash === false || $hash === null){
                return false;
            }
            $sql_check_user_exist = "SELECT * FROM user where name = '$user_name' or telephone = '$telephone' order by name asc";
            
            $kiemtra = mysqli_query($link, $sql_check_user_exist);
            if(mysqli_num_rows($kiemtra)>0){
                echo 'Tên user hoặc số điện thoại đã tồn tại';
                exit();
            }
            else{
                $sql_save_user = "INSERT INTO user(name, password, sex, telephone, gmail) VALUES ('$user_name', '$hash', '$sex', '$telephone', '$gmail')";
                $save = mysqli_query($link, $sql_save_user);
                if($save){
                    return $save;
                }
                else{
                    return false;
                }
            }
        }
        public function export_product(string $sql){
            $link = $this->connect();
            $result = mysqli_query($link, $sql);
            if($result->num_rows > 0){
                $i = 0;
                while($row = mysqli_fetch_array($result)){
                    echo'
                        <div class="product-card">
                            <form action="" method="post">
                                <div class="card shadow-sm border-0" style="width: 100%; max-width: 250px;">
                                    <div class="custom-images overflow-hidden rounded-top" style="aspect-ratio: 1/1;">
                                        <img src="images/'.$row['hinh_anh'].'" alt="Ảnh sản phẩm" class="w-100 h-100 object-fit-cover">
                                    </div>
                                    <div class="card-body d-flex flex-column">
                                        <h5 class="card-title fs-6 fw-bold text-truncate" style="width: 230px; overflow-y: hidden;">'.$row['ten_sach'].'</h5>
                                        
                                        <p class="card-text text-danger fw-bold mb-3">'.$row['gia_ban'].' VNĐ</p>
                                        <div class="mt-auto d-flex gap-2">
                                            <input type="submit" class="btn btn-primary w-50" style="font-size: 14px;" value="Thêm">
                                            <a href="chitiet.php?id='.$hash = password_hash($row['id'], PASSWORD_DEFAULT).'" class="btn btn-outline-secondary w-50" style="font-size: 14px; text-decoration: none; text-align: center; line-height: 2;">
                                                Xem chi tiết
                                            </a>
                                        </div>
                                    </div>
                                    
                                </div>
                            </form>
                        </div>
                    ';
                    $i += 1;
                }
            }
            else{
                echo'<script>alert("Không tìm thấy dữ liệu nào");</script>';
            }
        }
    }
?>