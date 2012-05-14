DROP TABLE IF EXISTS FAQ;
CREATE TABLE FAQ (
    ID integer PRIMARY KEY AUTO_INCREMENT,      -- unique ID
    CategoryID integer REFERENCES QuestionCategories (ID)
        ON DELETE CASCADE
        ON UPDATE CASCADE,                      -- foreign key: category ID
    Question varchar (4096) NOT NULL,           -- question text
    Answer text NOT NULL                        -- answer text
);

DROP TABLE IF EXISTS FAQCategories;
CREATE TABLE FAQCategories (
    ID integer PRIMARY KEY AUTO_INCREMENT,      -- unique category id
    ParentID integer DEFAULT NULL,              -- parent node ID (if categories are organized in tree)
    ChildOffset integer DEFAULT NULL,           -- position among siblings (0 = first) (if categories are organized in tree)
    Name varchar (1024) NOT NULL                -- category name
);
