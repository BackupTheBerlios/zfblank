DROP TABLE IF EXISTS SiteMenu;
CREATE TABLE SiteMenu (
    ID integer PRIMARY KEY AUTO_INCREMENT,  -- menu item ID
    MenuDepth integer NOT NULL DEFAULT 0,   -- depth inside menu tree
    ParentID integer DEFAULT NULL,  -- ID of parent menu or NULL for toplevel
    Position integer DEFAULT 1,     -- item position among siblings
    Name varchar (256) NOT NULL,    -- name (or title) displayed
    Link varchar (1024),            -- menu item hyperlink 
    Active boolean DEFAULT TRUE     -- whether is active (not implemented)
);
