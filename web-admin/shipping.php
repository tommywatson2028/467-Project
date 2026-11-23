<?php
session_start();

// Check admin privileges
if (!isset($_SESSION['EmpID']) || $_SESSION['IsAdmin'] !== true) {
    header('Location: ../web-login/employeeDashboard.php');
    exit();
}

// Include required files
include '../shared/helpers.php';
include '../backend/adminFunctions.php';
include '../shared/setConnectionParams.php';

// Connect to database
try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch (PDOException $e) {
    error_log($e->getMessage());
    exit('Unable to connect to the database.');
}

$message = '';
$brackets = get_shipping_brackets($pdo);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $rows = [];
    $ids = $_POST['bracket_id'] ?? [];
    $mins = $_POST['min_weight'] ?? [];
    $maxs = $_POST['max_weight'] ?? [];
    $charges = $_POST['charge'] ?? [];

    foreach ($mins as $i => $min) {
        $rows[] = [
            'bracket_id' => $ids[$i] ?? $i,
            'min_weight' => $min,
            'max_weight' => $maxs[$i] ?? null,
            'charge' => $charges[$i] ?? 0
        ];
    }

    if (save_shipping_brackets($pdo, $rows)) {
        $message = 'Shipping brackets saved successfully.';
        $brackets = get_shipping_brackets($pdo);
    } else {
        $message = 'Failed to save shipping brackets.';
        $brackets = $rows;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Admin - Shipping Charges</title>
  <link rel="stylesheet" href='../shared/css/styles.css'>
</head>
<body class="adm-page">
  <nav class="nav-bar">
    <a href='../web-login/employeeDashboard.php' class="nav-link">↩ Back</a>
    <a href='index.php' class="nav-link">Orders</a>
    <a href='shipping.php' class="nav-link active">Shipping Charges</a>
    <form class="nav-logout" action="../backend/logout.php" method="POST">
      <button type="submit">Log Out</button>
    </form>
  </nav>
  <div class="page-header">
    <img class="logo" src="../shared/images/NEMT_LOGO.png" alt="NEMT Auto Parts">
    <h1 class="title">Shipping Charges</h1>
  </div>
  

  <div class="adm-card">
    <h2 class="table-title">Weight Brackets</h2>
    <p class="form-desc">Charge applies to the total weight of an order. Leave Max weight empty for no upper limit.</p>
    <form method="post" action="shipping.php">
      <table class="data-table">
        <tr><th>Bracket</th><th>Min weight (lb)</th><th>Max weight (lb)</th><th>Charge ($)</th></tr>
        <?php foreach ($brackets as $n => $b): ?>
        <tr>
          <td><input type="hidden" name="bracket_id[]" value="<?= h($b['bracket_id']) ?>"><label class="info-field"><?= h($b['bracket_id']) ?></label></td>
          <td><input class="input-field" type="number" step="0.01" min="0" name="min_weight[]" value="<?= h($b['min_weight']) ?>"></td>
          <td><input class="input-field" type="number" step="0.01" min="0" name="max_weight[]" value="<?= h($b['max_weight']) ?>"></td>
          <td><input class="input-field" type="number" step="0.01" min="0" name="charge[]" value="<?= h($b['charge']) ?>"></td>
        </tr>
        <?php endforeach; ?>
      </table>
      <p><button class="submit-btn" type="submit">Save</button></p>
    </form>
    <p class="form-desc"><?= h($message) ?></p>
  </div>
</body>
</html>
