<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h2>商品詳細</h2>
    <p><strong>ID:</strong> <?= htmlspecialchars($product['id']) ?></p>
    <p><strong>商品名:</strong> <?= htmlspecialchars($product['name']) ?></p>
    <p><strong>価格:</strong> <?= htmlspecialchars($product['price']) ?>円</p>
    <p><strong>説明:</strong> <?= nl2br(htmlspecialchars($product['description'])) ?></p>

    <a href="index.php">← 商品一覧へ戻る</a>
</body>
</html>