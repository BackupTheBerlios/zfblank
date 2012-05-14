DROP TABLE IF EXISTS NewsComments;
CREATE TABLE NewsComments (
    ID integer PRIMARY KEY AUTO_INCREMENT,      -- unique comment ID
    ItemID integer REFERENCES News    
        ON UPDATE CASCADE                
        ON DELETE CASCADE,                      -- commented item foreign key 
    Author varchar (256),                       -- author name
    -- variant for Author when using related users table:
    --      Author integer REFERENCES Users (ID) ON UPDATE CASCADE
    Title varchar (1024),                       -- comment title
    Body text NOT NULL,                         -- comment text
    ParentID integer DEFAULT NULL,              -- parent node ID (if comments are organized in tree)
    ChildOffset integer DEFAULT NULL,           -- position among sibling nodes (0 = first) (if comments are organized in tree)
    PubTimeStamp integer NOT NULL               -- publication timestamp (unix timestamp)
); 
