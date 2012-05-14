DROP TABLE IF EXISTS News;
CREATE TABLE News (
    ID integer PRIMARY KEY AUTO_INCREMENT,      -- unique item ID
    Title varchar (1024),                       -- item title
    Abstract varchar (2048),                    -- abstract
    Body text NOT NULL,                         -- item content
    CategoryID integer DEFAULT NULL REFERENCES NewsCategories (ID)
        ON UPDATE CASCADE
        ON DELETE SET NULL,                     -- item category foreign key 
    PubTimeStamp integer NOT NULL               -- publication timestamp (unix timestamp)
); 

-- categories table

DROP TABLE IF EXISTS NewsCategories;
CREATE TABLE NewsCategories (
    ID integer PRIMARY KEY AUTO_INCREMENT,  -- unique category ID
    ParentID integer DEFAULT NULL,          -- ID of parent category (for categories tree)
    ChildOffset integer DEFAULT NULL,       -- offset among sibling categories (for categories tree -- may be null if categories are not hierarchical)
    Name varchar (1024) NOT NULL            -- category name
);

-- tags table

DROP TABLE IF EXISTS NewsTags;
CREATE TABLE NewsTags (
    ID integer PRIMARY KEY AUTO_INCREMENT,  -- tag ID
    Name varchar (512) NOT NULL,           -- tag name (or title)
    Weight integer DEFAULT 0               -- tag weight
);

-- tags many-to-many association table

DROP TABLE IF EXISTS NewsTagsAssoc;
CREATE TABLE NewsTagsAssoc (
    Item integer NOT NULL REFERENCES News (ID) 
        ON UPDATE CASCADE
        ON DELETE CASCADE,                      -- item foreign key
    Tag integer NOT NULL REFERENCES NewsTags (ID)
        ON UPDATE CASCADE
        ON DELETE CASCADE                       -- tag foreign key
);
