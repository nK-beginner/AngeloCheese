<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <p><?php echo $_SESSION['test'] ?></p>
    
    <?php if(!empty($_SESSION['errors'])): ?>
        <div class="error-container">
            <?php foreach($_SESSION['errors'] as $error): ?>
                <p><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</body>
</html>