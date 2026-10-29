-- STUFFED ANIMAL STORE
INSERT INTO STUFFEDANIMALSTORE
	(StuffieID, ProductName, Price, InvQty, ImagePath, StuffieDescription) 
VALUES
	('S001','erawr',500.00,3, 'https://students.cs.niu.edu/~z1977897/erawr.jpg', 'green dinosaur'),
	('S002','urawr',67.00,6, 'https://students.cs.niu.edu/~z1977897/urawr.jpg', 'light pink dinosaur'),
	('S003','mirawr',3.25,140, 'https://students.cs.niu.edu/~z1977897/mirawr.jpg', 'pink dinosaur'),
	('S004','tirawr',487.75,5, 'https://students.cs.niu.edu/~z1977897/tirawr.jpg', 'yellow dinosaur'),
	('S005','quagsire',620.00,2, 'https://students.cs.niu.edu/~z1977897/quagsire.jpg', 'quagsire pokemon'),
	('S006','clodsire',980.00,1, 'https://students.cs.niu.edu/~z1977897/clodsire.jpg', 'clodsire pokemon'),
	('S007','tamago',495.00,6, 'https://students.cs.niu.edu/~z1977897/tamago.jpg', 'lil egg'),
	('S008','shibata',420.00,8, 'https://students.cs.niu.edu/~z1977897/shibata.jpg', 'shiba inu'),
	('S009','shibata BIG',515.00,1, 'https://students.cs.niu.edu/~z1977897/shibata%20big.jpg', 'BIG shiba inu'),
	('S010','burnt chibatta',499.00,2, 'https://students.cs.niu.edu/~z1977897/burnt%20shibata.jpg', 'black shiba inu'),
	('S011','shibata roll',455.00,7, 'https://students.cs.niu.edu/~z1977897/shibata%20roll.jpg', 'round shiba inu'),
	('S012','sharkie',510.00,2, 'https://students.cs.niu.edu/~z1977897/sharkie.jpg', 'shark with a pineapple surfboard'),
	('S013','hedhog',520.00,4, 'https://students.cs.niu.edu/~z1977897/hedgehog.jpg', 'squeaks, honks, AND crinkles?! perfect for dogs or people with whimsy'),
	('S014','coronavirus',2019.00,1, 'https://students.cs.niu.edu/~z1977897/corona.jpg', 'AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAHHHHHHHHHHHHHH'),
	('S015','skunkie',510.50,3, 'https://students.cs.niu.edu/~z1977897/skunkie.jpg', 'BIG FAT GUY he wants love, i know hes expensive but pls buy him'),
	('S016','toast',11.98,67, 'https://students.cs.niu.edu/~z1977897/toast.jpg', 'This little cutie is double the price of a "2-Egg Breakfast Slam" at Dennys! You only get one toast, one egg, and no hashbrowns- but it does come with a smile! What a steal! Pay no mind to the cat fur, its a little garnish from our sous chef, Khors.'),
	('S017','nothing',13.65,2, 'https://students.cs.niu.edu/~z1977897/nothing.jpg', 'Exactly what it says on the tin: absolutely nothing. You still have to pay for shipping though. Just dont ask why you dont have to pay for shipping for any of the other products.'),
	('S018','mama cow and baby',2032.54,25, 'https://students.cs.niu.edu/~z1977897/mamacow.jpg', 'This mama Ayrshire cow and her baby is a two for one deal! Two peas in a pod, if you will! Take away her baby and she will grind you up, make you into food for the pigs, and youll forever be an unsolved mystery- how lovely!'),
	('S019','cow',1432.32,67, 'https://students.cs.niu.edu/~z1977897/cow.jpg', 'A Holstein-Friesian cow! Free range, grass fed- the whole lot! And thats not all! He loves smooth jazz, lazing about, and is very aerodynamic! Just dont tip him over, he will cry.'),
	('S020','khors!?',9999.99,1, 'https://students.cs.niu.edu/~z1977897/horse.jpg', 'A very gaseous and gluttonus creature that eats like a horse- wait, whats he doing here!?');

-- SHOPPING CART
INSERT INTO SHOPPINGCART
	(TrackingID, StuffieID, CartQty)
VALUES
	('T103','S018',2),
	('T100','S001',1),
	('T104','S010',2),
	('T101','S014',1),
	('T105','S019',1),
	('T100','S003',4),
	('T102','S006',1),
	('T104','S020',1),
	('T101','S002',2),
	('T103','S011',1),
	('T104','S012',1),
	('T102','S007',2),
	('T105','S005',1),
	('T102','S005',1),
	('T103','S009',1);

-- ORDERS
INSERT INTO ORDERS
	(TrackingID, OrderStatus, Total, CCInfo, ShippingAddr, BillingAddr)
VALUES
	('T103','Processing',986.00,'4839-2051-7746-9182','742 First Street, DeKalb, IL','742 First Street, DeKalb, IL'),
	('T100','Processing',513.00,'1928-5501-3349-6671','1289 Sycamore Road, DeKalb, IL','1289 Sycamore Road, DeKalb, IL'),
	('T105','Processing',895.00,'7601-4429-8830-1156','55 Annie Glidden Road, DeKalb, IL','55 Annie Glidden Road, DeKalb, IL'),
	('T102','Shipped',2590.00,'3301-9984-1207-5563','900 Lincoln Highway, DeKalb, IL','900 Lincoln Highway, DeKalb, IL'),
	('T104','Delivered',2258.99,'5810-7743-9921-4408','1200 First Street, DeKalb, IL','1200 First Street, DeKalb, IL'),
	('T101','Processing',2153.00,'9044-2187-6630-5519','311 Sycamore Road, DeKalb, IL','311 Sycamore Road, DeKalb, IL');

-- REQUESTS
INSERT INTO REQUESTS
	(TrackingID, StuffieID, OrderQty)
VALUES
	('T102','S006',1),
	('T100','S001',1),
	('T104','S010',1),
	('T101','S002',2),
	('T103','S018',2),
	('T105','S019',1),
	('T100','S003',4),
	('T104','S020',1),
	('T102','S005',1),
	('T101','S014',1),
	('T103','S011',1),
	('T104','S012',1),
	('T102','S007',2),
	('T105','S005',1),
	('T103','S009',1);
