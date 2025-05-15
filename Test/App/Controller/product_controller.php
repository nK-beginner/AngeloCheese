<?php
    require_once __DIR__.'/../Model/product_model.php';
    require_once __DIR__.'/../../Utils/functions.php';

    class ProductController {
        private $model;

        public function __construct($pdo) {
            $this->model = new Product($pdo);
        }

        private function renderProductList($viewFile) {
            $products = $this->model->getAllProducts();

            foreach($products as &$product) {
                $mainImg = $this->model->getProductMainImg($product['id']);
                $product['main_img'] = $mainImg['image_path'] ?? null;
            }
            unset($product); // 参照渡しの解放：これがないと同じ商品が重複して表示されるので消さないこと

            require $viewFile;
        }

        public function listProducts() {
            $this->renderProductList('../App/View/product_editList_view.php');
        }

        public function allProducts() {
            $products = $this->model->getNameAndPrice();

            $categorizedProducts = [];
            foreach($products as &$product) {
                $mainImg = $this->model->getProductMainImg($product['id']);
                $product['main_img'] = $mainImg['image_path'] ?? null;

                $category = $product['category_name'] ?? '未分類';
                $categorizedProducts[$category][] = $product;
            }
            unset($product); // 参照渡しの解放

            require_once __DIR__.'/../View/all_products_view.php';
        }

        public function listProductsForDelete() {
            $this->renderProductList('../App/View/product_delete_view.php');
        }

        public function deleteProducts() {
            $errors = $_SESSION['errors'] ?? [];

            unset($_SESSION['errors']);

            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                header('Location: product_delete_view.php');
                exit;
            }

            if(!fncCheckCSRF()) {
                $errors[] = '不正アクセスです。';
                $_SESSION['errors'] = $errors;
                header('Location: product_delete.php');
                exit;
            }

            $deleteIds = $_POST['delete'] ?? [];

            try {
                $this->model->beginTransaction();
                $this->model->hideProducts($deleteIds);
                $this->model->commit();

            } catch (PDOException $e) {
                $this->model->rollBack();
                error_log('論理削除エラー: ' . $e->getMessage());
                $errors[] = '削除処理中にエラーが発生しました。';
            }

            if(!empty($errors)) {
                $_SESSION['errors'] = $errors;
            }

            header('Location: product_delete.php');
            exit;
        }

        public function addProduct() {
            $errors = [];

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                if (!isset($_POST['csrf_token'], $_SESSION['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
                    $errors[] = "不正なアクセスです。";
                    $_SESSION['errors'] = $errors;
                    echo './product_add.php';
                    exit;
                }

                $thumbnail   = $_FILES['image'] ?? null;
                $subImages   = $_FILES['images'] ?? null;
                $maxFileSize = 256000;

                // 文字データ
                $name               = trim($_POST['name'] ?? '');
                $description        = trim($_POST['description'] ?? '');
                $categoryId         = (int)($_POST['category'] ?? 0);
                $categoryMap        = [
                    1  => '人気商品',
                    2  => 'チーズケーキサンド',
                    3  => 'アンジェロチーズ',
                    99 => 'その他',
                ];
                $categoryName       = array_key_exists($categoryId, $categoryMap) ? $categoryMap[$categoryId] : null;
                $keyword            = trim($_POST['keyword'] ?? '');
                $size1              = (int)($_POST['size1'] ?? 0);
                $size2              = (int)($_POST['size2'] ?? 0);
                $taxRate            = (float)($_POST['tax-rate'] ?? 0.1);
                $price              = (int)str_replace(',', '', $_POST['price']);
                $taxIncludedPrice   = (int)str_replace(',', '', $_POST['tax-included-price']);
                $cost               = (int)str_replace(',', '', $_POST['cost']);
                $expirationDateMin1 = (int)($_POST['expiration-date-min1'] ?? 0);
                $expirationDateMax1 = (int)($_POST['expiration-date-max1'] ?? 0);
                $expirationDateMin2 = (int)($_POST['expiration-date-min2'] ?? 0);
                $expirationDateMax2 = (int)($_POST['expiration-date-max2'] ?? 0);                

                if($thumbnail['size'] > $maxFileSize) {         $errors[] = 'メイン画像のファイルサイズが大きすぎます。上限は250KB以下です。'; }
                foreach ($subImages['size'] as $index => $size) {
                    if ($size > $maxFileSize) {                 $errors[] = "サブ画像" . ($index + 1) . "のファイルサイズが大きすぎます。上限は250KB以下です。"; }
                }
                if(empty($name))       {                        $errors[] = '商品名が入力されていません。'; }
                if($categoryId === 0) {                         $errors[] = 'カテゴリーが選択されていません。'; } 
                    elseif(empty($categoryId)) {                $errors[] = 'カテゴリーが空'; }
                    elseif(is_null($categoryName)) {            $errors[] = 'カテゴリーがNull'; }
                if(!is_numeric($size1) || $size1 <= 0) {        $errors[] = 'サイズ1には0より大きい数値を入力してください。'; }
                if(!is_numeric($size2) || $size2 <= 0) {        $errors[] = 'サイズ2には0より大きい数値を入力してください。';  }
                if(!is_numeric($price) || $price <= 0) {        $errors[] = '値段には0より大きい数値を入力してください。';  }
                if(!is_numeric($cost)  || $cost  <= 0) {        $errors[] = '原価には0より大きい数値を入力してください。';  }
                if($expirationDateMin1 > $expirationDateMax1) { $errors[] = '消費期限の大小関係が不正です。';  }
                if($expirationDateMin2 > $expirationDateMax2) { $errors[] = '消費期限(解凍後)の大小関係が不正です。';  }

                if (!empty($errors)) {
                    $_SESSION['errors'] = $errors;
                    echo './product_add.php';
                    exit;
                }

                $data = [
                    'name'               => $name ,
                    'description'        => $description ,
                    'categoryId'         => $categoryId ,
                    'categoryName'       => $categoryName ,
                    'keyword'            => $keyword ,
                    'size1'              => $size1,
                    'size2'              => $size2,
                    'taxRate'            => $taxRate ,
                    'price'              => $price ,
                    'taxIncludedPrice'   => $taxIncludedPrice ,
                    'cost'               => $cost ,
                    'expirationDateMin1' => $expirationDateMin1 ,
                    'expirationDateMax1' => $expirationDateMax1 ,
                    'expirationDateMin2' => $expirationDateMin2 ,
                    'expirationDateMax2' => $expirationDateMax2 ,
                ];

                unset($_SESSION['csrf_token']);
                $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
                
                $uploadDir = realpath(__DIR__ . '/../../Public/uploads');
                if ($uploadDir === false) {
                    $uploadDir = __DIR__ . '/../../Public/uploads';
                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0755, true);
                    }
                }
                $uploadDir = rtrim($uploadDir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
                
                // 末尾スラッシュを付けておく（Model内で補正してもOK）
                $uploadDir = rtrim($uploadDir, '/') . '/';

                $allowedExt = ['jpg', 'jpeg', 'png'];

                try {
                    $this->model->beginTransaction();

                    $productId = $this->model->saveProduct($data);
                    $this->model->saveImage($thumbnail, 1, $uploadDir, $allowedExt, $errors, $productId);

                    foreach ($subImages['name'] as $index => $name) {
                        $file = [
                            'name'     => $subImages['name'][$index],
                            'type'     => $subImages['type'][$index],
                            'tmp_name' => $subImages['tmp_name'][$index],
                            'error'    => $subImages['error'][$index],
                            'size'     => $subImages['size'][$index],
                        ];
                        $this->model->saveImage($file, null, $uploadDir, $allowedExt, $errors, $productId);
                    }

                    $this->model->commit();
                    echo './product_add.php';
                    exit;

                } catch (PDOException $e) {
                    $this->model->rollBack();
                    error_log("商品登録失敗：" . $e->getMessage());
                    $_SESSION['errors'] = '商品登録中にエラーが発生しました。';

                    echo './product_add.php';
                    exit;
                }
            }
        }




















        
        public function showDetail($id) {
            $product = $this->model->getProductById($id);
            
            if ($product) {
                require '../App/View/product_detail.php';
            } else {
                echo "商品が見つかりませんでした。";
            }
        }
    }
?>