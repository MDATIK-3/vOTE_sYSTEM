CREATE TABLE address (
    AddressId INT AUTO_INCREMENT PRIMARY KEY,
    Street VARCHAR(255),
    City VARCHAR(255),
    State VARCHAR(255),
    ZipCode VARCHAR(10)
);

CREATE TABLE birthcertificate (
    BirthIdNo INT AUTO_INCREMENT PRIMARY KEY,
    Name VARCHAR(255),
    DateOfBirth DATE,
    Gender CHAR(1),
    AddressId INT,
    FOREIGN KEY (AddressId) REFERENCES address(AddressId)
);

CREATE TABLE nationalid (
    NID INT AUTO_INCREMENT PRIMARY KEY,
    IssueDate DATE,
    BirthIdNo INT,
    Age INT,
    BloodGroup VARCHAR(3),
    FOREIGN KEY (BirthIdNo) REFERENCES birthcertificate(BirthIdNo)
);


CREATE TABLE voterlist (
    VoterNo INT AUTO_INCREMENT PRIMARY KEY,
    NID INT,
    VoterRegion INT,
    FOREIGN KEY (NID) REFERENCES nationalid(NID),
    FOREIGN KEY (VoterRegion) REFERENCES voterdistrict(Id)
);
CREATE TABLE voterdivision (
    Id INT AUTO_INCREMENT PRIMARY KEY,
    Name VARCHAR(255) UNIQUE
);

CREATE TABLE voterdistrict (
    Id INT AUTO_INCREMENT PRIMARY KEY,
    Name VARCHAR(255) UNIQUE,
    DivisionId INT,
    FOREIGN KEY (DivisionId) REFERENCES voterdivision(Id)
);


-- Insert divisions for the dummy data
INSERT INTO voterdivision (Id, Name) VALUES (1, "Division 1"), (2, "Division 2"), (3, "Division 3");

-- Dummy data for voterdistricts
INSERT INTO voterdistrict (Name, DivisionId) VALUES ('District A', 1), ('District B', 2), ('District C', 3);