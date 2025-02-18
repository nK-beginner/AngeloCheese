




<?php
    session_start();
    require_once __DIR__ . '/backend/connection.php';
    require_once __DIR__ . '/backend/csrf_token.php';

    $errors = $_SESSION['errors'] ?? [];

    unset($_SESSION['errors']);

    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        // CSRFトークンチェック
        if(!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            die('CSRFトークン不一致エラー');
        }

        // CSRFトークン再生成
        unset($_SESSION['csrf_token']);
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

        // フォームデータ取得とサニタイズ
        $name              = trim($_POST['product-name'] ?? '');
        $description       = trim($_POST['product-description'] ?? '');
        $category          = (int)($_POST['product-category'] ?? 0);
        $keyword           = trim($_POST['keyword'] ?? '');
        $size1             = (int)($_POST['size1'] ?? 0);
        $size2             = (int)($_POST['size2'] ?? 0);
        $taxrate           = (float)($_POST['tax-rate'] ?? 0.1);
        $price             = (int)($_POST['price'] ?? 0);
        $taxIncludedPrice  = (int)($_POST['tax-included-price'] ?? 0);
        $cost              = (int)($_POST['cost'] ?? 0);
        $exprationdateMin1 = (int)($_POST['expirationdate-min1'] ?? 0);
        $exprationdateMax1 = (int)($_POST['expirationdate-max1'] ?? 0);
        $exprationdateMin2 = (int)($_POST['expirationdate-min2'] ?? 0);
        $exprationdateMax2 = (int)($_POST['expirationdate-max2'] ?? 0);
        // $thumbnail         = $_FILES['thumbnail']['name'] ?? null;
        // $file1             = $_FILES['file1']['name'] ?? null;
        // $file2             = $_FILES['file2']['name'] ?? null;
        // $file3             = $_FILES['file3']['name'] ?? null;
        // $file4             = $_FILES['file4']['name'] ?? null;
        // $file5             = $_FILES['file5']['name'] ?? null;

        // $files             = [$file1, $file2, $file3, $file4, $file5];

        // 各入力バリデーション
        // 商品名
        if(empty($name)) {
            $errors[] = '商品名が入力されていません。';
        }

        // カテゴリー
        if(empty($category)) {
            $errors[] = 'カテゴリーが選択されていません。';
        }
        
        // サイズ1
        if(!is_numeric($size1) || $size1 <= 0) {
            $errors[] = 'サイズ1には0より大きい数値を入力してください。';
        }

        // サイズ2
        if(!is_numeric($size2) || $size2 <= 0) {
            $errors[] = 'サイズ2には0より大きい数値を入力してください。';
        }

        // 値段
        if(!is_numeric($price) || $price <= 0) {
            $errors[] = '値段には0より大きい数値を入力してください。';
        }

        // 原価
        if(!is_numeric($cost) || $cost <= 0) {
            $errors[] = '原価には0より大きい数値を入力してください。';
        }

        // 消費期限1
        if($exprationdateMin1 > $exprationdateMax1) {
            $errors[] = '消費期限の大小関係が不正です。';
        }

        // 消費期限2
        if($exprationdateMin2 > $exprationdateMax2) {
            $errors[] = '消費期限(解凍後)の大小関係が不正です。';
        }

        /******************** ↓ 画像の保存前処理 ↓ ********************/
        // アップロードディレクトリ設定
        $uploadDir = 'uploads/';

        // フォルダが無ければ作成
        if(!is_dir($uploadDir)) {
            mkdir('$uploadDir', 777, true);
        }

        // メイン画像
        if($thumbnail && $thumbnail['error'] !== UPLOAD_ERR_OK) {
            $errors[] = 'メイン画像のアップロードに失敗しました。';
        }

        // サブ画像
        foreach($files as $file) {
            if($files[$file]['error'] !== UPLOAD_ERR_OK) {
                $errors[] = '画像のアップロードに失敗しました。';
            }
        }

        // ファイル情報
        $thumbnailName = basename($thumbnail);
        $fileExt       = strtolower(pathinfo($thumbnailName, PATHINFO_EXTENTION));
        $allowedExt    = ["jpg", "jped", "png", "gif"];

        // 拡張子チェック
        if(!in_array($fileExt, $allowedExt)) {
            $errrors[] = '許可されていないファイル形式です。';
        }

        // ファイル名をランダムな値に
        $newThumbnailName = uniqid().'.'.$fileExt;
        $oploadFilePath   = $uploadDir.$newThumbnailName;

        // ファイル移動
        if(!move_uploaded_file($thumbnail, $uploadFilePath)) {
            $errors[] = 'ファイルの保存に失敗しました。';
        }
        /******************** ↑ 画像の保存前処理 ↑ ********************/

        // エラーあったらフォームへリダイレクト
        if(!empty($errors)) {
            $_SESSION['errors'] = $errors;
            header('Location: itemAdd.php');
            exit;
        }

        // 商品詳細登録処理
        $stmt = $pdo -> prepare('insert into products (name,  description,  category_id,  keyword,  size1,  size2,  tax_rate,  price,  tax_included_price,  cost,  expirationdate_min1,  expirationdate_max1,  expirationdate_min2,  expirationdate_max2)
                                               values (:name, :description, :category_id, :keyword, :size1, :size2, :tax_rate, :price, :tax_included_price, :cost, :expirationdate_min1, :expirationdate_max1, :expirationdate_min2, :expirationdate_max2)');
        $stmt -> bindValue(':name',                $name,              PDO::PARAM_STR);
        $stmt -> bindValue(':description',         $description,       PDO::PARAM_STR);
        $stmt -> bindValue(':category_id',         $category,          PDO::PARAM_INT);
        $stmt -> bindValue(':keyword',             $keyword,           PDO::PARAM_STR);
        $stmt -> bindValue(':size1',               $size1,             PDO::PARAM_INT);
        $stmt -> bindValue(':size2',               $size2,             PDO::PARAM_INT);
        $stmt -> bindValue(':tax_rate',            $taxrate,           PDO::PARAM_STR);
        $stmt -> bindValue(':price',               $price,             PDO::PARAM_INT);
        $stmt -> bindValue(':tax_included_price',  $taxIncludedPrice,  PDO::PARAM_INT);
        $stmt -> bindValue(':cost',                $cost,              PDO::PARAM_INT);
        $stmt -> bindValue(':expirationdate_min1', $exprationdateMin1, PDO::PARAM_INT);
        $stmt -> bindValue(':expirationdate_max1', $exprationdateMax1, PDO::PARAM_INT);
        $stmt -> bindValue(':expirationdate_min2', $exprationdateMin2, PDO::PARAM_INT);
        $stmt -> bindValue(':expirationdate_max2', $exprationdateMax2, PDO::PARAM_INT);
        $stmt -> execute();

        // 商品画像登録処理
        // $stmt -> $pdo = prepare('insert into product_image ()');
        /* 
        
        
        */

        // セッション固定攻撃対策
        session_regenerate_id(true);

        header('Location: itemAdd.php');
        exit;
    }

?>


<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>商品追加</title>
    <!-- 商品追加用CSS -->
    <link rel="stylesheet" href="CSS/itemAdd.css?v=<?php echo time(); ?>">
</head>
<body>
    <form action="itemAdd.php" method="POST" enctype="multipart/form-data">
        <!-- CSRFトークン -->
        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">

        <div class="grid-container">

            <?php include 'sidebar.php'; ?>

            <!-- 商品詳細エリア -->
            <main>
                <h1>商品追加</h1>
                <div class="product-info">
                    <!-- 商品名など -->
                    <div class="form-block">
                        <!-- CSRFトークン -->
                        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                        
                        <!-- エラーメッセージ -->
                        <?php if(!empty($errors)): ?>
                            <div class="error-msg">
                                <?php foreach($errors as $error): ?>
                                    <p><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></p>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>

                        <!-- 商品名 -->
                        <div class="block">
                            <label for="product-name">商品名</label>
                            <input type="text" class="product-name" id="product-name" name="product-name" placeholder="例：チーズケーキサンド" required>                        
                        </div>

                        <!-- 商品説明 -->
                        <div class="block">
                            <label for="product-description" class="label">商品説明</label>
                            <textarea class="product-description" id="product-description" name="product-description" required></textarea>                        
                        </div>

                        <!-- 商品カテゴリー -->
                        <div class="block">
                            <label for="product-category" class="label">商品カテゴリー</label>
                            <select class="product-category" name="product-category" id="product-category">
                                <option value="0" selected>選択してください。</option>
                                <option value="1">人気商品</option>
                                <option value="2">チーズケーキサンド</option>
                                <option value="3">アンジェロチーズ</option>
                                <option value="99">その他</option>
                            </select>
                        </div>
                        
                        <!-- キーワード -->
                        <div class="block">
                            <label for="keyword" class="label">キーワード</label>
                            <input type="text" class="keyword" id="keyword" name="keyword" placeholder="例：北海道産">                        
                        </div>

                        <!-- サイズ -->
                        <div class="size-title">
                            <label for="size1" class="label">サイズ(cm)</label>
                        </div>
                        <div class="flex-block size-block">
                            <div class="block">
                                <input type="text" class="size" id="size1" name="size1" inputmode="numeric" placeholder="例：15" maxlength="3" required>
                            </div>
                            <p>✖</p>
                            <div class="block">
                                <input type="text" class="size" id="size2" name="size2" inputmode="numeric" placeholder="例：10" maxlength="3" required>                        
                            </div> 
                        </div>
                    </div>

                    <!-- 金額など -->
                    <div class="form-block">
                        <div class="">
                            <label>税率</label>
                            <div class="tax-rate">
                                <label><input type="radio" value="0.1"  pattern="\d*" class="tax" id="tax10" name="tax-rate" checked>10%</label>
                                <label><input type="radio" value="0.08" pattern="\d*" class="tax" id="tax8"  name="tax-rate">8%</label>
                            </div>
                        </div>
                        <div class="flex-block price-block">
                            <!-- 値段 -->
                            <div class="block">
                                <label for="price" class="label">値段</label>
                                <input type="text" pattern="\d*" class="price" id="price" name="price" inputmode="numeric" maxlength="5" required>                        
                            </div>

                            <!-- 税込み価格（自動計算） -->
                            <div class="block">
                                <label for="tax-included-price" class="label">税込み価格</label>
                                <!-- 表示用（ユーザーが編集できない） -->
                                <input type="text" class="tax-included-price" id="tax-included-price-display" readonly value="¥0">
                                <!-- データ送信用（hidden） -->
                                <input type="hidden" id="tax-included-price" name="tax-included-price" value="0">
                            </div>

                            <!-- 原価 -->
                            <div class="block">
                                <label for="cost" class="label">原価</label>
                                <input type="text" pattern="\d*" class="cost" id="cost" name="cost" inputmode="numeric" maxlength="5" required>                        
                            </div>
                        </div>
                    </div>

                    <!-- 消費期限 -->
                    <div class="form-block expiration">
                        <!-- 消費期限解凍前 -->
                        <div class="before">
                            <label for="expirationdate">消費期限</label>
                            <div class="flex-block date-block">
                                <div class="firstdate">
                                    <input type="text" name="expirationdate-min1"  maxlength="3" inputmode="numeric"><p>～</p>
                                </div>
                                <div class="lastdate">
                                    <input type="text" name="expirationdate-max1" maxlength="3" inputmode="numeric"><p>日間</p>
                                </div>
                            </div>
                        </div>

                        <!-- 消費期限解凍後 -->
                        <div class="after">
                            <label for="expirationdate">消費期限(解凍後)</label>
                            <div class="flex-block date-block">
                                <div class="firstdate">
                                    <input type="text" name="expirationdate-min2" maxlength="3" inputmode="numeric"><p>～</p>
                                </div>
                                <div class="lastdate">
                                    <input type="text" name="expirationdate-max2" maxlength="3" inputmode="numeric"><p>日間</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>

            <!-- 写真投稿エリア -->
            <aside class="photo-drop-area">
                <div class="photo-drop-container">
                    <!-- メイン画像 -->
                    <h3 class="thumbnail">メイン画像</h3>
                    <div class="drop-area">
                        <p id="drop-text">ここに画像をドロップ<br>または</p>
                        <label for="thumbnail" class="custom-button">ファイルを選択</label>
                        <input type="file" name="thumbnail" class="file-input" id="thumbnail" accept="image/*" hidden>
                    </div>

                    <h3 class="subtitle">サブ画像<span>(ドラッグアンドドロップで追加できます。)</span></h3>
                    <div class="flex-block">

                        <!-- サブ画像 1 -->
                        <div class="drop-area">
                            <label for="file1" class="custom-button">+</label>
                            <input type="file" name="file1" class="file-input" id="file1" accept="image/*" hidden>
                        </div>

                        <!-- サブ画像 2 -->
                        <div class="drop-area">
                            <label for="file2" class="custom-button">+</label>
                            <input type="file" name="file2" class="file-input" id="file2" accept="image/*" hidden>
                        </div>

                        <!-- サブ画像 3 -->
                        <div class="drop-area">
                            <label for="file3" class="custom-button">+</label>
                            <input type="file" name="file3" class="file-input" id="file3" accept="image/*" hidden>
                        </div>

                        <!-- サブ画像 4 -->
                        <div class="drop-area">
                            <label for="file4" class="custom-button">+</label>
                            <input type="file" name="file4" class="file-input" id="file4" accept="image/*" hidden>
                        </div>

                        <!-- サブ画像 5 -->
                        <div class="drop-area">
                            <label for="file5" class="custom-button">+</label>
                            <input type="file" name="file5" class="file-input" id="file5" accept="image/*" hidden>
                        </div>
                    </div>

                    <!-- ボタン -->
                    <div class="flex-btns">
                        <input type="reset" class="cancelbtn" value ="キャンセル"></input>
                        <input type="submit" class="savebtn" value ="保存"></input>
                    </div>
                </div>
            </aside>
        </div>
    </form>
    <script type="text/javascript" src="script.js"></script>
</body>
</html>
