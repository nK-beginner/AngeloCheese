<?php
$uploadDir = __DIR__ . '/uploads/';
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

$uploadedPath = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
    $tmpName = $_FILES['image']['tmp_name'];
    $name = basename($_FILES['image']['name']);
    $uploadedPath = 'uploads/' . $name;

    move_uploaded_file($tmpName, $uploadDir . $name);
}

$name = htmlspecialchars($_POST['name'] ?? '');
$desc = nl2br(htmlspecialchars($_POST['description'] ?? ''));
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>アップロード結果</title>
</head>
<body>
    <h1>アップロード完了</h1>
    <p><strong>商品名:</strong> <?= $name ?></p>
    <p><strong>説明:</strong><br><?= $desc ?></p>
    <?php if ($uploadedPath): ?>
        <p><img src="<?= $uploadedPath ?>" style="max-width:300px;"></p>
    <?php else: ?>
        <p>画像のアップロードに失敗しました。</p>
    <?php endif; ?>
</body>
</html>
