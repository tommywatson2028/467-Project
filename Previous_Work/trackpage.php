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
    ?> 

<!DOCTYPE HTML>
<html>
    <head>
        <title>TrackingPage</title>
        <meta charset="UTF-8">

        <style>
            h2 {text-align: center; color:purple;}
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
                .side-ad {
                position: fixed;
                top: 10%;              /* pushes them down a bit */
                width: 110px;          /* narrow like ads */
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
            /* =======================
            PAGE WRAPPER
            ======================= */
            body {
                background-color: lavender;
                margin: 0;
                font-family: Arial, sans-serif;
                position: relative;
            }

            .page-wrapper {
                width: 90%;
                max-width: 1000px;
                margin: 30px auto;
                padding: 3%;
                background: #ffe4f1;
                border-radius: 20px;
                box-shadow: 0px 4px 15px rgba(0,0,0,0.1);
                position: relative;
                z-index: 2; /* keeps content above side images */
            }

            /* =======================
            HEADER
            ======================= */
            h1 {
                text-align: center;
                color: hotpink;
                margin-bottom: 20px;
            }

            /* =======================
            FORM
            ======================= */
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

            /* =======================
            IMAGE GALLERY
            ======================= */
            .gallery {
                display: flex;
                justify-content: center;
                flex-wrap: wrap;
                gap: 3%;
                margin-top: 30px;
                
            }

            .img-box {
                width: 22%;
                max-width: 200px;
                height: 28%;
                max-height: 255px;
                aspect-ratio: 1 / 1.2;
                object-fit: cover;
                transition: 0.3s ease;
            }

            /* =======================
            MOBILE
            ======================= */
            @media (max-width: 1000px) {
                .img-box {
                    width: auto;
                    max-width: 200px;
                    height: auto;          /* allow full height */
                    max-height: 255px;
                    aspect-ratio: auto;    /* remove forced ratio */
                    object-fit: contain;   /* show entire image */
                }

                .side-ad {
                    display: none !important;
                }

                .page-wrapper {
                    width: 95%;
                    margin: 20px auto;
                }
            }
        </style>

        <?php
            session_start();

            ini_set('display_errors', 1);
            error_reporting(E_ALL);

            /* =======================
            GET ORDER DATA
            ======================= */
            $order = null;
            $TotalPrice = "";

            if ($_SERVER["REQUEST_METHOD"] === "POST")
            {
                $TrackingID = $_POST["TrackingID"] ?? "";

                if (!empty($TrackingID) && strlen($TrackingID) <=128)
                {
                    $stmt = $pdo->prepare("
                        SELECT TrackingID, OrderStatus, Total 
                        FROM ORDERS 
                        WHERE TrackingID= ?
                    ");

                    $stmt->execute([$TrackingID]);
                    $order = $stmt->fetch(PDO::FETCH_ASSOC);
                }
            }
        ?>
    </head>

    <body>
        <?php
            if ($_SERVER["REQUEST_METHOD"] === "POST")
            {
                /* =======================
                TRACKING LOGIC
                ======================= */

                $status = $order["OrderStatus"] ?? "";
               
                function activeStep($status, $steps)
                {
                    return in_array($status, $steps)
                        ? "filter: hue-rotate(270deg) saturate(1.5);"
                        : "opacity: 0.3;";
                }

                $TotalPrice = 0.00;

                $sqlPrepared = $pdo->prepare("
                    SELECT STUFFEDANIMALSTORE.StuffieID, STUFFEDANIMALSTORE.Price, SHOPPINGCART.CartQty
                    FROM SHOPPINGCART 
                    JOIN STUFFEDANIMALSTORE 
                    ON SHOPPINGCART.StuffieID = STUFFEDANIMALSTORE.StuffieID
                    WHERE SHOPPINGCART.TrackingID = ? 
                "); // TrackingID = SessionID

                $sqlPrepared->execute([$TrackingID]);
                $cartItems = $sqlPrepared->fetchAll(PDO::FETCH_ASSOC);

                $PriceArray = [];

                $DefaultStatus = "OrderPlaced";
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
            }
        ?>

        <img class="side-ad ad-left" src="https://media.tenor.com/IRFM1RzwxV0AAAAm/goku-dance.webp">
        <img class="side-ad ad-right" src="https://media.tenor.com/cgByUMFw0r8AAAAm/minecraft-minecraft-steve.webp">

        <div class="page-wrapper">
            <a href="https://students.cs.niu.edu/~z1977897/gpstore.php" class="top-right-btn">
                Store Home
            </a>

            <a href="https://students.cs.niu.edu/~z1977897/shoppingcart.php" class="top-right-btn2">
                My Cart
            </a>
            <h1>TRACK YOUR ORDER</h1>

            <form method="POST" class="track-form">
                <input type="text" name="TrackingID" maxlength="64" placeholder="Enter Tracking ID" required>
                <button type="submit">Track Your Order</button>
            </form>

            <h2>
                <?php
                    if ($TotalPrice > 0)
                    {
                        echo "Amount paid: $" . number_format($TotalPrice, 2, '.', '');
                    }
                ?>
            </h2>

            <div class="gallery">
                <img class="img-box" title="Your Order Has Been Placed"
                style="<?= activeStep($status, ['OrderPlaced','Processing','Shipped','Delivered']) ?>"
                src="https://students.cs.niu.edu/~z1977897/order%20placed.png">

                <img class="img-box" title="Your Order is being Processed"
                style="<?= activeStep($status, ['Processing','Shipped','Delivered']) ?>"
                src="https://students.cs.niu.edu/~z1977897/order%20processing.png">

                <img class="img-box" title="Your Order Has Been Shipped!"
                style="<?= activeStep($status, ['Shipped','Delivered']) ?>"
                src="https://students.cs.niu.edu/~z1977897/order%20shipped.png">
                
                <img class="img-box" title="Your Order Has Arrived!"
                style="<?= activeStep($status, ['Delivered']) ?>"
                src="https://students.cs.niu.edu/~z1977897/order%20delivered.png">
            </div>
        </div>
    </body>
</html>
