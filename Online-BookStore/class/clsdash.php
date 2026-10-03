<?php 
include_once("clsbook.php");

class dash extends clsbook{
    // 1. Đếm số lượng đơn hàng theo từng trạng thái
    public function count_order($trang_thai = ''){
        $link = $this ->connect();
        $where = "WHERE 1=1";

        if($trang_thai !== ''){
            $st = mysqli_real_escape_string($link, $trang_thai);
            $where .= " AND trang_thai = '$st'";
        }
        $sql = "SELECT COUNT(*) AS tong FROM donhang $where";
        $res = mysqli_query($link, $sql);

        $row = mysqli_fetch_assoc($res);

        if (isset($row['tong'])) {
            return (int)$row['tong'];
        } else {
            return 0;
        }
    }

    // 2. Tính tổng tiền theo trạng thái
    public function sum_status($trang_thai = '') {
        $link = $this->connect();
        $where = "WHERE 1=1";
        if ($trang_thai !== '') {
            $st = mysqli_real_escape_string($link, $trang_thai);
            $where .= " AND trang_thai = '$st'";
        }
        $sql = "SELECT SUM(tong_tien) AS tongtien FROM donhang $where";
        $res = mysqli_query($link, $sql);
        $row = mysqli_fetch_assoc($res);
        if (isset($row['tongtien'])) {
            return (int)$row['tongtien'];
        } else {
            return 0;
        }
    }

    // 3. Bảng Chi Tiết Tỷ Lệ & Doanh Thu
    public function dashboard() {
        $list_st = [
            'Chờ xác nhận'  => ['badge' => 'bg-warning text-dark', 'note' => 'Đơn mới nhận'],
            'Đang giao'     => ['badge' => 'bg-info text-dark',    'note' => 'Đang vận chuyển'],
            'Đã hoàn thành' => ['badge' => 'bg-success',           'note' => 'Đã thu tiền'],
            'Đã hủy'        => ['badge' => 'bg-danger',            'note' => 'Đơn thất bại']
        ];

        $tong_don = $this->count_order();

        foreach ($list_st as $st => $info) {
            $count = $this->count_order($st);
            $money = $this->sum_status($st);
            $percent = 0;

            if ($tong_don > 0) {
                $percent = round(($count / $tong_don) * 100, 1);
            }

            echo '
            <tr>
              <td><span class="badge ' . $info['badge'] . ' me-2">' . $st . '</span> ' . $info['note'] . '</td>
              <td class="text-center fw-bold">' . $count . '</td>
              <td class="text-center">' . $percent . '%</td>
              <td class="text-end">' . number_format($money, 0, ',', '.') . 'đ</td>
            </tr>';
        }
    }
    
}


?>