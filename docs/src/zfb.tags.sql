DROP TABLE IF EXISTS Tags;
CREATE TABLE Tags (
    ID integer PRIMARY KEY AUTO_INCREMENT,  -- tag ID
    Name varchar (512) NOT NULL,           -- tag name (or title)
    Weight integer DEFAULT 0               -- tag weight
);

-- many-to-many association table

DROP TABLE IF EXISTS ItemTags;
CREATE TABLE ItemTags (
    Item integer NOT NULL REFERENCES Items (ID) 
        ON UPDATE CASCADE
        ON DELETE CASCADE,                      -- item foreign key
    Tag integer NOT NULL REFERENCES Tags (ID)
        ON UPDATE CASCADE
        ON DELETE CASCADE                       -- tag foreign key
);
    
        
