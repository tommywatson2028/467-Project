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

    // List of ads
    $ads = 
    [
        [
            "img" => "https://students.cs.niu.edu/~z1977897/ad1vb.png",
            "link" => "https://www.niu.edu/academic-support/get-help/tutoring.shtml"
        ],
        [
            "img" => "https://students.cs.niu.edu/~z1977897/ad2.png",
            "link" => "https://www.niu.edu/academic-support/get-help/tutoring.shtml"
        ],
        [
            "img" => "https://students.cs.niu.edu/~z1977897/ad3.png",
            "link" => "https://www.niu.edu/academic-support/get-help/tutoring.shtml"
        ],
        [
            "img" => "https://students.cs.niu.edu/~z1977897/ad4.gif",
            "link" => "https://acm.cs.niu.edu/"
        ]
    ];

    // Pick left ad
    $leftAd  = $ads[array_rand($ads)];
	
    // Pick right ad, different from left ad
    do 
    {
        $rightAd = $ads[array_rand($ads)];
    } 
    while ($rightAd == $leftAd);


    if ($_SERVER['REQUEST_METHOD'] === 'POST')
    {
        //REMOVE ITEM
        if (isset($_POST['remove_id']))
        {
            $stmt = $pdo->prepare("
                DELETE FROM SHOPPINGCART
                WHERE TrackingID = ? AND StuffieID = ?
            ");
            $stmt->execute([$trackingID, $_POST['remove_id']]);
        }

        //UPDATE QUANTITY
        if (isset($_POST['update_id'], $_POST['new_qty']))
        {
            $id = $_POST['update_id'];
            $qty = intval($_POST['new_qty']);

            $curqua = $pdo->prepare("SELECT InvQty FROM STUFFEDANIMALSTORE WHERE StuffieID = ?");
            $curqua->execute([$id]);
            $row = $curqua->fetch(PDO::FETCH_ASSOC);

            $stock = (int)$row['InvQty'];

            if ($qty > 0 and $qty <= $stock)
            {
                $stmt = $pdo->prepare("
                UPDATE SHOPPINGCART
                SET CartQty = ?
                WHERE TrackingID = ? AND StuffieID = ?
                ");
                $stmt->execute([$qty, $trackingID, $_POST['update_id']]);
            }
            elseif($qty == 0)
            {
                //Remove item if quantity = 0
                $stmt = $pdo->prepare("
                DELETE FROM SHOPPINGCART
                WHERE TrackingID = ? AND StuffieID = ?
                ");
                $stmt->execute([$trackingID, $_POST['update_id']]);
            }
            else
            {
                $stmt = $pdo->prepare("
                UPDATE SHOPPINGCART
                SET CartQty = ?
                WHERE TrackingID = ? AND StuffieID = ?
                ");
                $stmt->execute([$stock, $trackingID, $_POST['update_id']]);
            }
        }

        //Redirect to prevent resubmission
        header("Location: https://students.cs.niu.edu/~z1977897/shoppingcart.php");
        exit;
    }
?>

<html>
    <head>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200..1000;1,200..1000&display=swap" rel="stylesheet">

        <style>
            h1 {text-align: center; font-family:'Nunito', sans-serif; color:hotpink; padding: 15px;}
            p {text-align: center; font-family:'Nunito', sans-serif; color:lightpink;}
            th {text-align: center; font-family:'Nunito', sans-serif; color:lightpink;}
            div {text-align: center;}
            img 
            {
            display: block;
            margin-left: auto;
            margin-right: auto;
            width: 75%; /* Optional: set a width smaller than the container */
            }
            td 
            {
            border: 1px solid lightgray;
            border-radius: 10px;
            word-wrap: break-word;
            overflow-wrap: break-word;
            background-color: white;
            text-align: center; font-family:'Nunito', sans-serif; color:lightpink;
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

            .button2
            {
            background-color: white; 
            color: black; 
            border: 2px solid hotpink;
            border-radius: 10px;
            text-align: center;
            padding: 10px 16px;
            font-family:'Nunito', sans-serif; 
            color:lightpink;
            text-decoration: none;
            }

            .button2:hover 
            {
            background-color: pink;
            color: white;
            border-radius: 10px;
            text-align: center;
            padding: 10px 16px;
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

            input[type="number"] {
            border: none;
            outline: none;
            background-color: transparent;
            font-family: 'Nunito', sans-serif;
            color: hotpink;
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
                left: 10px;   /* space from edge */
            }

            .ad-right {
                right: 10px;  /* space from edge */
            }

        </style>
    </head>

    <body style="background-color:Lavender">

<?php
	// Left ad
	echo '<a href="' . $leftAd["link"] . '">';
	echo '<img class="side-ad ad-left" src="' . $leftAd["img"] . '">';
	echo '</a>';

	// Right ad
	echo '<a href="' . $rightAd["link"] . '">';
	echo '<img class="side-ad ad-right" src="' . $rightAd["img"] . '">';
	echo '</a>';
?>

        <h1>Stuffie Store<hr></h1>
        <a href="https://students.cs.niu.edu/~z1977897/gpstore.php" class="top-right-btn2">
            Home
        </a>

        <table width="75%" border="0">    
            <tr>
            <th>Stuffie Name</th>
            <th>Price</th>
            <th>Quantity</th>
            <th>Remove</th>
            <th></th>
            </tr>

            <?php
                $statement = $pdo->prepare("
                    SELECT s.StuffieID, s.ProductName, s.Price, c.CartQty
                    FROM SHOPPINGCART c
                    JOIN STUFFEDANIMALSTORE s
                    ON c.StuffieID = s.StuffieID
                    WHERE c.TrackingID = ?
                ");

                $statement->execute([$trackingID]);

                if ($statement->rowCount() === 0) {
                    echo "<tr><td colspan='4'>Shopping cart is empty</td></tr>";
                }

                while ($row = $statement->fetch())
                {
                    echo "<tr>
                    <td>{$row['ProductName']}</td>
                    <td>{$row['Price']}</td>

                    <td> <form method='POST' style='margin:0; display:flex; justify-content:center; gap:5px;'>
                    <input type='hidden' name='update_id' value='{$row['StuffieID']}'>
                    <input type='number' name='new_qty' value='{$row['CartQty']}' min='0' style='width:60px; text-align:center;'>
                    <button type='submit' class='button2'>Update</button>
                    </form> </td>

                    <td> <form method='POST' style='margin:0;'>
                    <input type='hidden' name='remove_id' value='{$row['StuffieID']}'>
                    <button type='submit' class='button1'>X</button>
                    </form> </td>
                    </tr>";
                }
            ?>
        </table>

        <div style="text-align: center; margin-top: 30px;">
            <a href="https://students.cs.niu.edu/~z1977897/checkout.php" class="button2">
            Continue to checkout
            </a>
        </div>
    </body>
</html>
