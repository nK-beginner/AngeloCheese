<?php 

    require_once __DIR__.'/../PHP/itemEdit.php';

    $editItem  = null;
    $subImages = [];

    if(isset($_SESSION['edit_item_id'])) {
        $editItemId = $_SESSION['edit_item_id'];
        unset($_SESSION['edit_item_id']);
        
        try {
            $editItem  = fncGetProduct($pdo2, $editItemId);
            $subImages = fncGetSubImages($pdo2, $editItemId); 

        } catch(PDOException $e) {
            error_log('データベース接続エラー:' . $e -> getMessage());

            $_SESSION['errors'] = 'データベース接続エラーが発生しました。管理者にお問い合わせください。';
        }
    }
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <?php include 'headTags.php' ?>
    <title>商品編集</title>
    <link rel="stylesheet" href="/../AngeloCheese/Admin/CSS/itemDelete.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="/../AngeloCheese/Admin/CSS/itemEdit.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="/../AngeloCheese/Admin/CSS/itemAdd.css?v=<?php echo time(); ?>">
</head>
<body>
    <form autocomplete="off" method="POST" class="product-form" enctype="multipart/form-data">
        <div class="grid-container">
            <?php include 'sidebar.php'; ?>

            <main>
                <div class="main-container">
                    <h1>商品編集</h1>

                    <a href="itemEditList.php">一覧へ</a>
                    
                    <div class="product-info">
                        <input autocomplete="off" type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                        <input autocomplete="off" type="hidden" name="item_id" value="<?php echo htmlspecialchars($editItem['id'], ENT_QUOTES, 'UTF-8'); ?>">
                        <div class="form-block">
                            <div class="block">
                                <h3>商品表示状態</h3>
                                <label class="user-radio"><input autocomplete="off" type="radio" value="on"  id="on"  name="display" <?php if($editItem['hidden_at'] === NULL) echo 'checked'; ?> >表示</label>
                                <label class="user-radio"><input autocomplete="off" type="radio" value="off" id="off" name="display" <?php if($editItem['hidden_at'] !== NULL) echo 'checked'; ?> >非表示</label>
                            </div>
                            <div class="block">
                                <h3>メイン画像</h3>
                                <div class="preview-container <?php echo isset($editItem['image_path']) ? 'show' : '' ?>">
                                    <?php if(isset($editItem['image_path'])): ?>
                                        <img class="main-img" src="<?php echo htmlspecialchars('/../AngeloCheese/Admin/uploads/' . $editItem['image_path'], ENT_QUOTES, 'UTF-8'); ?>" alt="">
                                        <div class="delete-main-img">✖</div>
                                    <?php endif; ?>
                                </div>
                                <div class="drop-area main-drop">画像をドラッグ&ドロップ、またはクリックで選択</div>
                                <input autocomplete="off" type="file" class="file-input main-file" accept="image/*" name="image">
                            </div>

                            <div class="block">
                                <h3>サブ画像（複数追加可能）</h3>
                                <div class="sub-preview-wrapper">
                                    <?php foreach($subImages as $subImage): ?>
                                        <div class="sub-preview-container <?php echo isset($subImage) ? 'show' : '' ?>">
                                            <img class="sub-img" src="<?php echo htmlspecialchars('/../AngeloCheese/Admin/uploads/' . $subImage, ENT_QUOTES, 'UTF-8'); ?>" alt="">
                                            <div class="delete-sub-img">✖</div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                                <div class="drop-area sub-drop" id="sub-drop-area">画像をドラッグ&ドロップ、またはクリックで選択</div>
                                <input autocomplete="off" type="file" class="sub-file" accept="image/*" name="images[]" multiple>
                            </div>
                        </div>
                    </div>
                    <button type="submit">送信</button>                    
                </div>
            </main>
        </div>
    </form>

    <script type="module" src="/../AngeloCheese/Admin/JS/sidebar.js"></script> <!-- サイドバー -->
    <script type="module" src="/../AngeloCheese/Admin/JS/itemEdit.js"></script> <!-- 編集用 -->
</body>
</html>