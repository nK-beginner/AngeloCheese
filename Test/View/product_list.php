<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php if (!empty($products)): ?>
        <table border="1">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>商品名</th>
                    <th>価格</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $product): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($product['id']); ?></td>
                        <td><?php echo htmlspecialchars($product['name']); ?></td>
                        <td><?php echo htmlspecialchars($product['price']); ?>円</td>
                        <td><a href="index.php?action=detail&id=<?= $product['id'] ?>">詳細</a></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

    <?php else: ?>
        <p>商品がありません。</p>
    <?php endif; ?>
</body>
</html>