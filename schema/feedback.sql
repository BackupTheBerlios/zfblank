DROP TABLE IF EXISTS Feedback;
CREATE TABLE Feedback (
    ID integer PRIMARY KEY AUTO_INCREMENT,  -- unique message ID
    Author varchar (100) NOT NULL,          -- the user wrote the message
    -- variant for Author when using related users table:
    --      Author integer REFERENCES Users (ID) ON UPDATE CASCADE
    Contact varchar (1024),                 -- user contact info
    Message varchar (2048) NOT NULL,        -- the feedback message
    AnswerRequired boolean NOT NULL,      
    Reply text DEFAULT NULL,                -- admin reply to the message
    MsgTimeStamp integer NOT NULL           -- timestamp of the feedback message
);
