<?php
require_once __DIR__ . '/../shared/helpers.php';
require_once __DIR__ . '/../backend/adminFunctions.php'; // backend provides search_orders()

// Expected: array of ['id', 'date', 'customer_name', 'status', 'total']
$orders = search_orders($_GET);
$status = $_GET['status'] ?? '';
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Admin - Orders</title>
  <link rel="stylesheet" href="/shared/css/styles.css">
</head>
<nav class="nav-bar">
    <a href="/web-admin/index.php" class="nav-link active">Orders</a>
    <a href="/web-admin/shipping.php" class="nav-link">Shipping Charges</a>
</nav>
<body class="adm-page">
  <div class="page-header">
    <img class="logo" src="/shared/images/NEMT_LOGO.png" alt="NEMT Auto Parts">
    <h1 class="title">All Orders</h1>
  </div>
  
    <div class="adm-card">
      <h2 class="table-title">Search Orders</h2>
      <form class="filters" method="get" action="/web-admin/index.php">
        <label>Date from <input class="input-field" type="date" name="date_from" value="<?= h($_GET['date_from'] ?? '') ?>"></label>
        <label>Date to <input class="input-field" type="date" name="date_to" value="<?= h($_GET['date_to'] ?? '') ?>"></label>
        <label>Status
          <select class="input-field" name="status">
            <option value="">All</option>
            <option value="Authorized" <?= $status === 'Authorized' ? 'selected' : '' ?>>Authorized</option>
            <option value="Shipped" <?= $status === 'Shipped' ? 'selected' : '' ?>>Shipped</option>
          </select>
        </label>
        <label>Min price <input class="input-field" type="number" min="0" step="0.5" name="min_price" value="<?= h($_GET['min_price'] ?? '') ?>"></label>
        <label>Max price <input class="input-field" type="number" min="0" step="0.5" name="max_price" value="<?= h($_GET['max_price'] ?? '') ?>"></label>
        <button class="submit-btn" type="submit">Search</button>
        <button class="secondary-btn" type="button" onclick="window.location.href='/web-admin/index.php'">Clear</button>
      </form>
    </div>

    <div class="adm-card">
      <h2 class="table-title">Results</h2>
      <?php if (!$orders): ?>
        <p>No orders found.</p>
      <?php else: ?>
      <table class="data-table">
        <tr><th>Order #</th><th>Date</th><th>Customer</th><th>Status</th><th class="num">Total</th></tr>
        <?php foreach ($orders as $o): ?>
        <tr>
          <td><a class="nav-link" href="/web-admin/order.php?id=<?= urlencode($o['id']) ?>"><?= h($o['id']) ?></a></td>
          <td><?= h($o['date']) ?></td>
          <td><?= h($o['customer_name']) ?></td>
          <td><?= h($o['status']) ?></td>
          <td class="num"><?= money($o['total']) ?></td>
        </tr>
        <?php endforeach; ?>
      </table>
      <?php endif; ?>
    </div>
</body>
</html>
