CREATE DATABASE miris_orijenta;
use miris_orijenta;

CREATE TABLE User (
  UID int NOT NULL AUTO_INCREMENT,
  Username varchar(32) UNIQUE NOT NULL,
  Password varchar(100) NOT NULL,
  PRIMARY KEY (UID)
);

CREATE TABLE Category (
  CID int NOT NULL AUTO_INCREMENT,
  Name varchar(100) NOT NULL,
  PRIMARY KEY (CID)
);
