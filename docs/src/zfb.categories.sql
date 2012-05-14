DROP TABLE IF EXISTS Categories;
CREATE TABLE Categories (
    ID integer PRIMARY KEY AUTO_INCREMENT,  -- unique category ID
    ParentID integer DEFAULT NULL,          -- ID of parent category (for categories tree)
    ChildOffset integer DEFAULT NULL,       -- offset among sibling categories (for categories tree -- may be null if categories are not hierarchical)
    Name varchar (1024) NOT NULL            -- category name
);
