




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
        // 詳細
        $name               = trim($_POST['product-name'] ?? '');
        $description        = trim($_POST['product-description'] ?? '');
        $category           = (int)($_POST['product-category'] ?? 0);
        $keyword            = trim($_POST['keyword'] ?? '');
        $size1              = (int)($_POST['size1'] ?? 0);
        $size2              = (int)($_POST['size2'] ?? 0);
        $taxrate            = (float)($_POST['tax-rate'] ?? 0.1);
        $price              = (int)($_POST['price'] ?? 0);
        $taxIncludedPrice   = (int)($_POST['tax-included-price'] ?? 0);
        $cost               = (int)($_POST['cost'] ?? 0);
        $expirationdateMin1 = (int)($_POST['expirationdate-min1'] ?? 0);
        $expirationdateMax1 = (int)($_POST['expirationdate-max1'] ?? 0);
        $expirationdateMin2 = (int)($_POST['expirationdate-min2'] ?? 0);
        $expirationdateMax2 = (int)($_POST['expirationdate-max2'] ?? 0);

        // 画像
        $thumbnail = $_FILES['thumbnail'] ?? null;
        $files     = [
            $_FILES['file1'] ?? null,
            $_FILES['file2'] ?? null,
            $_FILES['file3'] ?? null,
            $_FILES['file4'] ?? null,
            $_FILES['file5'] ?? null,
        ];

        // 各入力バリデーション
        if(empty($name))     {  $errors[] = '商品名が入力されていません。'; }
        if(empty($category)) {  $errors[] = 'カテゴリーが選択されていません。'; }
        if(!is_numeric($size1) || $size1 <= 0) {  $errors[] = 'サイズ1には0より大きい数値を入力してください。'; }
        if(!is_numeric($size2) || $size2 <= 0) {  $errors[] = 'サイズ2には0より大きい数値を入力してください。';  }
        if(!is_numeric($price) || $price <= 0) {  $errors[] = '値段には0より大きい数値を入力してください。';  }
        if(!is_numeric($cost) || $cost <= 0)   {  $errors[] = '原価には0より大きい数値を入力してください。';  }
        if($expirationdateMin1 > $expirationdateMax1) {  $errors[] = '消費期限の大小関係が不正です。';  }
        if($expirationdateMin2 > $expirationdateMax2) {  $errors[] = '消費期限(解凍後)の大小関係が不正です。';  }

        /******************** ↓ 画像の保存前処理 ↓ ********************/
        // アップロードディレクトリ設定(無ければ作成)
        $uploadDir = 'uploads/';
        if(!is_dir($uploadDir)) {
            mkdir($uploadDir, 755, true);
        }

        // 許可する拡張子
        $allowedExt = ["jpg", "jpeg", "png"];
        $uploadedPaths = [];

        // 保存トランザクション
        try {
            $pdo2 -> beginTransaction();

            // productsテーブルに保存
            $stmt = $pdo2 -> prepare('insert into products ( name,  description,  category_id,  keyword,  size1,  size2,  tax_rate,  price,  tax_included_price,  cost,  expirationdate_min1,  expirationdate_max1,  expirationdate_min2,  expirationdate_max2)
                                                   values (:name, :description, :category_id, :keyword, :size1, :size2, :tax_rate, :price, :tax_included_price, :cost, :expirationdate_min1, :expirationdate_max1, :expirationdate_min2, :expirationdate_max2)');
            $stmt -> execute([
                ':name'                => $name,
                ':description'         => $description,
                ':category_id'         => $category,
                ':keyword'             => $keyword,
                ':size1'               => $size1,
                ':size2'               => $size2,
                ':tax_rate'            => $taxrate,
                ':price'               => $price,
                ':tax_included_price'  => $taxIncludedPrice,
                ':cost'                => $cost,
                ':expirationdate_min1' => $expirationdateMin1,
                ':expirationdate_max1' => $expirationdateMax1,
                ':expirationdate_min2' => $expirationdateMin2,
                ':expirationdate_max2' => $expirationdateMax2,
            ]);

            // 保存した商品のIDを取得
            $product_id = $pdo2 -> lastInsertId();

            // 画像保存の関数
            function fncSaveImage($file, $is_main, $uploadDir, $allowedExt, &$errors, $pdo2, $product_id) {
                if($file && $file['error'] === UPLOAD_ERR_OK) {
                    // 拡張子を取得＆チェック
                    $fineName = basename($file['name']);
                    $fileExt = strtolower(pathinfo($fineName, PATHINFO_EXTENSION));

                    if(!in_array($fileExt, $allowedExt)) {
                        $errors[] = '許可されていないファイル形式です。';
                        return;
                    }

                    // ファイル名のユニーク化
                    $newFileName = uniqid().bin2hex(random_bytes(32)).'.'.$fileExt;
                    $uploadFilePath = $uploadDir.$newFileName;

                    // 画像を保存
                    if(move_uploaded_file($file['tmp_name'], $uploadFilePath)) {
                        $stmt = $pdo2 -> prepare('insert into product_images ( product_id,  image_path, is_main)
                                                                     values (:product_id, :image_path, :is_main)');
                        $stmt -> execute([
                            ':product_id' => $product_id,
                            ':image_path' => $uploadFilePath,
                            ':is_main'    => $is_main,
                        ]);
                    } else {
                        $errors[] = '画像の保存に失敗しました。';
                    }
                }
            }
            // メイン画像保存(is_main = 1)
            fncSaveImage($thumbnail, 1, $uploadDir, $allowedExt, $errors, $pdo2, $product_id);

            // サブ画像の保存(is_main = null)
            foreach($files as $file) {
                fncSaveImage($file, null, $uploadDir, $allowedExt, $errors, $pdo2, $product_id);
            }

            // エラー無ければコミット、あればロールバック
            if(empty($errors)) {
                $pdo2 -> commit();
                $_SESSION['success'] = '商品が登録されました。';

            } else {
                $pdo2 -> rollback();
                $_SESSION['errors'] = $errors;
            }

        } catch(Exception $e) {
            $pdo2 -> rollback();
            $errors[] = 'データベースエラー'.$e->getMessage();
        }

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
