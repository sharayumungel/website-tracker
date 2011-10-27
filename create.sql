CREATE TABLE Customers (
    CustId INTEGER(10) NOT NULL AUTO_INCREMENT,
    CustFn VARCHAR(50) NOT NULL,
    CustLn VARCHAR(50) NOT NULL,
    CustEmail VARCHAR(100) NOT NULL,
    CustPhone VARCHAR(15),
    CONSTRAINT PK_Customers PRIMARY KEY (CustId)
);


CREATE TABLE Sites (
    SiteId INTEGER(10) NOT NULL AUTO_INCREMENT,
    CustId INTEGER(10) NOT NULL,
    SiteUrl VARCHAR(255) NOT NULL,
    SiteDate DATE NOT NULL,
    SiteDesc TEXT NOT NULL,
    CONSTRAINT PK_Sites PRIMARY KEY (SiteId)
);


CREATE TABLE Updates (
    UpdateId INTEGER(10) NOT NULL AUTO_INCREMENT,
    Updated DATE NOT NULL,
    UpdateNotes TEXT,
    CONSTRAINT PK_Updates PRIMARY KEY (UpdateId)
);


CREATE TABLE Sites_Updates (
    SiteId INTEGER(10) NOT NULL,
    UpdateId INTEGER(10) NOT NULL,
    CONSTRAINT PK_Sites_Updates PRIMARY KEY (SiteId, UpdateId)
);


CREATE TABLE Staff (
    StaffId INTEGER(10) NOT NULL AUTO_INCREMENT,
    CONSTRAINT PK_Staff PRIMARY KEY (StaffId)
);


CREATE TABLE Feedback (
    FeedbackId INTEGER(10) NOT NULL AUTO_INCREMENT,
    CustId INTEGER(10) NOT NULL,
    StaffId INTEGER(10) NOT NULL,
    FeedbackTs TIMESTAMP NOT NULL,
    FeedbackDesc TEXT,
    CONSTRAINT PK_Feedback PRIMARY KEY (FeedbackId)
);


ALTER TABLE Sites ADD CONSTRAINT Customers_Sites 
    FOREIGN KEY (CustId) REFERENCES Customers (CustId);

ALTER TABLE Sites_Updates ADD CONSTRAINT Sites_Sites_Updates 
    FOREIGN KEY (SiteId) REFERENCES Sites (SiteId);

ALTER TABLE Sites_Updates ADD CONSTRAINT Updates_Sites_Updates 
    FOREIGN KEY (UpdateId) REFERENCES Updates (UpdateId);

ALTER TABLE Feedback ADD CONSTRAINT Staff_Feedback 
    FOREIGN KEY (StaffId) REFERENCES Staff (StaffId);

ALTER TABLE Feedback ADD CONSTRAINT Customers_Feedback 
    FOREIGN KEY (CustId) REFERENCES Customers (CustId);
