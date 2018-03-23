CREATE DATABASE miris_orijenta;
use miris_orijenta;

CREATE TABLE User (
  UID int NOT NULL,
  Username varchar(32) UNIQUE NOT NULL,
  Password varchar(100) NOT NULL,
  PRIMARY KEY (UID)
);

CREATE TABLE Category (
  CID int NOT NULL,
  Name varchar(100) UNIQUE NOT NULL,
  PRIMARY KEY (CID)
);

CREATE TABLE Product (
  PID int NOT NULL,
  Name varchar(100) NOT NULL,
  Choosen TINYINT NOT NULL DEFAULT 0,
  CID int NOT NULL,
  PRIMARY KEY (PID),
  FOREIGN KEY (CID) REFERENCES Category(CID)
);

CREATE TABLE ChoosenProduct (
  PID int NOT NULL,
  PRIMARY KEY (PID),
  FOREIGN KEY (PID) REFERENCES Product(PID)
);
