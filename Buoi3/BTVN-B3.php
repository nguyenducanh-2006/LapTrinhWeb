<?php
session_start();
if (!isset($_SESSION['dsDangKy'])) {
    $_SESSION['dsDangKy'] = []; // mảng lưu các phiếu đăng ký
}


foreach ($_SESSION['dsDangKy'] as $k => $p) {
    if (!isset($p['id'])) {
        $_SESSION['dsDangKy'][$k]['id'] = uniqid();
    }
}

$thongBao = "";
$loi = [];      
$duLieuCu = [       
    'maSV' => '', 'maHP' => '', 'tenHP' => '', 'hocKy' => '', 'soTinChi' => ''
];


function tinhTongTinChi($ds, $maSV, $hocKy) {
    $tong = 0;
    foreach ($ds as $p) {
        if ($p['maSV'] === $maSV && $p['hocKy'] === $hocKy) {
            $tong += $p['soTinChi'];
        }
    }
    return $tong;
}


function daDangKy($ds, $maSV, $maHP, $hocKy) {
    foreach ($ds as $p) {
        if ($p['maSV'] === $maSV && $p['maHP'] === $maHP && $p['hocKy'] === $hocKy) {
            return true;
        }
    }
    return false;
}


function huyDangKy(&$ds, $id) {
    foreach ($ds as $index => $p) {
        if (isset($p['id']) && $p['id'] === $id) {
            array_splice($ds, $index, 1); 
            return true;
        }
    }
    return false;
}


function chuanHoa($chuoi) {
    $chuoi = trim($chuoi);
    $chuoi = preg_replace('/\s+/', ' ', $chuoi); 
    return $chuoi;
}


function kiemTraHopLe($maSV, $maHP, $tenHP, $hocKy, $soTinChiThoo) {
    $loi = [];

   
    if ($maSV === '') {
        $loi['maSV'] = 'Vui lòng nhập mã sinh viên.';
    } elseif (!preg_match('/^[0-9]{4,10}$/', $maSV)) {
        $loi['maSV'] = 'Mã sinh viên chỉ được nhập số, dài 4-10 chữ số.';
    }

    
    if ($maHP === '') {
        $loi['maHP'] = 'Vui lòng nhập mã học phần.';
    } elseif (!preg_match('/^[A-Za-z0-9_]{4,10}$/', $maHP)) {
        $loi['maHP'] = "Mã học phần chỉ gồm chữ, số và dấu '_', không được ký tự đặc biệt khác, dài 4-10 ký tự.";
    }

   
    if ($tenHP === '') {
        $loi['tenHP'] = 'Vui lòng nhập tên học phần.';
    } elseif (!preg_match('/^[\p{L}\s]+$/u', $tenHP)) {
        $loi['tenHP'] = 'Tên học phần chỉ được nhập chữ cái, không chứa số hoặc ký tự đặc biệt.';
    } elseif (mb_strlen($tenHP) < 3 || mb_strlen($tenHP) > 100) {
        $loi['tenHP'] = 'Tên học phần dài 3-100 ký tự.';
    }

  
    if ($hocKy === '') {
        $loi['hocKy'] = 'Vui lòng nhập học kỳ.';
    } elseif (!ctype_digit((string)$hocKy)) {
        $loi['hocKy'] = 'Học kỳ chỉ được nhập số.';
    } elseif ((int)$hocKy < 1 || (int)$hocKy > 10) {
        $loi['hocKy'] = 'Học kỳ phải trong khoảng 1 - 10.';
    }

   
    if ($soTinChiThoo === '') {
        $loi['soTinChi'] = 'Vui lòng nhập số tín chỉ.';
    } elseif (!ctype_digit((string)$soTinChiThoo)) {
        $loi['soTinChi'] = 'Số tín chỉ phải là số nguyên dương.';
    } elseif ((int)$soTinChiThoo < 1 || (int)$soTinChiThoo > 4) {
        $loi['soTinChi'] = 'Số tín chỉ không được quá 4.';
    }

    return $loi;
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'huy') {
  
    $id = $_POST['id'] ?? '';
    if (huyDangKy($_SESSION['dsDangKy'], $id)) {
        $thongBao = "Đã hủy phiếu đăng ký.";
    } else {
        $thongBao = "Không tìm thấy phiếu để hủy.";
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
  
    $maSV     = chuanHoa($_POST['maSV'] ?? '');
    $maHP     = strtoupper(chuanHoa($_POST['maHP'] ?? ''));
    $tenHP    = chuanHoa($_POST['tenHP'] ?? '');
    $hocKy    = chuanHoa($_POST['hocKy'] ?? '');
    $soTinChiThoo = chuanHoa($_POST['soTinChi'] ?? '');

    
    $duLieuCu = ['maSV' => $maSV, 'maHP' => $maHP, 'tenHP' => $tenHP, 'hocKy' => $hocKy, 'soTinChi' => $soTinChiThoo];

   
    $loi = kiemTraHopLe($maSV, $maHP, $tenHP, $hocKy, $soTinChiThoo);

    if (!empty($loi)) {
        $thongBao = "Dữ liệu chưa hợp lệ, vui lòng kiểm tra lại các trường được đánh dấu bên dưới.";
    } elseif (daDangKy($_SESSION['dsDangKy'], $maSV, $maHP, $hocKy)) {
        $loi['maHP'] = "Sinh viên này đã đăng ký học phần trong học kỳ này rồi.";
        $thongBao = "$maSV đã đăng ký học phần $maHP trong học kỳ $hocKy rồi.";
    } else {
        $soTinChi = (int) $soTinChiThoo;
        $_SESSION['dsDangKy'][] = [
            'id' => uniqid(),
            'maSV' => $maSV, 'maHP' => $maHP, 'tenHP' => $tenHP,
            'hocKy' => $hocKy, 'soTinChi' => $soTinChi
        ];
        $tong = tinhTongTinChi($_SESSION['dsDangKy'], $maSV, $hocKy);
        $thongBao = "Đã ghi nhận phiếu đăng ký cho $maSV. Tổng tín chỉ hiện tại trong học kỳ $hocKy: $tong.";
        $duLieuCu = ['maSV' => '', 'maHP' => '', 'tenHP' => '', 'hocKy' => '', 'soTinChi' => '']; 
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>Đăng ký học phần</title>
<style>
  body {
    font-family: Arial, Helvetica, sans-serif;
    max-width: 820px;
    margin: 30px auto;
    padding: 0 16px;
    color: #2b2b2b;
    background: #f7f8fa;
  }
  h1 {
    font-size: 24px;
    border-bottom: 3px solid #2e7d32;
    padding-bottom: 10px;
    margin-bottom: 18px;
  }
  h2 {
    font-size: 19px;
    margin-top: 34px;
    color: #1b5e20;
  }
  .thongbao {
    background: #fff8e1;
    border: 1px solid #f0c14b;
    color: #7a5c00;
    padding: 10px 14px;
    border-radius: 5px;
    margin-bottom: 18px;
  }
  form {
    background: #ffffff;
    border: 1px solid #ddd;
    border-radius: 6px;
    padding: 20px 22px;
  }
  .dong {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    margin-bottom: 12px;
  }
  .dong label {
    width: 130px;
    font-weight: bold;
  }
  .dong input {
    padding: 7px 10px;
    border: 1px solid #bbb;
    border-radius: 4px;
    font-size: 14px;
    width: 260px;
  }
  .dong input:focus {
    outline: none;
    border-color: #2e7d32;
    box-shadow: 0 0 0 2px rgba(46,125,50,0.15);
  }
  .loi-truong {
    color: #c62828;
    font-size: 13px;
    margin-left: 10px;
  }
  button {
    background: #2e7d32;
    color: #fff;
    border: none;
    padding: 9px 22px;
    border-radius: 4px;
    font-size: 14px;
    cursor: pointer;
  }
  button:hover { background: #1b5e20; }
  button.huy {
    background: #c62828;
    padding: 5px 12px;
    font-size: 12px;
  }
  button.huy:hover { background: #8e0000; }

  table {
    width: 100%;
    border-collapse: collapse;
    background: #fff;
    border: 1px solid #ddd;
    border-radius: 6px;
    overflow: hidden;
  }
  th, td {
    padding: 9px 10px;
    text-align: left;
    border-bottom: 1px solid #eee;
    font-size: 14px;
  }
  th {
    background: #2e7d32;
    color: #fff;
    font-size: 13px;
    text-transform: uppercase;
    letter-spacing: .03em;
  }
  tr:last-child td { border-bottom: none; }
  tr:hover td { background: #f2f8f2; }
</style>
</head>
<body>

<h1>Đăng ký học phần</h1>

<?php if ($thongBao): ?>
  <div class="thongbao"><?= htmlspecialchars($thongBao) ?></div>
<?php endif; ?>

<form method="post" novalidate>
  <div class="dong">
    <label>Mã sinh viên:</label>
    <input name="maSV" value="<?= htmlspecialchars($duLieuCu['maSV']) ?>" inputmode="numeric">
    <?php if (isset($loi['maSV'])): ?><span class="loi-truong"><?= htmlspecialchars($loi['maSV']) ?></span><?php endif; ?>
  </div>

  <div class="dong">
    <label>Mã học phần:</label>
    <input name="maHP" value="<?= htmlspecialchars($duLieuCu['maHP']) ?>">
    <?php if (isset($loi['maHP'])): ?><span class="loi-truong"><?= htmlspecialchars($loi['maHP']) ?></span><?php endif; ?>
  </div>

  <div class="dong">
    <label>Tên học phần:</label>
    <input name="tenHP" value="<?= htmlspecialchars($duLieuCu['tenHP']) ?>">
    <?php if (isset($loi['tenHP'])): ?><span class="loi-truong"><?= htmlspecialchars($loi['tenHP']) ?></span><?php endif; ?>
  </div>

  <div class="dong">
    <label>Học kỳ:</label>
    <input name="hocKy" value="<?= htmlspecialchars($duLieuCu['hocKy']) ?>" inputmode="numeric">
    <?php if (isset($loi['hocKy'])): ?><span class="loi-truong"><?= htmlspecialchars($loi['hocKy']) ?></span><?php endif; ?>
  </div>

  <div class="dong">
    <label>Số tín chỉ:</label>
    <input name="soTinChi" value="<?= htmlspecialchars($duLieuCu['soTinChi']) ?>" inputmode="numeric">
    <?php if (isset($loi['soTinChi'])): ?><span class="loi-truong"><?= htmlspecialchars($loi['soTinChi']) ?></span><?php endif; ?>
  </div>

  <button type="submit">Đăng ký</button>
</form>

<h2>Danh sách đăng ký</h2>

<table>
  <tr>
    <th>#</th><th>Sinh viên</th><th>Mã HP</th><th>Tên học phần</th><th>Học kỳ</th>
    <th>Tín chỉ</th><th>Hủy</th>
  </tr>
  <?php
  $stt = 1;
  foreach ($_SESSION['dsDangKy'] as $p) {
      echo "<tr>";
      echo "<td>$stt</td>";
      echo "<td>" . htmlspecialchars($p['maSV']) . "</td>";
      echo "<td>" . htmlspecialchars($p['maHP']) . "</td>";
      echo "<td>" . htmlspecialchars($p['tenHP'] ?? '') . "</td>";
      echo "<td>" . htmlspecialchars($p['hocKy']) . "</td>";
      echo "<td>" . htmlspecialchars($p['soTinChi']) . "</td>";
      echo "<td>
              <form method='post' style='display:inline;margin:0'>
                <input type='hidden' name='action' value='huy'>
                <input type='hidden' name='id' value='" . htmlspecialchars($p['id'] ?? '') . "'>
                <button type='submit' class='huy'>Hủy</button>
              </form>
            </td>";
      echo "</tr>";
      $stt++;
  }
  ?>
</table>

</body>
</html>