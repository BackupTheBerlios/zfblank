DROP TABLE IF EXISTS PageContent;
CREATE TABLE PageContent (
    Name varchar (32) PRIMARY KEY,               -- unique name (identifier)
    TextStorage enum ('db','file') DEFAULT 'db', -- storage of text (body): database or file
    Title varchar (512) NOT NULL,                -- page/block title
    Description varchar (2048) DEFAULT NULL,     -- page/block description
    Body text,                                   -- page/block content (unused when TextStorage = 'file')
    Tags varchar (512) DEFAULT NULL              -- page/block tags
);
