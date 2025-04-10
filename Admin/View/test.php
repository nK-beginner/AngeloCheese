<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>画像アップロード</title>
  <style>
    #drop-area {
      border: 2px dashed #ccc;
      padding: 30px;
      text-align: center;
      cursor: pointer;
      border-radius: 10px;
      transition: background-color 0.3s;
    }
    #drop-area.hover {
      background-color: #f0f0f0;
    }
    #preview {
      margin-top: 20px;
      max-width: 300px;
    }
  </style>
</head>
<body>


    <input type="file" multiple>





  <form id="upload-form" action="upload.php" method="post" enctype="multipart/form-data">
    <input type="file" name="image" id="file-input" accept="image/*">
    <label id="drop-area" for="file-input">
      画像をドラッグ＆ドロップ、またはクリックして選択
    </label>
    <img id="preview" src="" alt="プレビュー画像">
    <br><br>
    <button type="submit">アップロード</button>
  </form>

  <script>
    const dropArea  = document.getElementById("drop-area");
    const fileInput = document.getElementById("file-input");
    const preview   = document.getElementById("preview");

    // 前回選択されたファイルを保持
    let lastSelectedFile = null;

    // ファイル選択 or ドロップ時の処理
    function handleFile(file) {
      if (!file.type.startsWith('image/')) return;
      lastSelectedFile = file;

      const reader = new FileReader();
      reader.onload = (e) => {
        preview.src = e.target.result;
        preview.style.display = 'block';
      };
      reader.readAsDataURL(file);

      // fileInputにファイルを再設定（キャンセル対策）
      const dataTransfer = new DataTransfer();
      dataTransfer.items.add(file);
      fileInput.files = dataTransfer.files;
    }

    // ドラッグオーバー時のスタイル
    dropArea.addEventListener("dragover", (e) => {
      e.preventDefault();
      dropArea.classList.add("hover");
    });

    dropArea.addEventListener("dragleave", () => {
      dropArea.classList.remove("hover");
    });

    dropArea.addEventListener("drop", (e) => {
      e.preventDefault();
      dropArea.classList.remove("hover");
      const file = e.dataTransfer.files[0];
      if (file) handleFile(file);
    });

    // ファイル選択時
    fileInput.addEventListener("change", () => {
      if (fileInput.files.length > 0) {
        handleFile(fileInput.files[0]);
      } else if (lastSelectedFile) {
        // キャンセルされた場合、前のファイルを再設定
        const dataTransfer = new DataTransfer();
        dataTransfer.items.add(lastSelectedFile);
        fileInput.files = dataTransfer.files;
      }
    });
  </script>

</body>
</html>
