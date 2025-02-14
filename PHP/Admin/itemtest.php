




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php echo $_POST['product-name']; ?>
    <?php echo $_POST['product-description']; ?>
    <?php echo $_POST['product-category']; ?>
    <?php echo $_POST['keyword']; ?>
    <?php echo $_POST['size1']; ?>
    <?php echo $_POST['size2']; ?>
    <?php echo $_POST['tax-rate']; ?>
    <?php echo $_POST['price']; ?>
    <?php echo $_POST['tax-included-price']; ?>
    <?php echo $_POST['cost']; ?>
    <?php echo $_POST['expirationdate-min1']; ?>
    <?php echo $_POST['expirationdate-max1']; ?>
    <?php echo $_POST['expirationdate-min2']; ?>
    <?php echo $_POST['expirationdate-max2']; ?>
    <?php
        if (!empty($_FILES['thumbnail']['tmp_name'])) {
            $upload_dir = 'uploads/';
            $name = basename($_FILES['thumbnail']['name']);
            $upload_path = $upload_dir . $name;

            // フォルダがなければ作成
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }

            // ファイルを保存
            move_uploaded_file($_FILES['thumbnail']['tmp_name'], $upload_path);

            // **画像を表示**
            echo "<img src='$upload_path' style='max-width:300px; height:auto; display:block; margin-top:10px;'>";
        }
    ?>
    <?php
        if (!empty($_FILES['file1']['tmp_name'])) {
            $upload_dir = 'uploads/';
            $name = basename($_FILES['file1']['name']);
            $upload_path = $upload_dir . $name;

            // フォルダがなければ作成
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }

            // ファイルを保存
            move_uploaded_file($_FILES['file1']['tmp_name'], $upload_path);

            // **画像を表示**
            echo "<img src='$upload_path' style='max-width:300px; height:auto; display:block; margin-top:10px;'>";
        }
    ?>
    <?php
        if (!empty($_FILES['file2']['tmp_name'])) {
            $upload_dir = 'uploads/';
            $name = basename($_FILES['file2']['name']);
            $upload_path = $upload_dir . $name;

            // フォルダがなければ作成
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }

            // ファイルを保存
            move_uploaded_file($_FILES['file2']['tmp_name'], $upload_path);

            // **画像を表示**
            echo "<img src='$upload_path' style='max-width:300px; height:auto; display:block; margin-top:10px;'>";
        }
    ?>
    <?php
        if (!empty($_FILES['file3']['tmp_name'])) {
            $upload_dir = 'uploads/';
            $name = basename($_FILES['file3']['name']);
            $upload_path = $upload_dir . $name;

            // フォルダがなければ作成
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }

            // ファイルを保存
            move_uploaded_file($_FILES['file3']['tmp_name'], $upload_path);

            // **画像を表示**
            echo "<img src='$upload_path' style='max-width:300px; height:auto; display:block; margin-top:10px;'>";
        }
    ?>
    <?php
        if (!empty($_FILES['file4']['tmp_name'])) {
            $upload_dir = 'uploads/';
            $name = basename($_FILES['file4']['name']);
            $upload_path = $upload_dir . $name;

            // フォルダがなければ作成
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }

            // ファイルを保存
            move_uploaded_file($_FILES['file4']['tmp_name'], $upload_path);

            // **画像を表示**
            echo "<img src='$upload_path' style='max-width:300px; height:auto; display:block; margin-top:10px;'>";
        }
    ?>
    <?php
        if (!empty($_FILES['file5']['tmp_name'])) {
            $upload_dir = 'uploads/';
            $name = basename($_FILES['file5']['name']);
            $upload_path = $upload_dir . $name;

            // フォルダがなければ作成
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }

            // ファイルを保存
            move_uploaded_file($_FILES['file5']['tmp_name'], $upload_path);

            // **画像を表示**
            echo "<img src='$upload_path' style='max-width:300px; height:auto; display:block; margin-top:10px;'>";
        }
    ?>



</body>
</html>