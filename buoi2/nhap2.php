<?php
$ten = '';
$sl = '';
$dg = '';
$tong = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ten = $_POST['ten'];
    $sl = $_POST['sl'];
    $dg = $_POST['dg'];
    $tong = $sl * $dg;
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Tính tổng tiền</title>
</head>
<body>
    <h2>Tính tổng tiền</h2>
    <form method="POST">
        Tên tài liệu: <input type="text" name="ten" value="<?php echo $ten; ?>">
        Số lượng: <input type="text" name="sl" value="<?php echo $sl; ?>">
        Đơn giá: <input type="text" name="dg" value="<?php echo $dg; ?>">
        <button>Tính</button>
    </form>

    <?php if ($tong !== ''): ?>
        <h3>Tổng tiền: <?php echo $tong; ?> đ</h3>
    <?php endif; ?>
</body>
</html>