<header> 
        <?php include __DIR__.'/../../Backend/autoLogin.php'; ?>
        <div class="header-container">
            
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
