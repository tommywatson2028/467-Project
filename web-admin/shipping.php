<?php
require_once __DIR__ . '/../shared/helpers.php';
require_once __DIR__ . '/../backend/adminFunctions.php'; // backend provides save_shipping_brackets() and get_shipping_brackets()

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Expected: returns an error string, or '' on success
    $message = save_shipping_brackets($_POST['brackets'] ?? []) ?: 'Saved.';
}

// Expected: array of ['bracket_id', 'min_weight', 'max_weight' (null = no limit), 'charge']
$brackets = get_shipping_brackets();
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Admin - Shipping Charges</title>
  <link rel="stylesheet" href="/shared/css/styles.css">
</head>
<body class="adm-page">
  <nav class="nav-bar">
    <a href="/web-admin/index.php" class="nav-link">Orders</a>
    <a href="/web-admin/shipping.php" class="nav-link active">Shipping Charges</a>
  </nav>
  <div class="page-header">
    <img class="logo" src="/shared/images/NEMT_LOGO.png" alt="NEMT Auto Parts">
    <h1 class="title">Shipping Charges</h1>
  </div>
  

  <div class="adm-card">
    <h2 class="table-title">Weight Brackets</h2>
    <p class="form-desc">Charge applies to the total weight of an order. Leave Max weight empty for no upper limit.</p>
    <form method="post" action="/web-admin/shipping.php">
      <table class="data-table">
        <tr><th>Bracket</th><th>Min weight (lb)</th><th>Max weight (lb)</th><th>Charge ($)</th></tr>
        <?php foreach ($brackets as $n => $b): ?>
        <tr>
          <td><label class="info-field"><?= h($b['bracket_id']) ?></label></td>
          <td><input class="input-field" type="number" step="0.01" min="0" name="brackets[<?= $n ?>][min_weight]" value="<?= h($b['min_weight']) ?>"></td>
          <td><input class="input-field" type="number" step="0.01" min="0" name="brackets[<?= $n ?>][max_weight]" value="<?= h($b['max_weight']) ?>"></td>
          <td><input class="input-field" type="number" step="0.01" min="0" name="brackets[<?= $n ?>][charge]" value="<?= h($b['charge']) ?>"></td>
        </tr>
        <?php endforeach; ?>
      </table>
      <p><button class="submit-btn" type="submit">Save</button></p>
    </form>
    <p class="form-desc"><?= h($message) ?></p>
  </div>
</body>
</html>
