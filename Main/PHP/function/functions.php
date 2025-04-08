<?php
	/*======================================================*/
	/* 用途：ログイン状態チェック							  */
	/* 引数：なし                                            */
	/* 戻り値：なし											 */
	/* 備考：なし											 */
	/*======================================================*/
    function fncCheckStatus() {
        if (isset($_SESSION['user_id']) || isset($_COOKIE['remember_token'])) {
            header('Location: ../View/onlineShop.php');
            exit;
        }
    }

	/*======================================================*/
	/* 用途：セッション状態の確認。無ければ開始 				*/
	/* 引数：なし                                            */
	/* 戻り値：なし											 */
	/* 備考：なし											 */
	/*======================================================*/
    function fncSessionCheck() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    // 登録・ログイン試行回数監視
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
	/* 用途：ユーザー情報をセッションに保存					   */
	/* 引数：なし                                            */
	/* 戻り値：なし											 */
	/* 備考：なし											 */
	/*======================================================*/
    function fncSaveToSession($user, $userId = null) {
        session_regenerate_id(true);

        $_SESSION['user_id']   = $userId ?? $user['id'];
        $_SESSION['firstName'] = $user['firstName'];
        $_SESSION['lastName']  = $user['lastName'];
        $_SESSION['email']     = $user['email'];
    }

	/*======================================================*/
	/* 用途：ユーザー情報をDBから取得（ログイン用） 			*/
	/* 引数：$pdo：DB接続, $email：$_POSTされたemail          */
	/* 戻り値：SQL実行結果									  */
	/* 備考：なし											 */
	/*======================================================*/
    function fncGetUserByEmail($pdo, $email) {
        $stmt = $pdo -> prepare("SELECT id, firstName, lastName, email, password, deleted_at FROM test_users WHERE email = :email LIMIT 1");
        $stmt -> bindValue(':email', $email, PDO::PARAM_STR);
        $stmt -> execute();
        return $stmt -> fetch(PDO::FETCH_ASSOC);
    }

	/*======================================================*/
	/* 用途：ユーザー登録        							  */
	/* 引数：$pdo：DB接続, 
            $firstName：$_POSTされたfirstName, 
            $lastName：$_POSTされたlastName, 
            $email：$_POSTされたemail, 
            $hashedPassword：ハッシュ化されたpassword　*/
	/* 戻り値：なし											 */
	/* 備考：なし											 */
	/*======================================================*/
    function fncSaveUser($pdo, $firstName, $lastName, $email, $hashedPassword) {
        $stmt = $pdo -> prepare("INSERT INTO test_users (firstName, lastName, email, password)
                                                  VALUES(:firstName, :lastName, :email, :password)");
        $stmt -> bindValue(':firstName', $firstName,      PDO::PARAM_STR);
        $stmt -> bindValue(':lastName',  $lastName,       PDO::PARAM_STR);
        $stmt -> bindValue(':email',     $email,          PDO::PARAM_STR);
        $stmt -> bindValue(':password',  $hashedPassword, PDO::PARAM_STR);
        $stmt -> execute();
    }
?>