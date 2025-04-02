<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>グリッドギャラリー</title>
    <style>
        body {
            background-color: #2b2b2b;
            color: #fff;
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }

        .gallery {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(330px, 1fr));
            gap: 20px;
            max-width: 90%;
            padding: 20px;
        }

        .card {
            background-color: #000;
            padding: 10px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.5);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card:hover {
            transform: scale(1.05);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.8);
        }

        .card img {
            width: 330px;
            height: 380px;
            object-fit: cover;
            border-radius: 4px;
        }
    </style>
</head>
<body>

    <div class="gallery">
        <div class="card"><img src="/../AngeloCheese/images/category1.jpg" alt="サンプル画像"></div>
        <div class="card"><img src="/../AngeloCheese/images/category2.jpg" alt="サンプル画像"></div>
        <div class="card"><img src="/../AngeloCheese/images/category3.jpg" alt="サンプル画像"></div>
        <div class="card"><img src="/../AngeloCheese/images/category4.jpg" alt="サンプル画像"></div>
    </div>

</body>
</html>
