/**/
use angelo_cheese_management

select * from products

select * from product_images



-- select * FROM product_images WHERE product_id = 1 AND is_main IS NULL;
-- select * FROM product_images WHERE product_id = 4 AND is_main IS NULL;





SELECT image_path FROM product_images WHERE product_id = 4 AND is_main is NULL





CREATE TABLE product_images (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    product_id INT(11) NOT NULL,
    display_order INT(11) NULL,
    image_path VARCHAR(255) NOT NULL,
    is_main TINYINT(4) NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_product_image FOREIGN KEY (product_id)
        REFERENCES products(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);




-- ALTER TABLE product_images ADD COLUMN display_order INT(11) NULL AFTER product_id;