<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    require_once __DIR__.'/../backend/connection.php';
    
    if(!isset($_SESSION['user_id'])) {
        header('Location: onlineShop.php');
        exit;
    }

    $errors = $_SESSION['errors'] ?? [];

    unset($_SESSION['errors']);

    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        require_once __DIR__.'/../backend/check.php';

        $password   = $_POST['password'] ?? '';
        $rePassword = $_POST['rePassword'] ?? '';

        if($password !== $rePassword) {
            $errors[] = '確認用とパスワードが一致しません。';
        }

        $stmt = $pdo -> prepare("SELECT * FROM test_users WHERE email = :email LIMIT 1");
        $stmt -> bindValue(":email", $_SESSION['email'], PDO::PARAM_STR);
        $stmt -> execute();
        $user = $stmt -> fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            $errors[] = 'ユーザーが見つかりません。';
        } 
        
        if (!password_verify($password, $user['password'])) {
            $errors[] = 'パスワードが異なります。'; 
        }

        if(!empty($errors)) {
            $_SESSION['errors'] = $errors;
            header('Location: confirmUnsubscribe.php');
            exit;
        }

        $pdo -> beginTransaction();
        try {
            $stmt = $pdo -> prepare("INSERT INTO deletedUsers (userId,  firstName,  lastName,  email, reason, reasonDetail)
                                                        VALUES(:userId, :firstName, :lastName, :email, :reason, :reasonDetail)");
            $stmt -> bindValue(":userId",    $user['id'],        PDO::PARAM_INT);
            $stmt -> bindValue(":firstName", $user['firstName'], PDO::PARAM_STR);
            $stmt -> bindValue(":lastName",  $user['lastName'],  PDO::PARAM_STR);
            $stmt -> bindValue(":email",     $user['email'],     PDO::PARAM_STR);
            $stmt -> bindValue(":reason",    intval($_SESSION['reason']), PDO::PARAM_INT);
            $stmt -> bindValue(":reasonDetail",    $_SESSION['reasonDetail'], PDO::PARAM_STR);
            $stmt -> execute();

            $pdo -> commit();

            session_destroy();

            if(isset($_COOKIE[session_name()])) {
                setcookie(session_name(), '', time() - 3600, '/');
            }

            setcookie('remember_token', '', time() - 3600, '/', '', false, true);

            header('Location: unsubscribeDone.php');
            exit;

        } catch(PDOException $e) {
            $pdo -> rollBack();
            error_log('データベース接続エラー' . $e -> getMessage());
            
            $_SESSION['errors'][] = 'データベースエラーが発生しました。時間をおいて再試行してください。';

            header('Location: confirmUnsubscribe.php');
            exit;
        }
    }
?>