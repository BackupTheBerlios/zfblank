DROP TABLE IF EXISTS Tree;
CREATE TABLE Tree (
    ID integer PRIMARY KEY AUTO_INCREMENT,  -- unique node ID
    ParentID integer DEFAULT NULL,          -- parent node ID
    ChildOffset integer NOT NULL,           -- position among sibling nodes (0 = first)
    Name varchar (1024)                     -- (optional) name (arbitrary string)
    -- ... other node data
);
