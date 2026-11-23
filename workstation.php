<!-- Auto Part Shop Employee Page for Workstations
Allow Workstation employees to print packing lists, retrieve the items from the warehouse,
Package them up, and an invoice and shipping label, record the order status (processed, shipped, delivered)
Then send the customer an email that the order has shipped
--!>

<html>
    <head>
        <title>Employee Page Workstation</title>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>       
        <link href="https://fonts.googlapis.com/css2?family=Nunito:ital,wght@0,200..1000;1,200..1000&display=swap" rel="stylesheet">

        <link rel="stylesheet" href="../shared/css/styles.css">


    </head>
    <body class='adm-page'>
        
        <h1><b>All Products:</b></h1>

        <?php

            ini_set('display_errors', 1);
            ini_set('display_startup_errors', 1);
            error_reporting(E_ALL);

            $username = "z2015929";//Will be changed later
            $passwd = "2006Aug30";//Will be changed later

            try{
                $dsn = "mysql:host=courses;dbname={$username}";
                $pdo = new PDO($dsn, $username, $passwd);
                }

            catch(PDOException $e) {
                echo "Connection failed " . $e->getmessage();
                exit();
                }

 #Step 1 Create a list of all the products in a Table format
           $step1 = "SELECT PARTS.Num, PARTS.Description, PARTS.Price, PARTS.Weight, INVENTORY.InvQty FROM PARTS
           JOIN INVENTORY ON PARTS.Num = INVENTORY.Num;";

           $result1 = $pdo->query($step1);
           $answer1 = $result1->fetchAll(PDO::FETCH_ASSOC);

           echo "<table border='3'>";
           echo "<tr>";


            if (!empty($answer1)) {
                foreach($answer1[0] as $key => $value) {
                    echo "<th>" . htmlspecialchars($key) . "</th>";
                    }
            }
            echo "</tr>";
            #print rows
                foreach($answer1 as $row) {
                    echo "<tr>";
                foreach($row as $value) {
                    echo "<td>" . htmlspecialchars($value) . "</td>";
                    }
            echo "</tr>";
        }

      echo "</table>";

?>  
    <h1><b>All Orders:</b></h1>

    <?php
 #Step 2 Create a list of all orders in a Table format
    $step2 = "SELECT TrackingID, Status, Total, Name, CCNumber, City, Street, Contact FROM ORDERS;";
           

           $result2 = $pdo->query($step2);
           $answer2 = $result2->fetchAll(PDO::FETCH_ASSOC);

           echo "<table border='3'>";
           echo "<tr>";


            if (!empty($answer2)) {
                foreach($answer2[0] as $key => $value) {
                    echo "<th>" . htmlspecialchars($key) . "</th>";
                    }
            }
            echo "</tr>";
            #print rows
                foreach($answer2 as $row) {
                    echo "<tr>";
                foreach($row as $value) {
                    echo "<td>" . htmlspecialchars($value) . "</td>";
                    }
            echo "</tr>";
        }

      echo "</table>";



 #Check if Step 4 POST so Packing List is Up to Date
    
                    if(isset($_POST['step4']))
                    {    
                        $shipID = filter_input(INPUT_POST, 'order', FILTER_VALIDATE_INT);

                        if(!$shipID)
                        {
                            echo "<p style='color:red'>Invalid TrackingID</p>";
                        }
                        else
                        {
                            $pdo->beginTransaction();

                            #Check to ensure that order isn't shipped multiple times
                            $statusStmt = $pdo->prepare("UPDATE ORDERS SET Status = 'Shipped'
                            WHERE TrackingID = ? AND Status = 'Processed'");
                            $statusStmt->execute([$shipID]);

                            if($statusStmt->rowCount() !== 1)
                            {
                                $pdo->rollBack();
                                echo "<p style='color:red'>Order was already shipped.</p>";
                            }
                            else
                            {
                                #Check to ensure OrderQty is below InvQty
                                $checkStmt2 = $pdo->prepare("SELECT COUNT(*) FROM INVENTORY
                                JOIN REQUESTS ON INVENTORY.Num = REQUESTS.Num
                                WHERE REQUESTS.TrackingID = ?
                                AND REQUESTS.OrderQty > INVENTORY.InvQty");
                                $checkStmt2->execute([$shipID]);

                                if ($checkStmt2->fetchColumn() > 0)
                                {
                                    $pdo->rollBack();
                                    echo "<p style='color:red'>Can't ship order, insufficient inventory</p>";
                                }
                                else
                                {
                        
                                    $updateSql = $pdo->prepare("UPDATE INVENTORY JOIN REQUESTS ON INVENTORY.Num = REQUESTS.Num
                                    SET INVENTORY.InvQty = INVENTORY.InvQty - REQUESTS.OrderQty
                                    WHERE REQUESTS.TrackingID = ?");
                                    $updateSql->execute([$shipID]);
                                
                                    $pdo->commit();                        
                                    echo "<p style='color:green'>Order Shipped!</p>";
                               }
                            }
                        }
                    }

?>
 
<!--Step 3 Create a Packing List and a shipping button to ship the items listed --!>
    <h1><b>Print a Packing List!</b></h1>

    <form method='POST'>
    <label>Choose an Order:</label>
    <select name='order' id='order'>

    <?php

    $OrderList= $pdo->query("SELECT TrackingID FROM ORDERS WHERE Status = 'Processed'");
    while( $row = $OrderList->fetch())
    {
        echo "<option value='{$row['TrackingID']}'>{$row['TrackingID']}</option>";
    }

    echo "</select>";

    echo "<br/>";

    #Create a Packing List if POST

    echo "<input type='submit' name='step3' value='Print Packing List'>";
    echo "</form>";

#Check if there was an answer submitted for Print a Packing List
            if(isset($_POST['step3']))
            {
                $trackingID = filter_input(INPUT_POST, 'order', FILTER_VALIDATE_INT); 

               if(!$trackingID)
               {
                    echo "<p style='color:red'>Invalid TrackingID</p>";
                    
               }
               else
               {
                    $checkStmt = $pdo->prepare("SELECT ORDERS.TrackingID, ORDERS.Name, ORDERS.Street, ORDERS.City, REQUESTS.Num, REQUESTS.OrderQty, PARTS.Description FROM ORDERS
                    JOIN REQUESTS ON ORDERS.TrackingID = REQUESTS.TrackingID
                    JOIN PARTS ON REQUESTS.Num = PARTS.Num
                    WHERE ORDERS.TrackingID = ?");

                    $checkStmt->execute([$trackingID]);
                    $items = $checkStmt->fetchAll(PDO::FETCH_ASSOC);
               
                    if(!$items)
                    {
                         echo "<p style='color:red'>Invalid Request</p>";
                    }
                    else
                    {
               
                        $first = $items[0];
                        echo "<h1><b>Packing List</b></h1>";
                        echo "<p>Order # " . htmlspecialchars($first['TrackingID']) . "<br/>";
                        echo "Name: " . htmlspecialchars($first['Name']) . "<br/>";
                        echo "Street: " . htmlspecialchars($first['Street']) . "<br/>";
                        echo "City: " . htmlspecialchars($first['City']) . "</p>";

                    #Table for the items in the order
                        echo "<table border='4'>";
                        echo "<tr><th>Part #</th><th>Description</th><th>Qty</th></tr>";

                        foreach($items as $item)
                        {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($item['Num']) . "</td>";
                            echo "<td>" . htmlspecialchars($item['Description']) . "</td>";
                            echo "<td>" . htmlspecialchars($item['OrderQty']) . "</td>";
                            echo "</tr>";

                        }

                        echo "</table>";
               
        #Step 4 Allow the Employee to ship the order
                echo "<h1><b>Ship the Order?</b></h1>";

                echo "<form method='POST'>";
                echo "<input type='hidden' name='order' value='" . (int)$trackingID . "'>";
                echo "<input type='submit' name='step4' value='Ship Order'>";
                
                echo "</form>"; 
                    }
               }
           }
    ?>

        <!-- Step 5 Make a button  to return -->
        <style>
            .store-button {
                position: fixed;
                top: 15px;
                right: 15px;
                
                background-color: red;
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
}
            
.store-button:hover {
    background-color: white;
    transform: scale(1.05);
}

            
        </style>
        
        <a href="https://students.cs.niu.edu/~z2022615/NEMT-AUTO/web-login/loginPage.php" class="store-button">
            Login Page
        </a>
        
    </body>
</html>
