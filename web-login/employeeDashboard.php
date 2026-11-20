<?php
session_start();

if (!isset($_SESSION['EmpID'])) {
    header('Location: ../web-login/loginPage.php');
    exit();
}
?>

<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Employee Dashboard</title>
        <link rel="stylesheet" type="text/css" href="../shared/css/styles.css">
        <link rel="stylesheet" type="text/css" href="../shared/css/loginStyles.css">
    </head>
    <body class="dashboard-page">
        <nav class="nav-bar" aria-label="Main navigation">
            <a class="nav-link active" href="employeeDashboard.php">Dashboard</a>
            <a class="nav-link" href="../web-store/index.php">Webstore</a>
            <form class="nav-logout" action="../backend/logout.php" method="POST">
                <button type="submit">Log Out</button>
            </form>
        </nav>
        <main class="dashboard-content">
            <div class="dashboard-card">
                <h1>Employee Dashboard</h1>
                <h3>Welcome, <?php echo htmlspecialchars($_SESSION['FirstName']); ?>! please select which system to access.</h3>
            </div>
            <div class="dashboard-card">
                <h2>Warehouse System</h2>
                <a class="dashboard-link" href="../web-employee/warehouseIndex.php">Go</a>
            </div>
            <div class="dashboard-card">
                <h2>Reveiving Desk</h2>
                <a class="dashboard-link" href="../web-employee/receivingIndex.php">Go</a>
            </div>
            <?php if ($_SESSION['IsAdmin']): ?>
                <div class="dashboard-card">
                    <h2>Admin System</h2>
                    <a class="dashboard-link" href="../web-admin/index.php">Go</a>
                </div>
            <?php endif; ?>
        </main>
    </body>
</html>
