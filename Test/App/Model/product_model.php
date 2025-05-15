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

        public function hideProducts(array $ids): void {
            if (empty($ids)) return;

            $now = date('Y-m-d H:i:s');
            $placeholders = implode(',', array_fill(0, count($ids), '?'));

            $sql = "UPDATE products SET hidden_at = ? WHERE id IN ($placeholders)";
            $stmt = $this->pdo->prepare($sql);

            $params = array_merge([$now], $ids);
            $stmt->execute($params);
        }

        public function validateProductData(array $data, $thumbnail, $subImages, int $maxSize): array {
            $errors = [];

            // if ($thumbnail['size'] > $maxSize) {
            //     $errors[] = 'メイン画像のファイルサイズが大きすぎます(最大250KB)。';
            // }

            // foreach ($subImages['size'] as $i => $size) {
            //     if ($size > $maxSize) {
            //         $errors[] = 'サブ画像' . ($i + 1) . 'のファイルサイズが大きすぎます(最大250KB)。';
            //     }
            // }

            if (empty($data['name'])) { $errors[] = '商品名が入力されていません。'; }
            if (empty($data['categoryId'])) { $errors[] = 'カテゴリーが選択されていません。'; }
            if ($data['size1'] <= 0) { $errors[] = 'サイズ1は0より大きくしてください。'; }
            if ($data['size2'] <= 0) { $errors[] = 'サイズ2は0より大きくしてください。'; }
            if ($data['price'] <= 0) { $errors[] = '値段は0より大きくしてください。'; }
            if ($data['cost'] <= 0) { $errors[] = '原価は0より大きくしてください。'; }
            if ($data['expMin1'] > $data['expMax1']) { $errors[] = '消費期限(1)の大小が逆です。'; }
            if ($data['expMin2'] > $data['expMax2']) { $errors[] = '消費期限(2)の大小が逆です。'; }

            return $errors;
        }

        public function saveProduct(array $data): int {
            $stmt = $this->pdo->prepare(
                "INSERT INTO products (name, description, category_id, keyword, size1, size2, tax_rate, price, tax_included_price, cost, expirationDate_min1, expirationDate_max1, expirationDate_min2, expirationDate_max2, category_name)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");

            $categoryMap = [
                1 => '人気商品', 2 => 'チーズケーキサンド', 3 => 'アンジェロチーズ', 99 => 'その他'
            ];
            $categoryName = $categoryMap[$data['categoryId']] ?? '不明';

            $stmt->execute([
                $data['name'], $data['description'], $data['categoryId'], $data['keyword'], $data['size1'],
                $data['size2'], $data['taxRate'], $data['price'], $data['taxIncludedPrice'], $data['cost'],
                $data['expMin1'], $data['expMax1'], $data['expMin2'], $data['expMax2'], $categoryName
            ]);

            return $this->pdo->lastInsertId();
        }

        public function saveImage(int $productId, array $file, bool $isMain, string $uploadDir, array $allowedExt): void {
            if ($file && $file['error'] === UPLOAD_ERR_OK) {
                $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
                if (!in_array($ext, $allowedExt)) {
                    throw new RuntimeException("許可されていない拡張子です: $ext");
                }

                $fileName = uniqid('img_', true) . '.' . $ext;
                $filePath = $uploadDir . $fileName;

                if (!move_uploaded_file($file['tmp_name'], $filePath)) {
                    throw new RuntimeException("画像のアップロードに失敗しました。");
                }

                $stmt = $this->pdo->prepare("INSERT INTO product_images (product_id, image_path, is_main) VALUES (?, ?, ?)");
                $stmt->execute([$productId, $filePath, $isMain ? 1 : 0]);
            }
        }
    }
?>