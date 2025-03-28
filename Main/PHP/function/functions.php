<?php
    // ログイン状態チェック
    function fncCheckStatus() {
        if (isset($_SESSION['user_id']) || isset($_COOKIE['remember_token'])) {
            header('Location: ../View/onlineShop.php');
            exit;
        }
    }

    // 登録・ログイン試行回数監視
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

    // ユーザー情報をDBから取得（ログイン用）
    function fncGetUserByEmail($pdo, $email) {
        $stmt = $pdo -> prepare("SELECT id, firstName, lastName, email, password, deleted_at FROM test_users WHERE email = :email LIMIT 1");
        $stmt -> bindValue(':email', $email, PDO::PARAM_STR);
        $stmt -> execute();
        return $stmt -> fetch(PDO::FETCH_ASSOC);
    }

    // ユーザー登録
    function fncSaveUser($pdo, $firstName, $lastName, $email, $hashedPassword) {
        $stmt = $pdo -> prepare("INSERT INTO test_users (firstName, lastName, email, password)
                                                  VALUES(:firstName, :lastName, :email, :password)");
        $stmt -> bindValue(':firstName', $firstName,      PDO::PARAM_STR);
        $stmt -> bindValue(':lastName',  $lastName,       PDO::PARAM_STR);
        $stmt -> bindValue(':email',     $email,          PDO::PARAM_STR);
        $stmt -> bindValue(':password',  $hashedPassword, PDO::PARAM_STR);
        $stmt -> execute();
    }

    // ユーザー情報をセッションに保存
    function fncSaveToSession($user, $userId = null) {
        session_regenerate_id(true);

        $_SESSION['user_id']   = $userId ?? $user['id'];
        $_SESSION['firstName'] = $user['firstName'];
        $_SESSION['lastName']  = $user['lastName'];
        $_SESSION['email']     = $user['email'];
    }
?>