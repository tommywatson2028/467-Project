<?php
session_start();
echo "Session ID: " . session_id();

function updateTheme($selected) {
    $allowed = ['Classic White', 'Dark Mode', 'Ocean Blue', 'Sunset Orange'];
    if (in_array($selected, $allowed)) {
        $_SESSION['site_theme'] = $selected;
    }
}

// Form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['theme_choice'])) {
    updateTheme($_POST['theme_choice']);
}

// Set variables for output
$currentTheme = $_SESSION['site_theme'] ?? 'Classic White';

// Map names to CSS colors
$colorMap = [
    'Classic White' => '#ffffff',
    'Dark Mode'     => '#2c3e50',
    'Ocean Blue'    => '#3498db',
    'Sunset Orange' => '#e67e22'
];

$bgColor = $colorMap[$currentTheme];
$textColor = ($currentTheme === 'Dark Mode') ? '#ffffff' : '#333333';
?>

<!DOCTYPE html>
<html>
<head>
    <style>
        body { 
            background-color: <?php echo $bgColor; ?>; 
            color: <?php echo $textColor; ?>;
            font-family: sans-serif;
            transition: 0.4s;
        }
        .container { margin: 50px; padding: 20px; border: 1px solid #ccc; border-radius: 8px; background: rgba(255,255,255,0.1); }
    </style>
</head>
<body>

<div class="container">
    <h2>Current Theme: <?php echo htmlspecialchars($currentTheme); ?></h2>

    <form method="POST">
        <?php 
        $options = ['Classic White', 'Dark Mode', 'Ocean Blue', 'Sunset Orange'];
        foreach ($options as $option): 
            $checked = ($currentTheme === $option) ? 'checked' : '';
        ?>
            <label>
                <input type="radio" name="theme_choice" value="<?php echo $option; ?>" <?php echo $checked; ?> onchange="this.form.submit()">
                <?php echo $option; ?>
            </label><br>
        <?php endforeach; ?>
    </form>
</div>

</body>
</html>

