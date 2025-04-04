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
            margin: 0 auto;
        }

        .gallery {
            display: flex;
            flex-direction: column;
            gap: 20px;
            max-width: 90%;
            padding: 20px;
        }

        .gallery .gallery-container {
            display: flex;
            flex-direction: row;
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
            opacity: 0;
            transform: translateY(20px);
            transition: opacity 1s ease-in;
        }

        .card img.show {
            opacity: 1;
            transform: translateY(0);
        }
    </style>
</head>
<body>

    <div class="gallery">
        <div class="gallery-container">
            <div class="card"><img src="/../AngeloCheese/images/category1.jpg" alt="サンプル画像"></div>
            <div class="card"><img src="/../AngeloCheese/images/category2.jpg" alt="サンプル画像"></div>
            <div class="card"><img src="/../AngeloCheese/images/category3.jpg" alt="サンプル画像"></div>
            <div class="card"><img src="/../AngeloCheese/images/category4.jpg" alt="サンプル画像"></div>            
        </div>

        <div class="gallery-container">
            <div class="card"><img src="/../AngeloCheese/images/category1.jpg" alt="サンプル画像"></div>
            <div class="card"><img src="/../AngeloCheese/images/category2.jpg" alt="サンプル画像"></div>
            <div class="card"><img src="/../AngeloCheese/images/category3.jpg" alt="サンプル画像"></div>
            <div class="card"><img src="/../AngeloCheese/images/category4.jpg" alt="サンプル画像"></div>            
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const containers = document.querySelectorAll('.gallery-container');

            containers.forEach(container => {
                const images = container.querySelectorAll('.card img');

                const observer = new IntersectionObserver((entries, observer) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            images.forEach((img, index) => {
                                setTimeout(() => {
                                    img.classList.add('show');
                                }, index * 200); // 0.2秒ずつ遅らせて表示
                            });
                            observer.unobserve(entry.target);
                        }
                    });
                }, {
                    threshold: 0.2
                });

                // container全体を監視
                observer.observe(container);
            });
        });
    </script>


</body>
</html>
