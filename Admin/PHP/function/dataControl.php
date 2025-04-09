<?php
    function fncGetData($pdo, int $int, int $fetchType) {
        switch($int) {
            case 1:
                /********** allItems **********/
                $stmt = $pdo -> prepare("SELECT p.id, pi.image_path, p.name, p.tax_included_price, p.category_id, p.category_name, p.hidden_at
                    FROM product_images AS pi
                    JOIN products AS p ON pi.product_id = p.id
                    WHERE pi.is_main = 1
                    ORDER BY p.id
                ");
                break;

            case 2:
                /********** itemEdit, itemDelete **********/
                $stmt = $pdo -> prepare("SELECT * FROM products AS p JOIN product_images AS pi ON p.id = pi.product_id");
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