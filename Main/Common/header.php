<header> 
        <?php include __DIR__.'/../Backend/autoLogin.php'; ?>
        <div class="header-container">
            <a href="#">
                <picture>
                    <source media="(max-width: 510px)" srcset="../images/AngeloCheese_face.png">

                    <source media="(max-width: 768px)" srcset="../images/AngeloCheese_face.png">

                    <img src="../images/AngeloCheese_logo1.png" alt="logo" class="header-logo">
                </picture>
            </a>

            <!-- ハンバーガーメニューのボタン -->
            <div class="hamburger-menu">
                <span></span>
                <span></span>
            </div>

            <nav class="nav-menu">
                <ul class="header-list">
                    <li><a href="#">Home</a></li>
                    <li><a href="OnlineShop.php">Online Shop</a></li>
                    <li><a href="#">Recipe</a></li>
                    <li><a href="#">About</a></li>
                </ul>

                <ul class="cart-login">
                    <li><a href="cart.php"><i class="fa-solid fa-cart-shopping"></i></a></li>
                    <li>
                        <a class="c-block" href="<?php echo isset($_SESSION['user_id']) ? 'myAccount.php' : 'login.php'; ?>">
                            <i class="fa-regular fa-circle-user"></i>
                        </a>
                    </li>
                </ul>

                <img src="../images/AngeloCheese_logo1.png" alt="" class="nav-logo">
            </nav>
        </div>
    </header>

    <script>
        // ハンバーガー開閉
        const menuButton = document.querySelector('.hamburger-menu');
        const navMenu    = document.querySelector('.nav-menu');
        const navLogo    = document.querySelector('.nav-logo');

        menuButton.addEventListener('click', () => {
            navMenu.classList.toggle('active');
            menuButton.classList.toggle("active");
            navLogo.classList.toggle("active");
        });
    </script>
