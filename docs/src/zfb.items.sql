DROP TABLE IF EXISTS Items;
CREATE TABLE Items (
    ID integer PRIMARY KEY AUTO_INCREMENT,  -- unique item ID
    ParentID integer DEFAULT NULL,          -- parent node ID (if there is tree of items)
    ChildOffset integer DEFAULT NULL,       -- position among sibling nodes (0 = first) (if there is tree of items)
    CategoryID integer REFERENCES Categories (ID) -- item category foreign key
        ON UPDATE CASCADE
        ON DELETE SET NULL,
        
    -- ... other item data
);
