<?php
include '../shared/helpers.php';
include '../backend/adminFunctions.php'; // backend provides get_order()
include '../shared/setConnectionParams.php';

try {
  $pdo = new PDO($dsn, $username, $password);
}
catch (PDOException $e) {
   echo 'Connection to database failed: ' . $e->getMessage();
}

// Expected: ['id','date','status','customer_name','email','address','auth_number','weight',
//            'subtotal','shipping','total','items'=>[['part_number','description','qty','price']]] or null
$order = get_order($_GET['id'] ?? '');
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Admin - Order Detail</title>
  <link rel="stylesheet" href='../shared/css/styles.css'>
</head>
<nav class="nav-bar">
    <a href='index.php' class="nav-link">Orders</a>
    <a href='shipping.php' class="nav-link">Shipping Charges</a>
</nav>
<body class="adm-page">
  <div class="page-header">
    <img class="logo" src='../shared/images/NEMT_LOGO.png' alt="NEMT Auto Parts">
    <h1 class="title">ADMIN - ORDER <?= $order ? '#' . h($order['id']) : '' ?></h1>
  </div>
  

  <div class="adm-card">
    <?php if (!$order): ?>
      <p class="error">Order not found.</p>
    <?php else: ?>
    <dl class="detail-grid">
      <dt>Date</dt><dd><?= h($order['date']) ?></dd>
      <dt>Status</dt><dd><?= h($order['status']) ?></dd>
      <dt>Customer</dt><dd><?= h($order['customer_name']) ?></dd>
      <dt>Email</dt><dd><?= h($order['email']) ?></dd>
      <dt>Mailing address</dt><dd><?= h($order['address']) ?></dd>
      <dt>Authorization #</dt><dd><?= h($order['auth_number']) ?></dd>
      <dt>Total weight</dt><dd><?= h($order['weight']) ?> lb</dd>
    </dl>

    <table class="data-table">
      <tr><th>Part #</th><th>Description</th><th class="num">Qty</th><th class="num">Unit price</th><th class="num">Line total</th></tr>
      <?php foreach ($order['items'] as $i): ?>
      <tr>
        <td><?= h($i['part_number']) ?></td>
        <td><?= h($i['description']) ?></td>
        <td class="num"><?= h($i['qty']) ?></td>
        <td class="num"><?= money($i['price']) ?></td>
        <td class="num"><?= money($i['price'] * $i['qty']) ?></td>
      </tr>
      <?php endforeach; ?>
      <tr><td colspan="4" class="num">Subtotal</td><td class="num"><?= money($order['subtotal']) ?></td></tr>
      <tr><td colspan="4" class="num">Shipping &amp; handling</td><td class="num"><?= money($order['shipping']) ?></td></tr>
      <tr><td colspan="4" class="num">Total</td><td class="num"><?= money($order['total']) ?></td></tr>
    </table>
    <?php endif; ?>
  </div>
</body>
</html>
