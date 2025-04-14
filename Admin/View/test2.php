<?php
$uploadedImages = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['images'])) {
    $uploadDir = __DIR__ . '/uploads/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    foreach ($_FILES['images']['error'] as $index => $error) {
        if ($error === UPLOAD_ERR_OK) {
            $tmpName = $_FILES['images']['tmp_name'][$index];
            $name = basename($_FILES['images']['name'][$index]);
            $uploadFile = $uploadDir . $name;

            if (move_uploaded_file($tmpName, $uploadFile)) {
                $uploadedImages[] = 'uploads/' . htmlspecialchars($name);
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>アップロード完了</title>
</head>
<body>

<h2>アップロード完了！</h2>

<?php if (!empty($uploadedImages)): ?>
  <ul>
    <?php foreach ($uploadedImages as $img): ?>
      <li>
        <p><?= basename($img) ?></p>
        <img src="<?= $img ?>" alt="uploaded image" style="max-width:200px; max-height:200px;">
      </li>
    <?php endforeach; ?>
  </ul>
<?php else: ?>
  <p>画像がアップロードされませんでした。</p>
<?php endif; ?>

</body>
</html>
