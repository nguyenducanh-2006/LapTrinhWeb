<?php
$title = "Buổi 3: Xử lý Form (GET & POST) & Validation";
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #0f172a; color: #f8fafc; padding: 40px; margin: 0; }
        .card { background: #1e293b; border-radius: 12px; padding: 30px; border: 1px solid #334155; max-width: 800px; margin: 0 auto; box-shadow: 0 10px 25px rgba(0,0,0,0.3); }
        h1 { color: #38bdf8; border-bottom: 2px solid #334155; padding-bottom: 12px; margin-top: 0; }
        .btn { display: inline-block; padding: 10px 20px; background: #3b82f6; color: #fff; text-decoration: none; border-radius: 6px; font-weight: 600; margin-top: 20px; transition: background 0.3s; margin-right: 10px; }
        .btn:hover { background: #2563eb; }
        code { background: #0f172a; padding: 2px 8px; border-radius: 4px; color: #f43f5e; }
    </style>
</head>
<body>
    <div class="card">
        <h1><?= $title ?></h1>
        <p>Nội dung thực hành: Thu thập dữ liệu gửi từ biểu mẫu HTML bằng phương thức GET/POST, kiểm tra tính hợp lệ (Validation) và làm sạch dữ liệu (Sanitization).</p>
        <p><strong>Thông tin môi trường:</strong></p>
        <ul>
            <li>Phiên bản PHP hiện tại: <strong><?= phpversion(); ?></strong></li>
            <li>Thời gian hệ thống: <strong><?= date('Y-m-d H:i:s'); ?></strong></li>
        </ul>
        <a href="../index.php" class="btn">← Quay lại Trang Chủ</a>
        <a href="../about.php" class="btn" style="background: #10b981;">Xem trang About Me →</a>
    </div>
</body>
</html>
