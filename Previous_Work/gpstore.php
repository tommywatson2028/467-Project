<?php
    require_once "storedCreds.php";
?>

<?php
	try 
	{
	   $pdo = new PDO($stored_database, $stored_user, $stored_pass);
	    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}
	catch(PDOException $e)
	{
	    echo "Connection to database failed: " . $e->getMessage();
	}

    session_start();
    $trackingID = session_id();

    $stmt = $pdo->prepare("SELECT * FROM STUFFEDANIMALSTORE");
    $stmt->execute();
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if(isset($_POST['qty']))
    {
        $qty = $_POST['qty'];
    }
    else
    {
        $qty = 1;
    }

    if(isset($_POST['addtocart']))
    {
        $stuffieID=$_POST['stuffie_id'];

        // Get current inventory quantity for this item
        $invStmt = $pdo->prepare("SELECT InvQty FROM STUFFEDANIMALSTORE WHERE StuffieID = ?");
        $invStmt->execute([$stuffieID]);
        $invRow = $invStmt->fetch();
        
        // Store inventory quantity for comparison
        $stockQty = (int)$invRow['InvQty'];

        // Check if item is already in the user's cart
        $statement = $pdo->prepare("SELECT CartQty FROM SHOPPINGCART WHERE TrackingID = ? AND StuffieID = ?");
        $statement->execute([$trackingID, $stuffieID]);
        $row = $statement->fetch();

        if($row)
        {
            // Calculate new quantity if one more item is added
            $currentQty = (int)$row['CartQty'];
            $newQty = $currentQty + $qty;

            // Prevent adding more items than what is available in stock
            if ($newQty > $stockQty)
            {
                echo "Unable to add more than what is in stock. There is currently $stockQty available.";
            }
            else
            {
            // Update cart with checked quantity
                $stmt = $pdo->prepare("UPDATE SHOPPINGCART SET CartQty = ? WHERE TrackingID = ? AND StuffieID = ?");
                $stmt->execute([$newQty, $trackingID, $stuffieID]);
            }
        }
        else
        {
            // Prevent adding item if it is out of stock
            if ($stockQty < 1)
            {
                echo "This item is out of stock.";
            }
            else
            {
                // Insert new item into cart with quantity of 1
                $stmt = $pdo->prepare("INSERT INTO SHOPPINGCART (TrackingID, StuffieID, CartQty) VALUES (?, ?, ?)");
                $stmt->execute([$trackingID, $stuffieID, $qty]);
            }
        }
    }
?>

<html>
    <head>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200..1000;1,200..1000&display=swap" rel="stylesheet">

        <style>
            h1 {text-align: center; font-family:'Nunito', sans-serif; color:hotpink; padding: 15px;}
            p {text-align: center; font-family:'Nunito', sans-serif; color:lightpink; word-break: break-word;}
            div {text-align: center;}

            form {text-align: center;}
            img 
            {
                display: block;
                margin-left: auto;
                margin-right: auto;
                width: 75%; /* Optional: set a width smaller than the container */
                border-radius: 10px;
            }
            td
            {
                border: 1px solid lightgray;
                border-radius: 10px;
                word-wrap: break-word;
                overflow-wrap: break-word;
                background-color: white;
            }
            img:hover 
            {
                transform: scale(1.05);
                transition: 0.3s;
            }
            
            table 
            {
                margin: auto;
                border-radius: 10px;
                border-spacing: 20px;
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

            .top-right-btn3
            {
                position: fixed;
                top: 10px;
                right: 160px;

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

            .top-right-btn3:hover 
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

            .side-ad {
                position: fixed;
                top: 12%;              /* pushes them down a bit */
                width: 24vh;           /* narrow like ads */
                height: 75vh;          /* NOT full page height */
                object-fit: cover;
                z-index: 0;
                opacity: 0.85;
                border-radius: 10px;
            }

            .ad-left {
                pointer-events: none;
                left: 10px;   /* space from edge */
            }

            .ad-right {
                pointer-events: none;
                right: 10px;  /* space from edge */
            }
        </style>
    </head>

    <body style="background-color:Lavender">
        <img class="side-ad ad-left" src="https://media.tenor.com/lfDATg4Bhc0AAAAM/happy-cat.gif">
        <img class="side-ad ad-right" src="https://media.tenor.com/cb9L14uH-YAAAAAM/cool-fun.gif">
    
        <h1>Stuffie Store<hr></h1>
        <a href="https://students.cs.niu.edu/~z1977897/shoppingcart.php" class="top-right-btn2">
            My Cart
        </a>

        <a href="https://students.cs.niu.edu/~z1977897/trackpage.php" class="top-right-btn3">
            Track Your Package
        </a>

		<a href="https://students.cs.niu.edu/~z1977897/employee.php" class="bottom-right-btn">
            Employee Page
        </a>

        <table width="75%" border="0">    
            <tr>
                <?php
                    $count = 0;

                    foreach ($products as $product)
                    {
                        //this makes the table 3 wide
                        if ($count % 3 == 0 && $count != 0)
                        {
                            echo "</tr><tr>";
                        }

                        echo "
                        <td>
                            <br>
                            <a href='product.php?id={$product['StuffieID']}'>
                                <img src='{$product['ImagePath']}' width='300' height='auto'/>
                            </a>

                            <form method='post'>
                                <button class='button button1' type='submit' name='addtocart'>ADD TO CART</button>
                                <input type='hidden' name='stuffie_id' value='{$product['StuffieID']}'>
                            </form>

                            <p>{$product['ProductName']}</p>
                            <p>\${$product['Price']}</p>
                            <br>
                        </td>
                        ";

                        $count++;
                    }
                ?>
            </tr>
        </table>
    </body>
</html>
