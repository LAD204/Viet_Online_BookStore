<?php 
include_once("clsbook.php");
class donhang extends clsbook{
    public function  dsdonhang($keyword = '', $status_filter = ''){
        $link = $this ->connect();
        $where = "WHERE 1=1";

        if($keyword !== ''){
            $keyword_clean = mysqli_real_escape_string($link, trim($keyword)); // làm sạch từ khóa và tránh lỗi trong SQL
            $where .= " AND (dh.id LIKE '%$keyword_clean%' OR u.name LIKE '%$keyword_clean%' OR u.telephone LIKE '%$keyword_clean%')";
        }

        if ($status_filter !== '') {
            $status_clean = mysqli_real_escape_string($link, $status_filter);
            $where .= " AND dh.trang_thai = '$status_clean'";
        }  
        
        $sql = "SELECT 
                    dh.id AS donhang_id,
                    dh.ngay_dat,
                    dh.trang_thai,
                    dh.tong_tien,
                    u.name AS ho_ten,
                    u.telephone AS so_dien_thoai,
                    dc.dia_chi
                FROM donhang dh
                JOIN user u ON dh.user_id = u.id
                LEFT JOIN diachi dc ON dh.dia_chi_id = dc.id
                $where
                ORDER BY dh.ngay_dat DESC";

        return mysqli_query($link, $sql);
    }

    public function trang_thai($donhang_id, $trang_thai){
        $link = $this->connect();
        $donhang_id = (int)$donhang_id;
        $trang_thai = mysqli_real_escape_string($link, $trang_thai);

        $sql ="UPDATE donhang SET trang_thai = '$trang_thai' WHERE id = '$donhang_id' LIMIT 1";
        return mysqli_query($link, $sql);
    }


    public function badge_trangthai($trang_thai) {
        $st = trim($trang_thai);
        switch ($st) {
            case 'Chờ xác nhận': 
                {
                     return '<span class="badge bg-warning text-dark">Chờ xác nhận</span>';
                }
            
            case 'Đang giao': 
                {
                     return '<span class="badge bg-info text-dark">Đang giao</span>';
                }
            case 'Đã hoàn thành': 
                {
                    return '<span class="badge bg-success">Đã hoàn thành</span>';
                }
            case 'Đã hủy': 
                {
                     return '<span class="badge bg-danger">Đã hủy</span>';
                }
            default: 
            {
                return '<span class="badge bg-secondary">Chưa xác định</span>';
            }
        }
    }


    public function ct_donhang($donhang_id) {
        $link = $this->connect();
        $donhang_id = (int)$donhang_id;

        $sql = "SELECT 
                    sp.ten_sach,
                    ct.so_luong,
                    sp.gia_ban AS don_gia
                FROM chitietgiohang ct
                JOIN sanpham sp ON ct.sanpham_id = sp.id
                WHERE ct.donhang_id = '$donhang_id'";

        return mysqli_query($link, $sql);
    }

    public function option_trangthai($status = ''){
    $list_status = ['Chờ xác nhận', 'Đang giao', 'Đã hoàn thành', 'Đã hủy'];
    $html = '';
    foreach ($list_status as $st) {
        $selected = ($st === $status) ? 'selected' : '';
        $html .= "<option value='{$st}' {$selected}>{$st}</option>";
    }
    return $html;
    }   

    public function bang_donhang($keyword = '', $status_filter = '') {
        $orders = $this->dsdonhang($keyword, $status_filter);

        if (!$orders || mysqli_num_rows($orders) == 0) {
            echo '<tr><td colspan="8" class="text-center py-4 text-muted">Không tìm thấy đơn hàng nào trong hệ thống.</td></tr>';
            return;
        }


        while ($row = mysqli_fetch_assoc($orders)) {
            $dh_id = $row['donhang_id'];
            $ho_ten =$row['ho_ten'];
            $sdt = $row['so_dien_thoai'];
            $dia_chi = $row['dia_chi'];
            $tong_tien = number_format($row['tong_tien'], 0, ',', '.');
            $ngay_dat = date('d/m/Y H:i', strtotime($row['ngay_dat']));
            $badge = $this->badge_trangthai($row['trang_thai']);
            $options = $this->option_trangthai($row['trang_thai']);

            echo '
                <tr>
                <td><strong>#DH' . $dh_id . '</strong></td>
                <td>' . $ho_ten . '</td>
                <td>' . $sdt . '</td>
                <td><small>' . $dia_chi . '</small></td>
                <td class="fw-bold text-primary">' . $tong_tien . 'đ</td>
                <td><small>' . $ngay_dat . '</small></td>
                <td>' . $badge . '</td>
                <td class="text-center">
                    <form action="Order processing.php" method="POST" class="d-inline-flex gap-1 align-items-center">
                    <input type="hidden" name="donhang_id" value="' . $dh_id . '">
                    <select name="trang_thai" class="form-select form-select-sm" style="width: 135px;">
                        ' . $options . '
                    </select>
                    <button type="submit" name="btn_update_status" class="btn btn-sm btn-brand btn-action">Lưu</button>
                    </form>
                    <button class="btn btn-sm btn-outline-secondary btn-action ms-1" data-bs-toggle="modal" data-bs-target="#modalDetail' . $dh_id . '">Xem</button>
                </td>
                </tr>';              
        }
            

    }

    public function ds_modal($keyword = '', $status_filter = '') {
        $orders = $this->dsdonhang($keyword, $status_filter);
        if (!$orders || mysqli_num_rows($orders) == 0) return;

        while ($row = mysqli_fetch_assoc($orders)) {
            $dh_id = $row['donhang_id'];
            $ho_ten = $row['ho_ten'] ?? '';
            $sdt = $row['so_dien_thoai'] ?? '';
            $dia_chi = $row['dia_chi'] ?? 'Chưa cập nhật';
            $ngay_dat_full = date('d/m/Y H:i:s', strtotime($row['ngay_dat']));
            $tong_tien_full = number_format($row['tong_tien'], 0, ',', '.');

            $items = $this->ct_donhang($dh_id);
            $rows_items_html = '';

            if ($items && mysqli_num_rows($items) > 0) {
                while ($item = mysqli_fetch_assoc($items)) {
                    $ten_sach = $item['ten_sach'];
                    $so_luong = $item['so_luong'];
                    $don_gia = number_format($item['don_gia'], 0, ',', '.');
                    $thanh_tien = number_format($so_luong * $item['don_gia'], 0, ',', '.');

                    $rows_items_html .= '
                    <tr>
                      <td>' . $ten_sach . '</td>
                      <td class="text-center">' . $so_luong . '</td>
                      <td class="text-end">' . $don_gia . 'đ</td>
                      <td class="text-end fw-semibold">' . $thanh_tien . 'đ</td>
                    </tr>';
                }
            } else {
                $rows_items_html = '<tr><td colspan="4" class="text-center text-muted">Không có dữ liệu chi tiết sách.</td></tr>';
            }

            echo '
            <div class="modal fade" id="modalDetail' . $dh_id . '" tabindex="-1" aria-hidden="true">
              <div class="modal-dialog modal-lg">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title fw-bold" style="color: var(--primary-color);">Chi Tiết Đơn Hàng #DH' . $dh_id . '</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                    <div class="row mb-3 fs-6">
                      <div class="col-md-6">
                        <p class="mb-1"><strong>Khách hàng:</strong> ' . $ho_ten . '</p>
                        <p class="mb-1"><strong>Số điện thoại:</strong> ' . $sdt . '</p>
                      </div>
                      <div class="col-md-6">
                        <p class="mb-1"><strong>Địa chỉ giao:</strong> ' . $dia_chi . '</p>
                        <p class="mb-1"><strong>Ngày đặt:</strong> ' . $ngay_dat_full . '</p>
                      </div>
                    </div>

                    <table class="table table-bordered align-middle">
                      <thead class="table-light">
                        <tr>
                          <th>Tên Sách</th>
                          <th class="text-center" style="width: 100px;">Số Lượng</th>
                          <th class="text-end" style="width: 130px;">Đơn Giá</th>
                          <th class="text-end" style="width: 140px;">Thành Tiền</th>
                        </tr>
                      </thead>
                      <tbody>
                        ' . $rows_items_html . '
                        <tr>
                          <td colspan="3" class="text-end fw-bold">TỔNG TIỀN ĐƠN HÀNG:</td>
                          <td class="text-end fw-bold text-primary fs-5">' . $tong_tien_full . 'đ</td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Đóng</button>
                  </div>
                </div>
              </div>
            </div>';
        }
    }

}






?>