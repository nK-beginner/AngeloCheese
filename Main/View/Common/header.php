<header> 
        <?php include __DIR__.'/../../Backend/autoLogin.php'; ?>
        <div class="header-container">

            <picture class="logo">
                    <source media="(max-width: 510px)" srcset="/../AngeloCheese/images/AngeloCheese_face.png">

                    <source media="(max-width: 768px)" srcset="/../AngeloCheese/images/AngeloCheese_face.png">

                    <img src="/../AngeloCheese/images/white/AngeloCheese_logo1.png" alt="logo" class="header-logo">       
            </picture>

            <nav class="nav-menu">
                <ul class="nav-1">
                    <li><a href="OnlineShop.php">Product</a></li>
                    <li><a href="#">About</a></li>
                    <li><a href="#">Recipe</a></li>
                </ul>

                <ul class="nav-2">
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

            <div class="hamburger-menu">
                <span></span>
                <span></span>
            </div>

        </div>
    </header>

    <script>
        // ハンバーガー開閉
        const menuButton = document.querySelector('.hamburger-menu');
        const navMenu    = document.querySelector('.nav-menu');

        menuButton.addEventListener('click', () => {
            navMenu.classList.toggle('active');
            menuButton.classList.toggle("active");
        });
    </script>
