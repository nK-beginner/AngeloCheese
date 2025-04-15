<?php
    $uploadDir = __DIR__ . '/../Uploads/';
    if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

    // メイン画像
    $mainPath = '';
    if ($_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $mainName = basename($_FILES['image']['name']);
        $mainTmp = $_FILES['image']['tmp_name'];
        move_uploaded_file($mainTmp, $uploadDir . $mainName);
        $mainPath = 'uploads/' . $mainName;
    }

    // サブ画像（複数）
    $subPaths = [];
    if (isset($_FILES['images'])) {
        foreach ($_FILES['images']['tmp_name'] as $i => $tmp) {
            if ($_FILES['images']['error'][$i] === UPLOAD_ERR_OK) {
                $name = basename($_FILES['images']['name'][$i]);
                move_uploaded_file($tmp, $uploadDir . $name);
                $subPaths[] = 'uploads/' . $name;
            }
        }
    }

    $name = htmlspecialchars($_POST['name'] ?? '');
    $desc = nl2br(htmlspecialchars($_POST['description'] ?? ''));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>アップロード完了</h1>
    <p><strong>商品名:</strong> <?= $name ?></p>
    <p><strong>説明:</strong><br><?= $desc ?></p>

    <?php if ($mainPath): ?>
        <h2>メイン画像</h2>
        <img src="<?= $mainPath ?>" style="max-width:300px;">
    <?php endif; ?>

    <?php if ($subPaths): ?>
        <h2>サブ画像</h2>
        <?php foreach ($subPaths as $path): ?>
            <img src="<?= $path ?>" style="max-width:150px; margin: 5px;">
        <?php endforeach; ?>
    <?php endif; ?>
</body>
</html>

