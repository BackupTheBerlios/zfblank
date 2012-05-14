DROP TABLE IF EXISTS Feed;
CREATE TABLE Feed (
    ID integer PRIMARY KEY AUTO_INCREMENT,      -- unique item ID
    Title varchar (1024),                       -- item title
    Author varchar (256),                       -- author name
    -- variant for Author when using related users table:
    --      Author integer REFERENCES Users (ID) ON UPDATE CASCADE
    Abstract varchar (2048),                    -- abstract
    Body text NOT NULL,                         -- item content
    CategoryID integer DEFAULT NULL REFERENCES FeedCategories (ID)
        ON UPDATE CASCADE
        ON DELETE SET NULL,                     -- item category foreign key (if using categories)
    PubTimeStamp integer NOT NULL               -- publication timestamp (unix timestamp)
); 

-- categories table

DROP TABLE IF EXISTS FeedCategories;
CREATE TABLE FeedCategories (
    ID integer PRIMARY KEY AUTO_INCREMENT,  -- unique category ID
    ParentID integer DEFAULT NULL,          -- ID of parent category (for categories tree)
    ChildOffset integer DEFAULT NULL,       -- offset among sibling categories (for categories tree -- may be null if categories are not hierarchical)
    Name varchar (1024) NOT NULL            -- category name
);

-- tags table

DROP TABLE IF EXISTS FeedTags;
CREATE TABLE FeedTags (
    ID integer PRIMARY KEY AUTO_INCREMENT,  -- tag ID
    Name varchar (512) NOT NULL,           -- tag name (or title)
    Weight integer DEFAULT 0               -- tag weight
);

-- tags many-to-many association table

DROP TABLE IF EXISTS FeedTagsAssoc;
CREATE TABLE FeedTagsAssoc (
    Item integer NOT NULL REFERENCES Feeds (ID) 
        ON UPDATE CASCADE
        ON DELETE CASCADE,                      -- item foreign key
    Tag integer NOT NULL REFERENCES FeedTags (ID)
        ON UPDATE CASCADE
        ON DELETE CASCADE                       -- tag foreign key
);
