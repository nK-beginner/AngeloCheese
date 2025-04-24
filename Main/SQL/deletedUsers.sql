use angelo_cheese

select * from deletedUsers

select * from test_users

CREATE TABLE deletedUsers (
    id INT PRIMARY KEY AUTO_INCREMENT,
    userId INT NOT NULL,  -- 退会したユーザーの元のID
    firstName VARCHAR(255) NOT NULL,
    lastName VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    reason INT NOT NULL,
    reasonDetail VARCHAR(255) NULL,
    deleted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (userId) REFERENCES test_users(id) ON DELETE CASCADE
);


CREATE TRIGGER userDeleted
AFTER INSERT ON deletedUsers
FOR EACH ROW
UPDATE test_users 
SET deleted_at = NOW() 
WHERE id = NEW.userId;

