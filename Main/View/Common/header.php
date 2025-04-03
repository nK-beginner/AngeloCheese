<header> 
        <?php include __DIR__.'/../../Backend/autoLogin.php'; ?>
        <div class="header-container">

            <div class="hamburger-menu">
                <span></span>
                <span></span>
            </div>

            <nav class="nav-menu">
                <ul class="nav-1">
                    <li><a href="OnlineShop.php">Product</a></li>
                    <li><a href="#">About</a></li>
                    <li><a href="#">Recipe</a></li>
                </ul>
            </nav>

            <div class="logo">
                <img src="/../AngeloCheese/images/white/AngeloCheese_logo1.png" alt="logo" class="header-logo">
            </div>
            

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
        </div>
    </header>

    <script>
        // ハンバーガー開閉
        const menuButton = document.querySelector('.hamburger-menu');
        const navMenu = document.querySelector('.nav-menu');
        const body = document.body;

        menuButton.addEventListener('click', () => {
            if (navMenu.classList.contains("active")) {
                navMenu.classList.add("closing");
                
                setTimeout(() => {
                    navMenu.classList.remove("active", "closing");
                    body.style.overflow = "";
                }, 300);
                
            } else {
                navMenu.classList.add("active");
                body.style.overflow = "hidden";
            }
            
            menuButton.classList.toggle("active");
        });
    </script>
