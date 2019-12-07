DROP DATABASE IF EXISTS FinalProject;

CREATE DATABASE FinalProject;

USE FinalProject;

DROP USER finalproject@localhost;

FLUSH PRIVILEGES;

CREATE USER 'finalproject'@'localhost' IDENTIFIED BY 'jung2016';

GRANT ALL PRIVILEGES ON * TO 'finalproject'@'localhost';

CREATE TABLE tblplDepartment
(
deDepartmentID int NOT NULL AUTO_INCREMENT,
deDepartmentName varchar(255),
PRIMARY KEY(deDepartmentID)
);

CREATE TABLE tblplProject
(
plProjectID int NOT NULL AUTO_INCREMENT,
plProjectName varchar(255),
PRIMARY KEY(plProjectID)
);

CREATE TABLE tblEmployee
(
emEmployeeID int NOT NULL AUTO_INCREMENT,
emFullName varchar(255),
emPhoto mediumblob,
emJoinDate varchar(255),
emDepartmentID int,
emProjectID int,
emSalary varchar(255),
PRIMARY KEY(emEmployeeID),
CONSTRAINT tblplDepartment_AS_dep FOREIGN KEY(emDepartmentID) REFERENCES tblplDepartment(deDepartmentID),
CONSTRAINT tblplProject_AS_pro FOREIGN KEY(emProjectID) REFERENCES tblplProject(plProjectID)
);

CREATE TABLE tblUser
(
userID int NOT NULL AUTO_INCREMENT,
Username varchar(255),
Password varchar(255),
userRole char(20),
PRIMARY KEY(userID)
);


insert into tblplDepartment values(NULL, 'Tech Support');
insert into tblplDepartment values(NULL, 'Software Developer');
insert into tblplDepartment values(NULL, 'Sales');
insert into tblplDepartment values(NULL, 'Quality Assurance');
insert into tblplDepartment values(NULL, 'Web Developer');

insert into tblplProject values(NULL, 'Network Construction');
insert into tblplProject values(NULL, 'Whole Sale Comm.');
insert into tblplProject values(NULL, 'Java Swing');
insert into tblplProject values(NULL, 'Backup Research');
insert into tblplProject values(NULL, 'Website Design');
insert into tblplProject values(NULL, 'PHP Coding');

insert into tblEmployee values(NULL, 'Mike Burcume', 'blob', '1/1/2011', '1', '1', '5000000');
insert into tblEmployee values(NULL, 'Kyle Cooper', 'blob', '1/2/15', '2', '3', '5000000');
insert into tblEmployee values(NULL, 'Johnathan Lamberson', 'blob', '1/3/14', '3', '2', '5000000');

insert into tblUser values('NULL', 'admin','5c8e2ff86628ba9ba4402c1e3e06572c', 'admin');
insert into tblUser values('NULL', 'employee','5c8e2ff86628ba9ba4402c1e3e06572c', 'employee');
insert into tblUser values('NULL', 'other','5c8e2ff86628ba9ba4402c1e3e06572c', 'other');
