<?php
    function fncGetData($pdo, int $int, int $fetchType) {
        switch($int) {
            case 1:
                /********** allItems **********/
                $stmt = $pdo -> prepare("SELECT *
                    FROM product_images AS pi
                    JOIN products AS p ON pi.product_id = p.id
                    WHERE pi.is_main = 1
                    ORDER BY p.id
                ");
                break;

            case 2:
                /********** itemEdit, itemDelete **********/
                $stmt = $pdo -> prepare("SELECT * FROM product_images AS pi JOIN products AS p ON pi.product_id = p.id WHERE pi.is_main = 1 ORDER BY p.id");
                break;

            case 3:
                $stmt = $pdo -> prepare("SELECT * FROM test_users");
                break;
    
            case 4:
            
    
                break;
        }
        $stmt -> execute();

        return ($fetchType === 0) ? $stmt -> fetch(PDO::FETCH_ASSOC) : $stmt -> fetchAll(PDO::FETCH_ASSOC);
    }

?>