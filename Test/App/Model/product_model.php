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

        private function bindProductValues($stmt, $data) {
            $bindings = [
                ':name'                => [$data['name'],               PDO::PARAM_STR],
                ':description'         => [$data['description'],        PDO::PARAM_STR],
                ':category_id'         => [$data['categoryId'],         PDO::PARAM_INT],
                ':category_name'       => [$data['categoryName'],       PDO::PARAM_STR],
                ':keyword'             => [$data['keyword'],            PDO::PARAM_STR],
                ':size1'               => [$data['size1'],              PDO::PARAM_INT],
                ':size2'               => [$data['size2'],              PDO::PARAM_INT],
                ':tax_rate'            => [$data['taxRate'],            PDO::PARAM_STR],
                ':price'               => [$data['price'],              PDO::PARAM_INT],
                ':tax_included_price'  => [$data['taxIncludedPrice'],   PDO::PARAM_INT],
                ':cost'                => [$data['cost'],               PDO::PARAM_INT],
                ':expirationDate_min1' => [$data['expirationDateMin1'], PDO::PARAM_INT],
                ':expirationDate_max1' => [$data['expirationDateMax1'], PDO::PARAM_INT],
                ':expirationDate_min2' => [$data['expirationDateMin2'], PDO::PARAM_INT],
                ':expirationDate_max2' => [$data['expirationDateMax2'], PDO::PARAM_INT],
            ];

            foreach($bindings as $param => [$value, $type]) {
                $stmt->bindValue($param, $value, $type);
            }
        }

        public function saveProduct($data) {
            $stmt = $this->pdo->prepare(
                "INSERT INTO products (name,  description,  category_id, category_name,   keyword,  size1,  size2,  tax_rate,  price,  tax_included_price,  cost,  expirationDate_min1,  expirationDate_max1,  expirationDate_min2,  expirationDate_max2)
                               VALUES (:name, :description, :category_id, :category_name, :keyword, :size1, :size2, :tax_rate, :price, :tax_included_price, :cost, :expirationDate_min1, :expirationDate_max1, :expirationDate_min2, :expirationDate_max2)");
            $this->bindProductValues($stmt, $data);
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

                /********** 同一画像名があれば保存阻止＆置き換え **********/
                $fileHash = hash_file('sha256', $file['tmp_name']); // ハッシュでファイルの重複を確認
                $existingFilePath = null;

                foreach (glob(rtrim($uploadDir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . '*.' . $fileExt) as $existingFile) {
                    if (hash_file('sha256', $existingFile) === $fileHash) {
                        $existingFilePath = $existingFile;
                        break;
                    }
                }

                if ($existingFilePath) {
                    $uploadFilePath = $existingFilePath; // 既存の画像を使用
                } else {
                    $newFileName    = uniqid() . '_' . bin2hex(random_bytes(32)) . '.' . $fileExt;
                    $uploadFilePath = rtrim($uploadDir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $newFileName;

                    if (!move_uploaded_file($file['tmp_name'], $uploadFilePath)) {
                        $errors[] = '画像の保存に失敗しました。';
                        return;
                    }
                }
                /************************* END *************************/

                // 相対パス（DB用）
                $relativePath = '../uploads/' . basename($uploadFilePath);

                // DBへ保存（重複画像でもproduct_imagesには登録する）
                $stmt = $this->pdo->prepare("INSERT INTO product_images (product_id,  image_path, is_main)
                                                                 VALUES (:product_id, :image_path, :is_main)");
                $stmt->bindValue(':product_id', $productId,    PDO::PARAM_INT);
                $stmt->bindValue(':image_path', $relativePath, PDO::PARAM_STR);
                $stmt->bindValue(':is_main',    $isMain,       PDO::PARAM_INT);
                $stmt->execute();
            }
        }

        public function updateProduct($data) {
            $stmt = $this->pdo->prepare(
                "UPDATE products SET
                    name = :name, 
                    description = :description,
                    category_id = :category_id, 
                    category_name = :category_name, 
                    keyword = :keyword, 
                    size1 = :size1, 
                    size2 = :size2, 
                    tax_rate = :tax_rate, 
                    price = :price, 
                    tax_included_price = :tax_included_price,
                    cost = :cost, 
                    expirationDate_min1 = :expirationDate_min1, 
                    expirationDate_max1 = :expirationDate_max1,
                    expirationDate_min2 = :expirationDate_min2,
                    expirationDate_max2 = :expirationDate_max2, 
                    hidden_at = :hidden_at
                WHERE id = :id
            ");
            $this->bindProductValues($stmt, $data);
            $stmt->execute();
        }

        public function updateImage($file, $isMain, $uploadDir, $allowedExt, &$errors, $productId) {
            if ($file && $file['error'] === UPLOAD_ERR_OK) {
                $fileName = preg_replace('/[^a-zA-Z0-9._-]/', '_', basename($file['name']));
                $fileExt  = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

                if (!in_array($fileExt, $allowedExt)) {
                    $errors[] = '許可されていないファイル形式です。';
                    return;
                }

                /********** 同一画像名があれば保存阻止＆置き換え **********/
                $fileHash = hash_file('sha256', $file['tmp_name']); // ハッシュでファイルの重複を確認
                $existingFilePath = null;

                foreach (glob(rtrim($uploadDir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . '*.' . $fileExt) as $existingFile) {
                    if (hash_file('sha256', $existingFile) === $fileHash) {
                        $existingFilePath = $existingFile;
                        break;
                    }
                }

                if ($existingFilePath) {
                    $uploadFilePath = $existingFilePath; // 既存の画像を使用
                } else {
                    $newFileName    = uniqid() . '_' . bin2hex(random_bytes(32)) . '.' . $fileExt;
                    $uploadFilePath = rtrim($uploadDir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $newFileName;

                    if (!move_uploaded_file($file['tmp_name'], $uploadFilePath)) {
                        $errors[] = '画像の更新に失敗しました。';
                        return;
                    }
                }
                /************************* END *************************/

                $relativePath = '../uploads/' . basename($uploadFilePath);

                if($isMain === 1) {
                    $stmt = $this->pdo->prepare("UPDATE product_images SET image_path = :image_path WHERE product_id = :id and is_main = 1");
                    $stmt->bindValue(':image_path', $relativePath, PDO::PARAM_STR);
                    $stmt->bindValue(':id',         $productId,    PDO::PARAM_STR);
                    $stmt->execute();
                }
            }
        }
    }
?>