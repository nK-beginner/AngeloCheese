<header>
        <?php include __DIR__.'/../Backend/autoLogin.php'; ?>
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
            <h1 class="header-title"><a href="onlineShop.php">ONLINE SHOP<span>.</span></a></h1> <!-- オンラインショップ画面へ -->
            <ul class="header-nav">
                <!-- <li><a href="#"><i class="fa-regular fa-heart"></i></a></li>        お気に入り画面へ -->
                <li><a href="#"><i class="fa-solid fa-cart-shopping"></i></a></li>  <!-- カート内画面へ -->
                <div class="c-block">
                    <li>
                        <?php if(isset($_SESSION['user_id'])): ?>
                            <!-- ログインしてればカウント詳細画面へ -->
                            <a href="myAccount.php"><i class="fa-regular fa-circle-user"></i></a>

                        <?php else: ?>
                            <!-- ログインしてなければログアウト画面へ -->
                            <a href="login.php"><i class="fa-regular fa-circle-user"></i></a>
                        <?php endif; ?>
                    </li>
                    <p><?php echo isset($_SESSION['user_id']) ? 'アカウント詳細' : 'ログイン'; ?></p>
                </div>
                
            </ul>
        </div>
    </header>
    