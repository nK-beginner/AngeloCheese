<header> 
        <?php include __DIR__.'/../../Backend/autoLogin.php'; ?>
        <div class="header-container">
            <nav class="nav-1">
                <ul>
                    <li><a href="OnlineShop.php">Product</a></li>
                    <li><a href="#">About</a></li>
                    <li><a href="#">Recipe</a></li>
                </ul>
            </nav>

            <picture class="logo">
                    <source media="(max-width: 510px)" srcset="/../AngeloCheese/images/AngeloCheese_face.png">

                    <source media="(max-width: 768px)" srcset="/../AngeloCheese/images/AngeloCheese_face.png">

                    <img src="/../AngeloCheese/images/white/AngeloCheese_logo1.png" alt="logo" class="header-logo">       
            </picture>

            <nav class="nav-2">
                <ul>
                    <a class="log-cart" href="login.php">
                        <p><?php echo isset($_SESSION['user_id']) ? 'Logout' : 'Login'; ?></p>
                        <li><i class="bi bi-person-circle"></i></li>
                    </a>

                    <a class="log-cart" href="cart.php">
                        <p>Cart</p>
                        <li><i class="bi bi-cart2"></i></li>
                    </a>
                </ul>
            </nav>
            
            <!-- <picture class="logo">
                    <source media="(max-width: 510px)" srcset="/../AngeloCheese/images/AngeloCheese_face.png">

                    <source media="(max-width: 768px)" srcset="/../AngeloCheese/images/AngeloCheese_face.png">

                    <img src="/../AngeloCheese/images/white/AngeloCheese_logo1.png" alt="logo" class="header-logo">       
            </picture>

            <nav>
                <ul>
                    <li><a href="OnlineShop.php">Product</a></li>
                    <li><a href="#">About</a></li>
                    <li><a href="#">Recipe</a></li>

                    <li><a href="login.php"><i class="bi bi-person-circle"></i></a></li>
                    <li><a href="cart.php"><i class="bi bi-cart2"></i></a></li>
                </ul>
            </nav> -->

            <!-- <ul class="pages">
                <li><a href="#">About</a></li>
                <li><a href="#">Product</a></li>
                <li><a href="#">Recipe</a></li>
            </ul>

            <picture class="logo">
                    <source media="(max-width: 510px)" srcset="/../AngeloCheese/images/AngeloCheese_face.png">

                    <source media="(max-width: 768px)" srcset="/../AngeloCheese/images/AngeloCheese_face.png">

                    <img src="/../AngeloCheese/images/white/AngeloCheese_logo1.png" alt="logo" class="header-logo">       
            </picture>

            <ul class="login-cart">
                <li><a href="login.php"><i class="bi bi-person-circle"></i></a></li>
                <li><a href="cart.php"><i class="bi bi-cart2"></i></a></li>
            </ul> -->

            
            <!-- <a href="#">
                <picture>
                    <source media="(max-width: 510px)" srcset="/../AngeloCheese/images/AngeloCheese_face.png">

                    <source media="(max-width: 768px)" srcset="/../AngeloCheese/images/AngeloCheese_face.png">

                    <img src="/../AngeloCheese/images/AngeloCheese_logo1.png" alt="logo" class="header-logo">
                </picture>
            </a>

            ハンバーガーメニューのボタン
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
                        <a class="c-block" href="<?php  isset($_SESSION['user_id']) ? 'myAccount.php' : 'login.php'; ?>">
                            <i class="fa-regular fa-circle-user"></i>
                        </a>
                    </li>
                </ul>

                <img src="../images/AngeloCheese_logo1.png" alt="" class="nav-logo">
            </nav> -->

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
