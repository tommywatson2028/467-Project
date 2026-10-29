<!--
Employee Page
Allows the viewer to alter quantity
Table names: ORDERS, STUFFEDANIMALSTORE, REQUESTS, SHOPPINGCART
Allows the viewer to change status of orders
-->
<?php
            ini_set('display_errors', 1);
            ini_set('display_startup_errors', 1);
            error_reporting(E_ALL);

            $username = "";//Will be changed later
            $passwd = "";//Will be changed later

            try
            {
                $dsn = "mysql:host=courses;dbname={$username}";
                $pdo = new PDO($dsn, $username, $passwd);
            }

            catch(PDOException $e)
            {
                echo "Connection failed " . $e->getmessage();
                exit();
            }

            #Check if there was an answer submitted
            if (isset($_POST['step2']))
            {
                $product = $_POST['product'] ?? null;

                $check = true;

                $qty = filter_input(INPUT_POST, 'qty', FILTER_VALIDATE_INT);

                if($qty === false || $qty === null)
                {
                    echo "<p style='color:red'>Invalid Qty Input</p>";
                    $check = false;
                }
                
                if(!$product)
                {
                    echo "<p style='color:red'>No Product Selected</p>";
                    $check = false;
                }
                
                if($check)
                {
                    $checkStmt = $pdo->prepare("SELECT InvQty FROM STUFFEDANIMALSTORE WHERE StuffieID = ?");
                    $checkStmt->execute([$product]);
                    $answer2 = $checkStmt->fetch(PDO::FETCH_ASSOC);

                    if(!$answer2)
                    {
                        echo "Invalid Request";
                    }
                    else
                    {
                        if($qty <= 0)
                        {
                            echo "<p style='color:red'>Invalid Qty amount</p>";
                        }
                        else if($qty > 9999)
                        {
                            echo "<p style='color:red'>Invalid Qty amount exceeds max InvQty</p>";
                        }
                        else
                        {
                            $updateSql = $pdo->prepare("UPDATE STUFFEDANIMALSTORE SET InvQty = InvQty + ? WHERE StuffieID = ?");
                            $updateSql->execute([$qty, $product]);
                            //echo "<p style='color:green;'>Update complete</p>";
                            header("Location: " . $_SERVER['PHP_SELF'] . "?updated=1");
                            exit();

                            $resultStmt = $pdo->prepare("SELECT ProductName, InvQty FROM STUFFEDANIMALSTORE WHERE StuffieID = ?");
                            $resultStmt->execute([$product]);
                            $updatedQTY = $resultStmt->fetch(PDO::FETCH_ASSOC);

                            $qty = $updatedQTY['InvQty'];
                            $ProductName = $updatedQTY['ProductName'];

                            //echo "<p><b>Product: $ProductName now has QTY of: $qty</b></p>";
                        }
                    }
                }
            }
        ?>

<html>
    <head>
        <title>Employee Page Stuffie Store</title>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200..1000;1,200..1000&display=swap" rel="stylesheet">

        <style>
            h1 {font-family:'Nunito', sans-serif; color:hotpink; padding: 5px 20px;}
            p {font-family:'Nunito', sans-serif; color:lightpink; word-break: break-word; margin: 20px;}

            img 
            {
                display: block;
                margin-left: auto;
                margin-right: auto;
                width: 75%; /* Optional: set a width smaller than the container */
                border-radius: 10px;
            }
            th
            {
                border: 1px solid lightgray;
                word-wrap: break-word;
                overflow-wrap: break-word;
                background-color: white;
                font-family:'Nunito', sans-serif;
                padding: 5px;
            }
            form
            {
                border-radius: 10px;
                border-spacing: 5px;
                padding: 10px;
                margin: 20px;
                background-color: #ffe8f8;
                font-family:'Nunito', sans-serif;
            }
            td
            {
                border: 1px solid lightgray;
                word-wrap: break-word;
                overflow-wrap: break-word;
                background-color: white;
                font-family:'Nunito', sans-serif;
                padding: 2px 5px;
            }
            img:hover 
            {
                transform: scale(1.05);
                transition: 0.3s;
            }
            
            table 
            {
                border-radius: 10px;
                border-spacing: 5px;
                padding: 10px;
                margin: 20px;
                background-color: #ffe8f8;
            }

            .button 
            {
                border: none;
                color: white;
                padding: 16px 32px;
                text-align: center;
                text-decoration: none;
                display: inline-block;
                font-size: 16px;
                margin: 4px 2px;
                transition-duration: 0.3s;
                cursor: pointer;
            }

            .button1 
            {
                background-color: white; 
                color: black; 
                border: 2px solid hotpink;
                border-radius: 10px;
            }

            .button1:hover 
            {
                background-color: pink;
                color: white;
                border-radius: 10px;
            }

            .top-right-btn2 
            {
                position: fixed;
                top: 20px;
                right: 15px;

                background-color: hotpink;
                color: white;

                padding: 10px 16px;
                border-radius: 10px;

                text-decoration: none;
                font-weight: bold;

                z-index: 999; /* stays above everything */
                box-shadow: 0px 4px 10px rgba(0,0,0,0.2);
                transition: 0.3s ease;

                width: 100px;
                max-width: 200px;
                text-align: center;
                font-family:'Nunito', sans-serif; 
            }

            .top-right-btn2:hover 
            {
                background-color: deeppink;
                transform: scale(1.05);
            }

			.bottom-right-btn
			{
				position: fixed;
                bottom: 20px;
                right: 15px;

                background-color: hotpink;
                color: white;

                padding: 10px 16px;
                border-radius: 10px;

                text-decoration: none;
                font-weight: bold;

                z-index: 999; /* stays above everything */
                box-shadow: 0px 4px 10px rgba(0,0,0,0.2);
                transition: 0.3s ease;

                width: 100px;
                max-width: 200px;
                text-align: center;
                font-family:'Nunito', sans-serif;
			}

			.bottom-right-btn:hover
			{
				background-color: deeppink;
                transform: scale(1.05);
			}
        </style>
    </head>

    <body style="background-color:Lavender">
        <h1><b>All Products:</b></h1>

        <?php
            if (isset($_GET['updated'])) {
                echo "<p style='color:green;'><b>Update complete</b></p>";
            }

            #Step 1 Create a list of all the products in a Table format
            $step1 = "SELECT StuffieID, ProductName, Price, InvQty FROM STUFFEDANIMALSTORE;";

            $result1 = $pdo->query($step1);
            $answer1 = $result1->fetchAll(PDO::FETCH_ASSOC);

            echo "<table border='3'>";
            echo "<tr>";

            if (!empty($answer1)) 
            {
                foreach($answer1[0] as $key => $value)
                {
                    echo "<th>" . htmlspecialchars($key) . "</th>";
                }
            }
            echo "</tr>";
            
            #print rows
            foreach($answer1 as $row)
            {
                echo "<tr>";

                foreach($row as $value) 
                {
                    echo "<td>" . htmlspecialchars($value) . "</td>";
                }

                echo "</tr>";
            }

            echo "</table>";

            #Step 2 Allow the user to alter the InvQty of the products
            echo "<h1><b>Alter The QTY of Any Product!</b></h1>";

            echo "<form method='POST'>";
            echo "<label>Choose a Product:</label>";
            echo "<select name='product' id='product'>";

            $ProductList = $pdo->query("SELECT StuffieID, ProductName FROM STUFFEDANIMALSTORE");
            while( $row = $ProductList->fetch())
            {
                echo "<option value='{$row['StuffieID']}'>{$row['ProductName']}</option>";
            }
                
            echo "</select>";

            echo "<br/>";
            echo "How Many of That Specific Item to Restock?<input type='number' name='qty'>";

            echo "<input type='submit' name='step2' value='Add to InvQty'>";
            echo "</form>";

            

            #Step 3 Create a display of all Orders
            echo "<h1><b>All Orders:</b></h1>";
            $step3 = "SELECT TrackingID, OrderStatus, ShippingAddr, BillingAddr FROM ORDERS;";
            $result3 = $pdo->query($step3);
            $answer3 = $result3->fetchAll(PDO::FETCH_ASSOC);

            echo "<table border='3'>";
            echo "<tr>";
            echo "<th>Order #</th>";

            if (!empty($answer3))
            {
                foreach($answer3[0] as $key => $value) 
                {
                    echo "<th>" . htmlspecialchars($key) . "</th>";
                }
				echo "<th>Items</th>";
            }

            echo "</tr>";
            $count2 = 1;
            #print rows
            foreach($answer3 as $row) 
            {
                echo "<tr>";
                echo "<td>" . $count2 . "</td>";
                $count2++;

                foreach($row as $value)
                {
                    echo "<td>" . htmlspecialchars($value) . "</td>";
                }

				$orderInfo = $pdo->prepare("SELECT STUFFEDANIMALSTORE.StuffieID, STUFFEDANIMALSTORE.ProductName, SHOPPINGCART.CartQty FROM STUFFEDANIMALSTORE 
				JOIN SHOPPINGCART ON SHOPPINGCART.StuffieID = STUFFEDANIMALSTORE.StuffieID WHERE SHOPPINGCART.TrackingID = ?");

				$orderInfo->execute([$row['TrackingID']]);
				$items = $orderInfo->fetchAll(PDO::FETCH_ASSOC);

				echo "<td>";

				if(!empty($items))
				{
					foreach($items as $item) 
					{
            					 echo htmlspecialchars($item['ProductName']) . " - " . htmlspecialchars($item['CartQty']) . "<br>";
        			}
				}
				else 
				{
        			echo "No items";
    			}

				echo "</td>";
    			echo "</tr>";
				
            }

            echo "</table>";
        
        #Step 4 Change the Order Status accordingly    
        ?>

        <h1><b>Update order status</b></h1>
            <form method="POST">
                <label><b>Select an order:</b></label>
                    <select name="order">
                        <?php
                            $orderlist = $pdo->query("SELECT TrackingID FROM ORDERS");
                            $count = 0;
                            while ($row = $orderlist->fetch()) 
                            {
                                $count++;
                                echo "<option value='{$row['TrackingID']}'>Order $count</option>";
                                
                            }
                        ?>
                    </select>

                <label><b>Select a status:</b></label>
                <select name="status">
                    <?php
                        $orderstatus = $pdo->query("SELECT DISTINCT OrderStatus FROM ORDERS");
                        while ($row = $orderstatus->fetch()) 
                        {
                            echo "<option value='{$row['OrderStatus']}'>{$row['OrderStatus']}</option>";
                        }
                    ?>
                </select>

                
                <input type="submit" name="submitupdateorder" value="update order">
            </form>

        <?php
            if (isset($_POST['submitupdateorder']))
            {
                $order = $_POST['order'] ?? null;
                $status = $_POST['status'] ?? null;

                if(!$order || !$status)
                {
                    echo "<p style='color:red'><b>Missing order or status</b></p>";
                    exit;
                }
            
                $update = $pdo->prepare("UPDATE ORDERS SET OrderStatus = ? WHERE TrackingID = ?");
                $update->execute([$status, $order]);

                if($update->rowCount() > 0)
                {   
                    echo "<p style='color:green;'><b>Order Updated!</b></p>";
                
                    $resultStmt = $pdo->prepare("SELECT OrderStatus FROM ORDERS WHERE TrackingID = ?");
                    $resultStmt->execute([$order]);
                    $updatedOrder = $resultStmt->fetch(PDO::FETCH_ASSOC);
                    
                    if($updatedOrder)
                    {
                        $updateStatus = $updatedOrder['OrderStatus'];

                        echo "<p><b>Order: $order now has OrderStatus of: $updateStatus</b></p>"; 
                    }
                }
                else
                {
                    echo "<p style='color:red;'><b>No Update Applied (same status or invalid order)</b></p>";
                }
            }  
        ?>
        
        <a href="https://students.cs.niu.edu/~z1977897/gpstore.php" class="top-right-btn2">
            Store Home
        </a> 
    </body>
</html>
