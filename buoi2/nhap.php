<?php
$students = [];

function calculateAverage($midterm, $final) {
    return ($midterm + $final) / 2;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    for ($i = 1; $i <= 3; $i++) {
        $students[] = [
            'name'    => $_POST['name' . $i],
            'midterm' => $_POST['midterm' . $i],
            'final'   => $_POST['final' . $i],
        ];
    }
}

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Nhập điểm sinh viên</title>
</head>
<body>
    <h2>Nhập điểm 3 sinh viên</h2>
    <form method="POST">
        <?php for ($i = 1; $i <= 3; $i++): ?>
            Tên: <input type="text" name="name<?php echo $i; ?>">
            Giữa kỳ: <input type="text" name="midterm<?php echo $i; ?>">
            Cuối kỳ: <input type="text" name="final<?php echo $i; ?>"><br><br>
        <?php endfor; ?>
        <input type="submit" value="Tính">
    </form>

    <?php if ($students): ?>
        <table border="1" cellpadding="8">
            <tr>
                <th>Tên</th>
                <th>Giữa kỳ</th>
                <th>Cuối kỳ</th>
                <th>Trung bình</th>
                <th>Kết quả</th>
            </tr>
            <?php foreach ($students as $sv): ?>
                <?php $avg = calculateAverage($sv['midterm'], $sv['final']); ?>
                <tr>
                    <td><?php echo htmlspecialchars($sv['name']); ?></td>
                    <td><?php echo $sv['midterm']; ?></td>
                    <td><?php echo $sv['final']; ?></td>
                    <td><?php echo $avg; ?></td>
                    <td><?php echo $avg >= 5 ? 'Đạt' : 'Không đạt'; ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
</body>
</html>