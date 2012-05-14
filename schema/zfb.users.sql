DROP TABLE IF EXISTS Users; 
CREATE TABLE Users (
    ID integer PRIMARY KEY AUTO_INCREMENT,   -- unique user ID
    UserName varchar (128) NOT NULL,         -- user login name
    Password varchar (256) NOT NULL,         -- user password
    FullName varchar (512) DEFAULT NULL,     -- user full name (optional)
    RegDate integer NOT NULL                 -- user registration timestamp
);
