<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>画像を1枚ずつ追加</title>
  <style>
    #dropArea {
      width: 100%;
      max-width: 500px;
      height: 200px;
      border: 2px dashed #aaa;
      display: flex;
      align-items: center;
      justify-content: center;
      margin-bottom: 20px;
      font-family: sans-serif;
      cursor: pointer;
    }
    #preview img {
      max-width: 120px;
      max-height: 120px;
      margin: 5px;
      object-fit: cover;
    }
    #fileInput {
      /* display: none; */
    }
  </style>
</head>
<body>

    <h2>画像を1枚ずつドロップ or 選択して追加</h2>


    <div id="preview"></div>
    <div id="dropArea">ここに画像をドロップ<br>またはクリックで選択</div>
    <input type="file" id="fileInput" accept="image/*">

    <button id="submitBtn">送信する</button>

    <script>
        const dropArea  = document.getElementById('dropArea');
        const fileInput = document.getElementById('fileInput');
        const preview   = document.getElementById('preview');
        const submitBtn = document.getElementById('submitBtn');

        let selectedFiles = [];

        dropArea.addEventListener('click', () => fileInput.click());

        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropArea.addEventListener(eventName, e => e.preventDefault());
        });

        dropArea.addEventListener('drop', (e) => {
            const files = Array.from(e.dataTransfer.files);
            files.forEach(file => {
            selectedFiles.push(file);
            });
            updatePreview();
        });

        fileInput.addEventListener('change', () => {
            const files = Array.from(fileInput.files);
            files.forEach(file => {
            selectedFiles.push(file);
            });
            updatePreview();
            fileInput.value = ""; // 同じファイルでも再選択できるように
        });

        function updatePreview() {
            preview.innerHTML = '';
            selectedFiles.forEach(file => {
            const reader = new FileReader();
            reader.onload = (e) => {
                const img = document.createElement('img');
                img.src = e.target.result;
                preview.appendChild(img);
            };
            reader.readAsDataURL(file);
            });
        }

        submitBtn.addEventListener('click', () => {
            if (selectedFiles.length === 0) {
            alert('画像を選択してください');
            return;
            }

            const formData = new FormData();
            selectedFiles.forEach((file, i) => {
            formData.append('images[]', file);
            });

            fetch('test2.php', {
            method: 'POST',
            body: formData
            })
            .then(res => res.text())
            .then(html => {
            document.body.innerHTML = html;
            })
            .catch(err => {
            alert('アップロードに失敗しました');
            console.error(err);
            });
        });
    </script>

</body>
</html>
