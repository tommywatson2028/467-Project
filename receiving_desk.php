<!-- NEMT AUTO 
Employee Receiving Desk page
Produce a list of all the parts and InvQty's
Allow Employees to add InvQty to Parts using the Part #'s
--!>

<html>
    <head>
        <title>Employee Page Recieving Desk</title>
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
 #Check if there was an answer submitted for Add to InvQty
if (isset($_POST['step2']))
            {
                $part = $_POST['part'] ?? null;

                $check = true;

                $qty = filter_input(INPUT_POST, 'qty', FILTER_VALIDATE_INT);

                if($qty === false || $qty === null)
                {
                    echo "<p style='color:red'>Invalid Qty Input</p>";
                    $check = false;
                }
                
                if(!$part)
                {
                    echo "<p style='color:red'>No Product Selected</p>";
                    $check = false;
                }
                
                if($check)
                {
                    $checkStmt = $pdo->prepare("SELECT InvQty FROM INVENTORY WHERE Num = ?");
                    $checkStmt->execute([$part]);
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
                            $updateSql = $pdo->prepare("UPDATE INVENTORY SET InvQty = InvQty + ? WHERE Num = ?");
                            $updateSql->execute([$qty, $part]);
                            echo "<p style='color:green;'>Update complete</p>";
                            header("Location: " . $_SERVER['PHP_SELF'] . "?updated=1");
                            exit();

                            $resultStmt = $pdo->prepare("SELECT PARTS.Description, INVENTORY.InvQty FROM PARTS 
                            JOIN INVENTORY ON PARTS.Num = INVENTORY.Num
                            WHERE PARTS.Num = ?");
                            $resultStmt->execute([$part]);
                            $updatedQTY = $resultStmt->fetch(PDO::FETCH_ASSOC);

                            $qty = $updatedQTY['InvQty'];
                            $PartName = $updatedQTY['Description'];

                            echo "<p><b>Product: $PartName now has QTY of: $qty</b></p>";
                        }
                    }
                }
            }
         #Step 2 Allow the Employee to update the InvQty of the Parts
            echo "<h1><b>Received a Product?</b>
            <br/> Add them to Inventory Now!</h1>";

            echo "<form method='POST'>";
            echo "<label>Choose a Part:</label>";
            echo "<select name='part' id='part'>";

            $PartList = $pdo->query("SELECT Num, Description FROM PARTS");
            while( $row = $PartList->fetch())
            {
                echo "<option value='{$row['Num']}'>{$row['Description']}</option>";
            }

            echo "</select>";

            echo "<br/>";
            echo "How Many of That Specific Item to Restock?<input type='number' name='qty'>";

            echo "<input type='submit' name='step2' value='Add to InvQty'>";
            echo "</form>";
           
?>
<!-- Step 3 Make a button  to return -->
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
