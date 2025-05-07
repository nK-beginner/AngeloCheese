<?php
    class AdminRepository {
        private $pdo;

        public function __construct($pdo) {
            $this -> pdo = $pdo;
        }

        /*======================================================*/
        /* 用途：ユーザー情報をDBから取得                		   */
        /* 引数：$pdo：DB接続, $email：$_POSTされたemail          */
        /* 戻り値：SQL実行結果									  */
        /* 備考：なし											 */
        /*======================================================*/
        public function fncGetUserByEmail($email) {
            $stmt = $this -> pdo -> prepare("SELECT * FROM admin WHERE email = :email LIMIT 1");
            $stmt -> bindValue(":email", $email, PDO::PARAM_STR);
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
        public function fncSaveAdmin($firstName, $lastName, $email, $hashedPassword) {
            $stmt = $this -> pdo -> prepare("INSERT INTO admin (firstName, lastName, email, password)
                                                        VALUES (:firstName, :lastName, :email, :password)");
            $stmt -> bindValue(":firstName", $firstName     , PDO::PARAM_STR);
            $stmt -> bindValue(":lastName",  $lastName      , PDO::PARAM_STR);
            $stmt -> bindValue(":email",     $email         , PDO::PARAM_STR);
            $stmt -> bindValue(":password",  $hashedPassword, PDO::PARAM_STR);
            $stmt -> execute();
        }
    }


?>