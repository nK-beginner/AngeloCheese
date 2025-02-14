





<!DOCTYPE html>
<html lang="jp">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>サイドバー</title>
    <!-- 共通CSS -->
    <link rel="stylesheet" href="CSS/common.css?v=<?php echo time(); ?>">

    <!-- サイドバー用CSS -->
    <link rel="stylesheet" href="CSS/sidebar.css?v=<?php echo time(); ?>">

    <!-- フォンオーサム -->
    <script src="https://kit.fontawesome.com/5d315d0cb7.js" crossorigin="anonymous"></script>
</head>
<body>
    <!-- サイドバーエリア -->
    <aside class="sidebar">
        <img src="images/AngeloCheese_logo1.png" alt="logo" class="sidebar-logo">

        <div class="sidebar-contents">
            <ul class="menu">
                <li class="item">
                    <div class="item-title">
                        <h3>商品管理</h3>
                        <i class="fa-solid fa-angle-down"></i>
                    </div>
                    
                    <ul class="submenu">
                        <a href="itemAdd.php"><li>商品追加</li></a>
                        <a href="itemDelete.php"><li>商品削除</li></a>
                        <li><a href="#">商品編集</a></li>
                        <li><a href="#">商品一覧</a></li>
                    </ul>
                </li>

                <li class="item">
                    <div class="item-title">
                        <h3>注文管理</h3>
                        <i class="fa-solid fa-angle-down"></i>
                    </div>
                    <ul class="submenu">
                        <li>商品追加</li>
                        <li>商品削除</li>
                        <li>商品編集</li>
                        <li>商品一覧</li>
                    </ul>
                </li>

                <li class="item">
                    <div class="item-title">
                        <h3>顧客管理</h3>
                        <i class="fa-solid fa-angle-down"></i>
                    </div>
                    <ul class="submenu">
                        <li>商品追加</li>
                        <li>商品削除</li>
                        <li>商品編集</li>
                        <li>商品一覧</li>
                    </ul>
                </li>

                <li class="item">
                    <div class="item-title">
                        <h3>在庫管理</h3>
                        <i class="fa-solid fa-angle-down"></i>
                    </div>
                    <ul class="submenu">
                        <li>商品追加</li>
                        <li>商品削除</li>
                        <li>商品編集</li>
                        <li>商品一覧</li>
                    </ul>
                </li>
            </ul>  
        </div>

        <div class="user-info">
            <i class="fa-regular fa-circle-user"></i>
            <p>ゲストさん</p>
        </div>
    </aside>
</body>
</html>