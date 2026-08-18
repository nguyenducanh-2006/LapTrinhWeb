<?php

$loi = [];
$thanhCong = false;


$hoTen  = '';
$email  = '';
$chuDe  = '';
$noiDung = '';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

   
    $hoTen   = trim($_POST['ho_ten'] ?? '');
    $email   = trim($_POST['email'] ?? '');
    $chuDe   = trim($_POST['chu_de'] ?? '');
    $noiDung = trim($_POST['noi_dung'] ?? '');

   
    if ($hoTen === '') {
        $loi[] = "Họ tên không được để trống.";
    }

  
    if ($noiDung === '') {
        $loi[] = "Nội dung không được để trống.";
    }

   
    if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $loi[] = "Email không đúng định dạng.";
    }

   
    if (isset($_FILES['anh_dai_dien']) && $_FILES['anh_dai_dien']['error'] === UPLOAD_ERR_OK) {
        $tenFileGoc = $_FILES['anh_dai_dien']['name'];
        $duoiFile   = strtolower(pathinfo($tenFileGoc, PATHINFO_EXTENSION));
        $duoiChoPhep = ['jpg', 'jpeg', 'png', 'gif'];

        if (!in_array($duoiFile, $duoiChoPhep)) {
            $loi[] = "Ảnh đại diện phải có định dạng jpg, jpeg, png hoặc gif.";
        } else {
           
            $tenFileMoi = uniqid() . '.' . $duoiFile;
            $thuMucLuu  = __DIR__ . '/uploads/';

            if (!is_dir($thuMucLuu)) {
                mkdir($thuMucLuu, 0777, true); 
            }

            move_uploaded_file($_FILES['anh_dai_dien']['tmp_name'], $thuMucLuu . $tenFileMoi);
        }
    }

    
    if (empty($loi)) {
        $thanhCong = true;

       
        $hoTen = $email = $chuDe = $noiDung = '';
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>Liên hệ</title>
<style>
    body {
        font-family: Arial, sans-serif;
        background: #f4f6f8;
        margin: 0;
        padding: 30px;
    }
    .container {
        max-width: 480px;
        margin: 0 auto;
        background: #fff;
        padding: 24px 28px;
        border-radius: 8px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    }
    h2 {
        margin-top: 0;
        color: #1e3a8a;
    }
    label {
        display: block;
        font-weight: 600;
        margin-bottom: 4px;
        margin-top: 14px;
    }
    input[type="text"],
    textarea {
        width: 100%;
        padding: 8px 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
        font-size: 14px;
        box-sizing: border-box;
    }
    textarea {
        resize: vertical;
    }
    input[type="file"] {
        margin-top: 4px;
    }
    button {
        margin-top: 18px;
        background: #1e3a8a;
        color: #fff;
        border: none;
        padding: 10px 22px;
        border-radius: 5px;
        font-size: 14px;
        cursor: pointer;
    }
    button:hover {
        background: #16295e;
    }
    .thanh-cong {
        background: #dcfce7;
        color: #166534;
        border: 1px solid #86efac;
        padding: 10px 14px;
        border-radius: 5px;
        margin-bottom: 14px;
    }
    .loi {
        background: #fee2e2;
        color: #991b1b;
        border: 1px solid #fca5a5;
        border-radius: 5px;
        padding: 10px 14px;
        margin-bottom: 14px;
    }
    .loi ul {
        margin: 0;
        padding-left: 18px;
    }
</style>
</head>
<body>

<div class="container">
<h2> Liên hệ</h2>

<?php if ($thanhCong): ?>
    <div class="thanh-cong"><strong>Gửi liên hệ thành công!</strong></div>
<?php endif; ?>

<?php if (!empty($loi)): ?>
    <div class="loi">
        <ul>
            <?php foreach ($loi as $tenLoi): ?>
                <li><?= htmlspecialchars($tenLoi) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<form method="POST" enctype="multipart/form-data">
    <label>Họ tên</label>
    <input type="text" name="ho_ten" value="<?= htmlspecialchars($hoTen) ?>">

    <label>Email</label>
    <input type="text" name="email" value="<?= htmlspecialchars($email) ?>">

    <label>Chủ đề</label>
    <input type="text" name="chu_de" value="<?= htmlspecialchars($chuDe) ?>">

    <label>Nội dung</label>
    <textarea name="noi_dung" rows="4"><?= htmlspecialchars($noiDung) ?></textarea>

    <label>Ảnh đại diện</label>
    <input type="file" name="anh_dai_dien">

    <button type="submit">Gửi</button>
</form>
</div>

</body>
</html>