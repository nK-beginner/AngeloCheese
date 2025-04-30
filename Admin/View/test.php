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
    <input type="file" class="file-input main-file" accept="image/*" name="image">
</body>
</html>