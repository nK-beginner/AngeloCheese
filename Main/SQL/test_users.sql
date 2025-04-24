use angelo_cheese

select * from test_users

select * from deletedUsers


create table test_users (
    id int primary key AUTO_INCREMENT,
    firstName varchar(255) not NULL,
    lastName varchar(255) not NULL,
    email varchar(255) not null unique,
    password varchar(255) not null,
    created_at timestamp default CURRENT_TIMESTAMP,
    updated_at timestamp default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP null,
    reset_password_token varchar(255) null unique,
    reset_password_expires_at timestamp null,
    remember_token VARCHAR(255)
);


