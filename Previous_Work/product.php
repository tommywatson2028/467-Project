<?php
    try 
    {
        $username = "z1977897";
        $password = "2004Mar29";
        $dsn = "mysql:host=courses;dbname=z1977897";
        $pdo = new PDO($dsn, $username, $password);
    }
    catch(PDOException $e)
    {
        echo "Connection to database failed: " . $e->getMessage();
    }

    session_start();
    $trackingID = session_id();

    //check if id exists
    if (!isset($_GET['id'])) {
        die("No product selected.");
    }

    $id = $_GET['id'];

    //get product
    $stmt = $pdo->prepare("SELECT * FROM STUFFEDANIMALSTORE WHERE StuffieID = ?");
    $stmt->execute([$id]);
    $product = $stmt->fetch();

    //check if product exists
    if (!$product) {
        die("Product not found.");
    }
?>

<html>
    <head>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200..1000;1,200..1000&display=swap" rel="stylesheet">

        <style>
            h1 {text-align: center; font-family:'Nunito', sans-serif; color:hotpink; padding: 15px;}
            p {text-align: center; font-family:'Nunito', sans-serif; color:purple; word-break: break-word;}
            div {text-align: center;}

            form {text-align: center;}
            img 
            {
                display: block;
                margin-left: auto;
                margin-right: auto;
                width: 25%; /* Optional: set a width smaller than the container */
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
                padding: 5px 10px;
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

            .top-right-btn
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

            .top-right-btn:hover 
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

        <h1><?= htmlspecialchars($product['ProductName']) ?></h1>

        <img src="<?= htmlspecialchars($product['ImagePath']) ?>" width="300">

        <p>Price: $<?= $product['Price'] ?></p>
        <p>Stock: <?= $product['InvQty'] ?></p>
        <p><?= htmlspecialchars($product['StuffieDescription']) ?></p>

        <form method="POST" action="gpstore.php">
            <input type="hidden" name="stuffie_id" value="<?= $product['StuffieID'] ?>">
        
            <p>Quantity:
                <input type="number" name="qty" value="1" min="1" max="<?= $product['InvQty'] ?>" required>

                <button class="button button1" type="submit" name="addtocart">Add to Cart</button>
            </p>
        </form>

        <a href="https://students.cs.niu.edu/~z1977897/gpstore.php" class="top-right-btn">
            Store Home
        </a>
    </body>
</html>
