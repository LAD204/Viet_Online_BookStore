<?php
session_start();
include_once("../../class/clssanpham.php");
$p = new sanpham();

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Xử lý Ajax cập nhật số lượng tự động ngầm
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'ajax_update') {
    $id = intval($_POST['id'] ?? 0);
    $qty = intval($_POST['qty'] ?? 1);

    if ($id > 0) {
        if ($qty <= 0) {
            unset($_SESSION['cart'][$id]);
        } else {
            $_SESSION['cart'][$id] = $qty;
        }
    }
    echo json_encode(['status' => 'success']);
    exit();
}

// Xử lý thêm vào giỏ từ chitietsanpham.php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add') {
    $id = intval($_POST['id']);
    $qty = max(1, intval($_POST['so_luong'] ?? 1));

    if (isset($_SESSION['cart'][$id])) {
        $_SESSION['cart'][$id] += $qty;
    } else {
        $_SESSION['cart'][$id] = $qty;
    }
    header("Location: giohang.php");
    exit();
}

// Xử lý xóa sản phẩm khỏi giỏ
if (isset($_GET['del_id'])) {
    $del_id = intval($_GET['del_id']);
    unset($_SESSION['cart'][$del_id]);
    header("Location: giohang.php");
    exit();
}

// Tính tổng số lượng hiển thị Navbar
$tong_soluong_giohang = 0;
foreach ($_SESSION['cart'] as $id_sp => $qty) {
    $tong_soluong_giohang += $qty;
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giỏ hàng - SÁCH VIỆT</title>
    <link href="../../layout/css/bootstrap.min.css" rel="stylesheet">
    <link href="../../layout/css/index-style.css" rel="stylesheet">
</head>
<body>
<header>
    <nav class="navbar navbar-expand">
        <div class="container-fluid">
            <a class="navbar-brand" href="../../index.php">
                <img src="../../images/logo.jpg" alt="Logo"> SÁCH VIỆT
            </a>
            <ul class="navbar-nav align-items-center ms-auto">
                <!-- Thêm hiển thị giỏ hàng (X) vào đây -->
                <li class="nav-item me-3"><a class="nav-link fw-bold text-primary" href="giohang.php">Giỏ hàng (<?= $tong_soluong_giohang ?>)</a></li>
                <li class="nav-item"><a class="nav-link" href="../../index.php">TIẾP TỤC MUA SẮM</a></li>
            </ul>
        </div>
    </nav>
</header>

<div class="container my-5">
    <h3 class="fw-bold mb-4">GIỎ HÀNG CỦA BẠN</h3>

    <?php if (empty($_SESSION['cart'])): ?>
        <div class="alert alert-info text-center py-5">
            <p class="fs-5 mb-3">Giỏ hàng đang trống.</p>
            <a href="../../index.php" class="btn btn-primary">Xem danh sách sách</a>
        </div>
    <?php else: ?>
        <div class="card shadow border-0 mb-4">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Tên sách</th>
                            <th class="text-center">Đơn giá</th>
                            <th class="text-center" style="width: 140px;">Số lượng</th>
                            <th class="text-end">Thành tiền</th>
                            <th class="text-center">Xóa</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $tong_tien = 0;
                        foreach ($_SESSION['cart'] as $id => $qty):
                            $res = $p->chitietsanpham($id);
                            if ($res && $item = mysqli_fetch_assoc($res)):
                                $gia = $item['gia_ban'];
                                $thanh_tien = $gia * $qty;
                                $tong_tien += $thanh_tien;
                        ?>
                            <tr class="cart-item-row" data-id="<?= $id ?>" data-price="<?= $gia ?>">
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        <img src="../../images/<?= htmlspecialchars($item['hinh_anh']) ?>" width="50" height="65" style="object-fit: cover;" class="rounded" onerror="this.src='../../images/logo.jpg'">
                                        <span class="fw-semibold"><?= htmlspecialchars($item['ten_sach']) ?></span>
                                    </div>
                                </td>
                                <td class="text-center fw-semibold text-secondary">
                                    <?= number_format($gia, 0, ',', '.') ?> đ
                                </td>
                                <td class="text-center">
                                    <input 
                                        type="number" 
                                        class="form-control form-control-sm text-center cart-qty-input" 
                                        value="<?= $qty ?>" 
                                        min="1" 
                                        max="<?= $item['so_luong'] ?>"
                                    >
                                </td>
                                <td class="text-end fw-bold text-primary item-subtotal">
                                    <?= number_format($thanh_tien, 0, ',', '.') ?> đ
                                </td>
                                <td class="text-center">
                                    <a href="giohang.php?del_id=<?= $id ?>" class="btn btn-outline-danger btn-sm" onclick="return confirm('Bạn muốn xóa sản phẩm này khỏi giỏ hàng?');">✕</a>
                                </td>
                            </tr>
                        <?php endif; endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="d-flex justify-content-end align-items-center gap-3">
            <div class="text-end">
                <h5 class="mb-2">Tổng tiền: <span class="text-danger fw-bold fs-4" id="cart-total"><?= number_format($tong_tien, 0, ',', '.') ?> đ</span></h5>
                <a href="../dathang/dathang.php" class="btn btn-success btn-lg">Tiến hành đặt hàng</a>
            </div>
        </div>
    <?php endif; ?>
</div>

<script src="../../layout/js/bootstrap.bundle.min.js"></script>
<script>
document.querySelectorAll('.cart-qty-input').forEach(input => {
    input.addEventListener('input', function() {
        const row = this.closest('.cart-item-row');
        const id = row.getAttribute('data-id');
        const price = parseFloat(row.getAttribute('data-price'));
        let qty = parseInt(this.value);
        const max = parseInt(this.getAttribute('max')) || 9999;

        if (isNaN(qty) || qty < 1) {
            qty = 1;
            this.value = 1;
        } else if (qty > max) {
            qty = max;
            this.value = max;
        }

        const subtotal = price * qty;
        row.querySelector('.item-subtotal').textContent = subtotal.toLocaleString('vi-VN') + ' đ';

        let total = 0;
        document.querySelectorAll('.cart-item-row').forEach(r => {
            const p = parseFloat(r.getAttribute('data-price'));
            const q = parseInt(r.querySelector('.cart-qty-input').value) || 0;
            total += p * q;
        });
        document.getElementById('cart-total').textContent = total.toLocaleString('vi-VN') + ' đ';

        const formData = new FormData();
        formData.append('action', 'ajax_update');
        formData.append('id', id);
        formData.append('qty', qty);

        fetch('giohang.php', {
            method: 'POST',
            body: formData
        });
    });
});
</script>
</body>
</html>