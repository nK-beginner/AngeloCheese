<?php
	/*======================================================*/
	/* 用途：CSRFトークンチェック               			  */
	/* 引数：なし                                            */
	/* 戻り値：なし											 */
	/* 備考：なし											 */
	/*======================================================*/
    function fncCheckCSRF() {
        if(!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            return false;
        }
    
        // CSRFトークン再生成
        unset($_SESSION['csrf_token']);
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        return true;
    }

	/*======================================================*/
	/* 用途：セッション状態チェック               			  */
	/* 引数：なし                                            */
	/* 戻り値：なし											 */
	/* 備考：なし											 */
	/*======================================================*/
    function fncCheckSession() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if(!isset($_SESSION['adminId'])) {
            header('Location: ../Public/admin_login.php');
            exit;
        }
    }

	/*======================================================*/
	/* 用途：登録・ログイン試行回数監視						   */
	/* 引数：$key：failed_login_またはfailed_register_, $limit：ログイン回数, $lockTime：ロック時間, $isFailed：失敗したかどうか */
	/* 戻り値：失敗時・試行回数オーバー時にtrue				   */
	/* 備考：なし											 */
	/*======================================================*/
    function fncManageAttempts($key, $limit = 5, $lockTime = 900, $isFailed = false) {
        if (!isset($_SESSION[$key])) {
            $_SESSION[$key] = ['count' => 0, 'last_attempt' => 0];
        }

        // 失敗記録
        if ($isFailed) {
            $_SESSION[$key]['count']++;
            $_SESSION[$key]['last_attempt'] = time();
            return;
        }

        // 試行回数制限チェック
        if ($_SESSION[$key]['count'] >= $limit) {
            if (time() - $_SESSION[$key]['last_attempt'] < $lockTime) {
                return true;

            } else {
                unset($_SESSION[$key]);
            }
        }
        return false;
    }

	/*======================================================*/
	/* 用途：ユーザー情報をDBから取得                		   */
	/* 引数：$pdo：DB接続, ($filename：ファイル名、デフォルト指定あり) */
	/* 戻り値：SQL実行結果									  */
	/* 備考：なし											 */
	/*======================================================*/
    function exportCSV(PDO $pdo, $filename = 'products.csv') {
        $stmt = $pdo -> prepare("SELECT * FROM products");
        $stmt->execute();
        $products = $stmt -> fetchAll(PDO::FETCH_ASSOC);

        ob_clean();

        header('Content-Type: text/csv; charset=shift_JIS');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        $output = fopen('php://output', 'w');

        $headers = [
            '商品ID', '商品名', '商品説明', 'カテゴリー名', 'キーワード', 'サイズ1', 'サイズ2', '税率', '値段', '税込価格', '原価', 
            '消費期限1', '消費期限2', '消費期限(解凍後)1', '消費期限(解凍後)2', '作成日', '更新日', '商品表示状態'
        ];
        fputcsv($output, array_map(fn($v) => mb_convert_encoding($v, 'SJIS-win', 'UTF-8'), $headers));

        foreach($products as $product) {
            fputcsv($output, array_map(fn($v) => mb_convert_encoding($v, 'SJIS-win', 'UTF-8'), [
                $product['id'],
                $product['name'],
                $product['description'],
                $product['category_name'],
                $product['keyword'],
                $product['size1'] . 'cm',
                $product['size2'] . 'cm',
                $product['tax_rate'] * 100 . '%',
                '¥' . number_format($product['price']),
                '¥' . number_format($product['tax_included_price']),
                '¥' . number_format($product['cost']),
                $product['expirationDate_min1'] . '日',
                $product['expirationDate_max1'] . '日',
                $product['expirationDate_min2'] . '日',
                $product['expirationDate_max2'] . '日',
                $product['created_at'],
                $product['updated_at'],
                !is_null($product['hidden_at']) ? '非表示中' : '',
            ]));
        }

        fclose($output);
        exit;
    }
?>