<?php
// xl_lab03_bai3.php

function safe($value) {
    return htmlspecialchars(trim((string)$value), ENT_QUOTES, 'UTF-8');
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo '<p>Vui lòng gửi form từ trang nhập liệu.</p>';
    exit;
}

// ===== Nhận dữ liệu =====
$proID   = safe($_POST['nmProID'] ?? '');
$proName = safe($_POST['nmProName'] ?? '');
$man     = safe($_POST['nmMan'] ?? '');
$width   = safe($_POST['nmWidth'] ?? '');
$height  = safe($_POST['nmHeight'] ?? '');
$count   = safe($_POST['nmCount'] ?? '');
$tacgia  = safe($_POST['nmTacGia'] ?? '');
$price   = safe($_POST['nmPrice'] ?? '');
$color   = safe($_POST['nmColor'] ?? '');
$live    = safe($_POST['nmLive'] ?? '');
$detail  = nl2br(safe($_POST['nmProDet'] ?? ''));

// ===== Checkbox =====
$functions = [];
if (!empty($_POST['nmFunctions']) && is_array($_POST['nmFunctions'])) {
    foreach ($_POST['nmFunctions'] as $f) {
        $functions[] = safe($f);
    }
}
$functions_text = count($functions) ? implode(', ', $functions) : 'Không có';

// ===== Upload ảnh =====
$imgInfo = 'Chưa có ảnh.';

if (!empty($_FILES['nmProImg']['name']) && $_FILES['nmProImg']['error'] === UPLOAD_ERR_OK) {

    $uploadsDir = __DIR__ . '/uploads/';
    if (!file_exists($uploadsDir)) {
        mkdir($uploadsDir, 0755, true);
    }

    // tránh trùng tên file
    $ext = pathinfo($_FILES['nmProImg']['name'], PATHINFO_EXTENSION);
    $filename = time() . '_' . rand(1000,9999) . '.' . $ext;

    $target = $uploadsDir . $filename;

    if (move_uploaded_file($_FILES['nmProImg']['tmp_name'], $target)) {
        $imgInfo = '<img src="uploads/' . $filename . '" style="max-width:300px;">';
    } else {
        $imgInfo = 'Lỗi upload ảnh.';
    }
}

// ===== Validate =====
$analysis = [];

if (empty($proID) || empty($proName)) {
    $analysis[] = 'Thiếu mã hoặc tên sản phẩm';
}

if (!is_numeric($width) || !is_numeric($height)) {
    $analysis[] = 'Kích thước phải là số';
}

if (!is_numeric($count)) {
    $analysis[] = 'Số trang phải là số';
}

if (!is_numeric($price)) {
    $analysis[] = 'Giá phải là số';
}

$analysis_html = '<ul><li>' . implode('</li><li>', $analysis) . '</li></ul>';

?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Kết quả</title>
    <style>
        body { font-family: Arial; background:#f5f5f5; }
        .box { background:#fff; padding:20px; width:700px; margin:auto; border:1px solid #333; }
        table { width:100%; }
        td { padding:6px; }
        .label { font-weight:bold; width:180px; }
    </style>
</head>

<body>

<div class="box">
    <h2>THÔNG TIN SẢN PHẨM</h2>

    <table>
        <tr><td class="label">Mã</td><td><?= $proID ?></td></tr>
        <tr><td class="label">Tên</td><td><?= $proName ?></td></tr>
        <tr><td class="label">Ảnh</td><td><?= $imgInfo ?></td></tr>
        <tr><td class="label">Hãng</td><td><?= $man ?></td></tr>
        <tr><td class="label">Kích thước</td><td><?= $width ?> x <?= $height ?></td></tr>
        <tr><td class="label">Số trang</td><td><?= $count ?></td></tr>
        <tr><td class="label">Tác giả</td><td><?= $tacgia ?></td></tr>
        <tr><td class="label">Giá</td><td><?= $price ?></td></tr>
        <tr><td class="label">Màu</td>
            <td>
                <span style="display:inline-block;width:20px;height:20px;background:<?= $color ?>;"></span>
                <?= $color ?>
            </td>
        </tr>
        <tr><td class="label">Xuất xứ</td><td><?= $live ?></td></tr>
        <tr><td class="label">Thể loại</td><td><?= $functions_text ?></td></tr>
        <tr><td class="label">Mô tả</td><td><?= $detail ?></td></tr>
    </table>

    <h3>Kiểm tra dữ liệu:</h3>
    <?= $analysis_html ?>

    <!-- SỬA ĐƯỜNG DẪN -->
    <p><a href="../index.html">Quay lại</a></p>

</div>

</body>
</html>