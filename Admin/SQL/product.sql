/**/
-- use angelo_cheese_management

select * from products


CREATE TABLE products (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    description TEXT NOT NULL,
    category_id TINYINT(4) NULL,
    category_name VARCHAR(255) NULL,
    keyword VARCHAR(50) NOT NULL,
    size1 INT(11) NOT NULL,
    size2 INT(11) NOT NULL,
    tax_rate DECIMAL(10,2) NOT NULL,
    price INT(11) NOT NULL,
    tax_included_price INT(11) NOT NULL,
    cost INT(11) NOT NULL,
    expirationDate_min1 INT(11) NOT NULL,
    expirationDate_max1 INT(11) NOT NULL,
    expirationDate_min2 INT(11) NOT NULL,
    expirationDate_max2 INT(11) NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    hidden_at TIMESTAMP NULL
);
