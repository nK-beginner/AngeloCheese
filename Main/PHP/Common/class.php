<?php
    class Account {
        private $pdo;
        private $errors = [];
        private $firstName;
        private $lastName;
        private $email;
        private $password;

        public function __construct($pdo) {
            $this -> pdo = $pdo;
        }

        public function setInputData($firstName, $lastName, $email, $password) {
            $this -> firstName = trim($firstName);
            $this -> lastName  = trim($lastName);
            $this -> email     = trim($email);
            $this -> password  = $password;
        }

        public function validate() {
            // メアドチェック
            if (!filter_var($this -> email, FILTER_VALIDATE_EMAIL)) {
                $this -> errors[] = '有効なメールアドレスを入力してください。';
            }

            // パスワードのフォーマット設定
            if (!preg_match('/^(?=.*[A-Za-z])(?=.*\d)(?=.*[\W_])[A-Za-z\d\W_]{8,}$/', $this -> password)) {
                $this -> errors[] = 'パスワードは英数字記号を含む8文字以上で入力してください。';
            }

            // メアド重複チェック
            $stmt = $this -> pdo -> prepare("SELECT id FROM test_users WHERE email = :email LIMIT 1");
            $stmt -> bindValue(':email', $this -> email, PDO::PARAM_STR);
            $stmt -> execute();
            if ($stmt -> fetch()) {
                $this -> errors[] = 'このメールアドレスは既に登録されています。';
            }

            return empty($this -> errors);
        }

        public function register() {
            $hashedPassword = password_hash($this -> password, PASSWORD_DEFAULT);

            try {
                $pdo -> beginTransaction();

                $stmt = $this -> pdo -> prepare("INSERT INTO test_users (firstName, lastName, email, password)
                                                                  VALUES(:firstName, :lastName, :email, :password)");
                $stmt -> bindValue(':firstName', $this -> firstName, PDO::PARAM_STR);
                $stmt -> bindValue(':lastName',  $this -> lastName,  PDO::PARAM_STR);
                $stmt -> bindValue(':email',     $this -> email,     PDO::PARAM_STR);
                $stmt -> bindValue(':password',  $hashedPassword,    PDO::PARAM_STR);
                $stmt -> execute();

                return $this -> pdo -> lastInsertId();

            } catch (PDOException $e) {
                throw new Exception("ユーザー登録エラー: " . $e -> getMessage());
            }
        }

        public function getErrors() {
            return $this -> errors;
        }
    }
?>
