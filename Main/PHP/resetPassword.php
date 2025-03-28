<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    require_once __DIR__.'/../backend/connection.php';
    require_once __DIR__.'/../backend/csrf_token.php';

    $errors = $_SESSION['errors'] ?? [];
    unset($_SESSION['errors']);

    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        require_once __DIR__.'/../backend/check.php';

        $password = $_POST['password'] ?? '';
        $re_password = $_POST['re-password'] ?? '';
        $errors = [];

        // パスワード入力確認
        if($password !== $re_password) {
            $errors[] = 'パスワードが一致しません。';
        } 
        
        // パスワードフォーマットチェック
        if(!preg_match('/^(?=.*[A-Za-z])(?=.*\d)(?=.*[\W_])[A-Za-z\d\W_]{8,}$/', $password)) {
            $errors[] = 'パスワードは英数字記号を含む8文字以上で入力してください。';
        }

        // エラーがなければ更新処理へ
        if(empty($errors)) {
           $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // ユーザーIDはセッションから取得(既にセッションにユーザーIDがあること前提)
           $user_id = $_SESSION['user_id'] ?? null; 

            if($user_id) {
                $stmt = $pdo -> prepare('update test_users set password = :password where id = :id');
                $stmt -> bindValue(':password', $hashed_password, PDO::PARAM_STR);
                $stmt -> bindValue(':id', $user_id, PDO::PARAM_INT);
                $stmt -> execute();
                
                header('Location: ../View/resetPasswordDone.php');
                exit;

            } else {
                $errors[] = 'ユーザーIDが見つかりません。';
            }
        }

        // エラーあればセッションに残してリダイレクト
        if(!empty($errors)) {
            $_SESSION['errors'] = $errors; // セッションにエラー情報を格納
            header('Location: ../View/resetPassword.php'); // エラーがあればリダイレクト
            exit;
        }
    }
?>