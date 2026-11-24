-- NEMT Auto
INSERT INTO PARTS
    (Num, Description, Price, Weight, PictureURL)
VALUES
    (00000000500,'Placeholder but will be a Wheel',249.99,22.00,'Placeholder.jpg'),
    (00000000001,'Placeholder but will be a Car Door',999.99,50.00,'Placeholder.jpg'),
    (00000000010,'Placeholder but will be a Window',749.99,37.37,'Placeholder.jpg'),
    (00000001000,'Placeholder but will be a Engine',1.00,1,'Placeholder.jpg'),
    (00000000777,'Placeholder but will be a Spark Plug',100000.01,99.99,'Placeholder.jpg');

INSERT INTO ORDERS
    (TrackingID, Status, Total, Name, CCNumber, City, Street, Contact)
VALUES
    (0001,'Shipped',1,'John Smith','0000000000000000','Normalville','Average St.','test1.user@example.com'),
    (5872,'Processed',749.99,'Jon Lehuta','0123456789012345','Grayslake','Microsoft Blvd.','test2.user@example.com'),
    (9999,'Processed',201749.99,'Jean Smiths','9999999999999999','Richston','Money Rd.','test3.user@example.com');

INSERT INTO EMPLOYEE
    (EmpID, IsAdmin, FirstName, LastName, Username, Password, Email)
VALUES
    (77777777777,FALSE,'Nico','Flowers','Prospill','$2y$12$nXTQ0.xaQTP74ovsuXsmMOmFuiPFDEYtWTg9gw2JV1WVlFojbcwl2','unomail@gmail.com'),
    (93471390572,TRUE,'Timmy','Batson','Admin','$2y$12$ym1U0P3K/DursMNNAMaTCu0cgm/GzsKLXjuGmdxpk.H3Thjyu2Yta','dosmail@gmail.com'),
    (00000000000,FALSE,'Mewgumi','Gohome','Gamerapple','66666666','tresmail@gmail.com'),
    (99999999999,FALSE,'Ewin','Richstrom','Hammersometimes','55555555','fourmail@gmail.com');

INSERT INTO INVENTORY
    (Num, InvQty)
VALUES
    (00000000500,16),
    (00000000001,4),
    (00000000010,8),
    (00000001000,100),
    (00000000777,35);

INSERT INTO REQUESTS
    (TrackingID, Num, OrderQty)
VALUES
    (0001,00000001000,1),
    (5872,00000000010,1),
    (9999,00000000777,2),
    (9999,00000000010,1),
    (9999,00000000001,1);

INSERT INTO BRACKETS
    (MinWeight, MaxWeight, Charge)
VALUES
    (0,4.99,1.00),
    (5,9.99,5.00),
    (10,49.99,30.00),
    (50,100.00,500.00);
   
    
