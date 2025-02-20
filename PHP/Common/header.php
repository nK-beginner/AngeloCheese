




<!DOCTYPE html>
<html lang="jp">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#242424">
    <title>Header</title>
    <!-- 共通CSS -->
    <link rel="stylesheet" href="../css/common.css?v=<?php echo time(); ?>">

    <!-- フォントオーサム -->
    <script src="https://kit.fontawesome.com/5d315d0cb7.js" crossorigin="anonymous"></script>

    <!-- Googleフォント -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Arvo:ital,wght@0,400;0,700;1,400;1,700&family=Dancing+Script:wght@400..700&family=Mea+Culpa&family=Playfair+Display:ital,wght@0,400..900;1,400..900&display=swap" rel="stylesheet">
</head>
<body>
    <header>
        <div class="header-container">
            <a href="#">
                <picture>
                    <!-- スマホ -->
                    <source media="(max-width: 510px)" srcset="../images/AngeloCheese_face.png">

                    <!-- タブレット -->
                    <source media="(max-width: 768px)" srcset="../images/AngeloCheese_logo2.png">

                    <!-- PC -->
                    <img src="../images/AngeloCheese_logo2.png" alt="logo" class="header-logo">
                </picture>
            </a>
            <h1 class="header-title"><a href="#">ONLINE SHOP<span>.</span></a></h1> <!-- オンラインショップ画面へ -->
            <ul class="header-nav">
                <!-- <li><a href="#"><i class="fa-regular fa-heart"></i></a></li>        お気に入り画面へ -->
                <li><a href="onlineShop.php"><i class="fa-solid fa-cart-shopping"></i></a></li>  <!-- カート内画面へ -->
                <li><a href="login.php"><i class="fa-regular fa-circle-user"></i></a></li>  <!-- ユーザー画面へ -->
            </ul>
        </div>
    </header>    
</body>
</html>



