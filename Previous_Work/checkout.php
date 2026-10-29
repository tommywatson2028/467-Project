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

    ini_set('display_errors', 1);
    error_reporting(E_ALL);

    $TotalPrice = 0.00;

    $sqlPrepared = $pdo->prepare("
        SELECT STUFFEDANIMALSTORE.StuffieID, STUFFEDANIMALSTORE.Price, SHOPPINGCART.CartQty
        FROM SHOPPINGCART 
        JOIN STUFFEDANIMALSTORE 
        ON SHOPPINGCART.StuffieID = STUFFEDANIMALSTORE.StuffieID
        WHERE SHOPPINGCART.TrackingID = ? 
    "); // TrackingID = SessionID

    $sqlPrepared->execute([$trackingID]);
    $cartItems = $sqlPrepared->fetchAll(PDO::FETCH_ASSOC);

    $PriceArray = [];
?>

<?php
    $DefaultStatus = "Processing";
    foreach($cartItems as $item)
    {
        $Price = (float)$item['Price'];
        $Qty = (int)$item['CartQty'];
                        
        $lineTotal = $Price * $Qty;

        $PriceArray[] = number_format($lineTotal, 2, '.', ''); // Adds current Price into the array to be used and formatted later
        $TotalPrice += $lineTotal;
    }

    $FormatTotal = number_format($TotalPrice, 2, '.', '');
    $errorMessage = "";

    if ($_SERVER["REQUEST_METHOD"] === "POST")
    {
        $CreditCard = $_POST["Credit_Card"] ?? "";
        $ShipAdd    = $_POST["Ship_Add"] ?? "";
        $BillAdd    = $_POST["Bill_Add"] ?? "";

        if (!preg_match('/^\d{16}$/', $CreditCard))
        {
            $errorMessage = "Credit Card must be exactly 16 digits.";
        }
        elseif (!empty($ShipAdd) &&
                !empty($BillAdd) &&
                strlen($ShipAdd) <= 128 &&
                strlen($BillAdd) <= 128)
        {
            // Insert the order
            $sqlInsert = $pdo->prepare("
                INSERT INTO ORDERS (TrackingID, OrderStatus, Total, CCInfo, ShippingAddr, BillingAddr)
                VALUES (?, ?, ?, ?, ?, ?)
            ");

            $sqlInsert->execute([
                $trackingID,
                $DefaultStatus,
                $TotalPrice,
                $CreditCard,
                $ShipAdd,
                $BillAdd
            ]);

            //remove from inventory
            $subtractStmt = $pdo->prepare("
                SELECT STUFFEDANIMALSTORE.StuffieID, SHOPPINGCART.CartQty, STUFFEDANIMALSTORE.InvQty
                FROM SHOPPINGCART 
                JOIN STUFFEDANIMALSTORE 
                ON SHOPPINGCART.StuffieID = STUFFEDANIMALSTORE.StuffieID
                WHERE SHOPPINGCART.TrackingID = ?
            "); 

            $subtractStmt->execute([$trackingID]);
            $removeItems = $subtractStmt->fetchAll(PDO::FETCH_ASSOC);

            foreach($removeItems as $rItem)
            {
                $newQty = $rItem['InvQty'] - $rItem['CartQty'];
                $ID = $rItem['StuffieID'];
                
                $remove = $pdo->prepare("UPDATE STUFFEDANIMALSTORE SET InvQty = ? WHERE StuffieID = ?");
                $remove->execute([$newQty, $ID]);
            }

            session_regenerate_id(true);
            echo $trackingID;

            // Redirect ONLY after successful insert
            header("Location: trackpage.php?success=1");
            exit;
        }
    }
?>

<!DOCTYPE HTML>
<html>
    <head>
        <meta charset="UTF-8">
        <style>
            .track-form {
                width: 90%;
                max-width: 400px;
                margin: 0 auto;
                text-align: center;
            }

            .track-form input {
                width: 100%;
                padding: 12px;
                font-size: 1rem;
                border: 2px solid pink;
                border-radius: 8px;
                margin-bottom: 10px;
                box-sizing: border-box;
            }

            .track-form button {
                margin-top: 10px;
                padding: 10px 16px;
                background-color: hotpink;
                color: white;
                border: none;
                border-radius: 8px;
                cursor: pointer;
            }

            .track-form button:hover {
                background-color: deeppink;
            }

                .top-right-btn {
                position: fixed;
                top: 15px;
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
            }

            .top-right-btn:hover {
                background-color: deeppink;
                transform: scale(1.05);
            }

            /* ------------------------------
            Top Button 2 
            ------------------------------*/
            .top-right-btn2 {
                position: fixed;
                top: 60px;
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
            }

            .top-right-btn2:hover {
                background-color: deeppink;
                transform: scale(1.05);
            }

                .page-wrapper {
                width: 50%;
                max-width: 1000px;
                margin: 30px auto;
                padding-top: .2%;
                padding-left: 3%;
                padding-right: 3%;
                padding-bottom: 3%;
                background: #ffe4f1;
                border-radius: 20px;
                box-shadow: 0px 4px 15px rgba(0,0,0,0.1);
                position: relative;
                z-index: 2; /* keeps content above side images */
            }

            body {
                background-color: lavender;
                margin: 0;
                font-family: Arial, sans-serif;
            }
            .checkout-top {
                background-color: #ffcaeb;
                margin: 10px auto;
                font-size: 60px;
                font-weight: bold;
                padding: 3%;
            }    
            .checkout {
                margin: 5px auto;
                padding: 3%;
                border-radius: 10px;
            }
            .checkout-prices {
                
                font-size: 30px;
                color: hotpink;
            }
            .checkout-total {
                font-size: 60px;
                font-color: white;
                font-weight: bold;
                color: white;
                background-color: hotpink;
                padding: 3%;
            }
        </style>
    </head>

    <body>
        <div class="page-wrapper">
            <div class="checkout checkout-prices">
                <div class="checkout-top">
                    Checkout Total
                </div>

                <br>

                <?php
                    foreach ($PriceArray as $value)
                    {
                        echo "$" .  $value . "<br>" . "<p></p>";
                    }
                ?>
            </div>

            <div class="checkout checkout-total">
                Total: 
                $<?php echo $FormatTotal?>
            </div>
        </div>

        <?php if ($TotalPrice > 0): ?>
        <h1 style="text-align:center">THIS WILL BE YOUR TRACKING ID, PLEASE KEEP NOTE OF IT:</h1>
        <h1 style="text-align:center"><?php echo $trackingID; ?></h1>

        <?php if (!empty($errorMessage)): ?>
        <p style="color:red; font-size:20px; text-align:center;">
            <?= $errorMessage ?>
        </p>

        <?php endif; ?>
        <form method="POST" class="track-form">
            <input type="text" placeholder="CC (16 digits)" name="Credit_Card" required>
            <input type="text" placeholder="ShipAddr" name="Ship_Add" required>
            <input type="text" placeholder="BillAddr" name="Bill_Add" required>
            <button type="submit">Place Order</button>
        </form>

        <h3><br></h3>

        <?php else: ?>
        <p style="color:red; font-size:24px; text-align:center;">
            Your cart is empty — add items before checking out.
        </p>

        <?php endif; ?>
        <a href="https://students.cs.niu.edu/~z1977897/gpstore.php" class="top-right-btn">
            Store Home
        </a>

        <a href="https://students.cs.niu.edu/~z1977897/trackpage.php" class="top-right-btn2">
            Track Your Package
        </a>
    </body>
</html>
