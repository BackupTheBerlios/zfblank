-- admin zone menu in the sample application
-- this is clone of SiteMenu table (zfb.sitemenu.sql)

DROP TABLE IF EXISTS AdminMenu;
CREATE TABLE AdminMenu (
    ID integer PRIMARY KEY AUTO_INCREMENT,
    MenuDepth integer NOT NULL DEFAULT 0,
    ParentID integer DEFAULT NULL,
    Position integer DEFAULT 1,
    Name varchar (256) NOT NULL,
    Link varchar (1024),
    Active boolean DEFAULT TRUE
);
