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

            require '../App/View/all_products_view.php';
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

            $this->model->beginTransaction();
            try {
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
                }

                $thumbnail  = $_FILES['image'] ?? null;
                $subImages  = $_FILES['images'] ?? null;
                $maxSize    = 256000;

                $data = [
                    'name'               => trim($_POST['name'] ?? ''),
                    'description'        => trim($_POST['description'] ?? ''),
                    'categoryId'         => (int)($_POST['category'] ?? 0),
                    'keyword'            => trim($_POST['keyword'] ?? ''),
                    'size1'              => (int)($_POST['size1'] ?? 0),
                    'size2'              => (int)($_POST['size2'] ?? 0),
                    'taxRate'            => (float)($_POST['tax-rate'] ?? 0.1),
                    'price'              => (int)str_replace(',', '', $_POST['price']),
                    'taxIncludedPrice'   => (int)str_replace(',', '', $_POST['tax-included-price']),
                    'cost'               => (int)str_replace(',', '', $_POST['cost']),
                    'expMin1'            => (int)($_POST['expirationDate_min1'] ?? 0),
                    'expMax1'            => (int)($_POST['expirationDate_max1'] ?? 0),
                    'expMin2'            => (int)($_POST['expirationDate_min2'] ?? 0),
                    'expMax2'            => (int)($_POST['expirationDate_max2'] ?? 0),
                ];

                $errors = array_merge($errors, $this->model->validateProductData($data, $thumbnail, $subImages, $maxSize));

                if (!empty($errors)) {
                    $_SESSION['errors'] = $errors;
                    echo './product_add.php';
                    exit;
                }

                $uploadDir = '../uploads/';
                $allowedExt = ['jpg', 'jpeg', 'png'];

                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }

                try {
                    $this->model->beginTransaction();

                    $productId = $this->model->saveProduct($data);
                    $this->model->saveImage($productId, $thumbnail, true, $uploadDir, $allowedExt);

                    foreach ($subImages['name'] as $index => $name) {
                        $file = [
                            'name'     => $subImages['name'][$index],
                            'type'     => $subImages['type'][$index],
                            'tmp_name' => $subImages['tmp_name'][$index],
                            'error'    => $subImages['error'][$index],
                            'size'     => $subImages['size'][$index],
                        ];
                        $this->model->saveImage($productId, $file, false, $uploadDir, $allowedExt);
                    }

                    $this->model->commit();
                    echo './product_add.php';
                    exit;

                } catch (PDOException $e) {
                    $this->model->rollBack();
                    error_log("商品登録失敗：" . $e->getMessage());
                    $_SESSION['errors'] = ['商品登録中にエラーが発生しました。' . $e->getMessage()];
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