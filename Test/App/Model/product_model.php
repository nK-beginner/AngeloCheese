<?php
    class Product {
        private $pdo;

        public function __construct($pdo) {
            $this->pdo = $pdo;
        }

        public function beginTransaction() {
            $this->pdo->beginTransaction();
        }

        public function commit() {
            $this->pdo->commit();
        }

        public function rollBack() {
            $this->pdo->rollBack();
        }

        public function getAllProducts() {
            $stmt = $this->pdo->prepare("SELECT * FROM products");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        public function getProductById($id) {
            $stmt = $this->pdo->prepare("SELECT * FROM products WHERE id = :id");
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }

        public function getNameAndPrice() {
            $stmt = $this->pdo->prepare("SELECT id, name, tax_included_price, category_name, hidden_at FROM products");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        public function getProductMainImg($id) {
            $stmt = $this->pdo->prepare("SELECT image_path FROM product_images WHERE product_id = :id AND is_main = 1");
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }

        public function getProductSubImgs($id) {
            $stmt = $this->pdo->prepare("SELECT image_path FROM product_images WHERE product_id = :id AND is_main = NULL");
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        public function hideProducts($ids) {
            if (empty($ids)) { return; }
            $placeholders = implode(',', array_fill(0, count($ids), '?'));
            $stmt = $this->pdo->prepare("UPDATE products SET hidden_at = NOW() WHERE id IN ($placeholders)");
            $stmt->execute($ids);
        }

        public function saveProduct($data) {
            $stmt = $this->pdo->prepare('INSERT INTO products (name,  description,  category_id, category_name,   keyword,  size1,  size2,  tax_rate,  price,  tax_included_price,  cost,  expirationDate_min1,  expirationDate_max1,  expirationDate_min2,  expirationDate_max2)
                                                       VALUES (:name, :description, :category_id, :category_name, :keyword, :size1, :size2, :tax_rate, :price, :tax_included_price, :cost, :expirationDate_min1, :expirationDate_max1, :expirationDate_min2, :expirationDate_max2)');
            $stmt->bindValue(':name'               , $data['name'],               PDO::PARAM_STR);
            $stmt->bindValue(':description'        , $data['description'],        PDO::PARAM_STR);
            $stmt->bindValue(':category_id'        , $data['categoryId'],         PDO::PARAM_INT);
            $stmt->bindValue(':category_name'      , $data['categoryName'],       PDO::PARAM_STR);
            $stmt->bindValue(':keyword'            , $data['keyword'],            PDO::PARAM_STR);
            $stmt->bindValue(':size1'              , $data['size1'],              PDO::PARAM_INT);
            $stmt->bindValue(':size2'              , $data['size2'],              PDO::PARAM_INT);
            $stmt->bindValue(':tax_rate'           , $data['taxRate'],            PDO::PARAM_STR);
            $stmt->bindValue(':price'              , $data['price'],              PDO::PARAM_INT);
            $stmt->bindValue(':tax_included_price' , $data['taxIncludedPrice'],   PDO::PARAM_INT);
            $stmt->bindValue(':cost'               , $data['cost'],               PDO::PARAM_INT);
            $stmt->bindValue(':expirationDate_min1', $data['expirationDateMin1'], PDO::PARAM_INT);
            $stmt->bindValue(':expirationDate_max1', $data['expirationDateMax1'], PDO::PARAM_INT);
            $stmt->bindValue(':expirationDate_min2', $data['expirationDateMin2'], PDO::PARAM_INT);
            $stmt->bindValue(':expirationDate_max2', $data['expirationDateMax2'], PDO::PARAM_INT);
            $stmt->execute();

            return $this->pdo->lastInsertId();
        }

        public function saveImage($file, $isMain, $uploadDir, $allowedExt, &$errors, $productId) {
            if ($file && $file['error'] === UPLOAD_ERR_OK) {
                $fileName = preg_replace('/[^a-zA-Z0-9._-]/', '_', basename($file['name']));
                $fileExt  = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

                if (!in_array($fileExt, $allowedExt)) {
                    $errors[] = '許可されていないファイル形式です。';
                    return;
                }

                $newFileName = uniqid() . '_' . bin2hex(random_bytes(32)) . '.' . $fileExt;

                // 保存用のフルパス
                $uploadFilePath = rtrim($uploadDir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $newFileName;

                // 表示用の相対パス（DB保存用）
                $relativePath = '../uploads/' . $newFileName;

                if (move_uploaded_file($file['tmp_name'], $uploadFilePath)) {
                    $stmt = $this->pdo->prepare("INSERT INTO product_images (product_id, image_path, is_main)
                                                                     VALUES (:product_id, :image_path, :is_main)");
                    $stmt->bindValue(':product_id', $productId,    PDO::PARAM_INT);
                    $stmt->bindValue(':image_path', $relativePath, PDO::PARAM_STR);
                    $stmt->bindValue(':is_main',    $isMain,       PDO::PARAM_INT);
                    $stmt->execute();
                } else {
                    $errors[] = '画像の保存に失敗しました。';
                }
            }
        }
    }
?>