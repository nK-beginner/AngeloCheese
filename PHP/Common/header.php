<header>
        <?php include __DIR__.'/../Backend/autoLogin.php'; ?>
        <div class="header-container">
            <a href="#">
                <picture>
                    <!-- スマホ -->
                    <source media="(max-width: 510px)" srcset="../images/AngeloCheese_face.png">
                    <!-- タブレット -->
                    <source media="(max-width: 768px)" srcset="../images/AngeloCheese_face.png">
                    <!-- PC -->
                    <img src="../images/AngeloCheese_face.png" alt="logo" class="header-logo">
                </picture>
            </a>
            <!-- オンラインショップ画面へ -->
            <h1 class="header-title"><a href="onlineShop.php">ONLINE SHOP<span>.</span></a></h1>

            <ul class="header-nav">

                <!-- カート内画面へ -->
                <li><a href="cart.php"><i class="fa-solid fa-cart-shopping"></i></a></li>

                <!-- ログインしてればアカウント詳細画面、なければログイン画面へ -->
                <li>
                    <a class="c-block" href="<?php echo isset($_SESSION['user_id']) ? "myAccount.php" : "login.php" ; ?>">
                        <i class="fa-regular fa-circle-user"></i>
                    </a>
                <li>
                
            </ul>
        </div>
    </header>
    