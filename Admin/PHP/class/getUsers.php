<?php

    class Users {
        private $pdo;

        public function __construct($pdo) {
            $this -> pdo = $pdo;
        }

        /*======================================================*/
        /* 用途：ユーザー情報をDBから取得            		       */
        /* 引数：なし                                            */
        /* 戻り値：更新する商品情報								  */
        /* 備考：なし											 */
        /*======================================================*/
        public function getUsers() {
            $stmt = $this -> pdo -> prepare(
            "SELECT
                id,
                firstName,
                lastName,
                email,
                created_at,
                updated_at,
                deleted_at
            FROM
                test_users
            ");
            $stmt -> execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
    }

?>