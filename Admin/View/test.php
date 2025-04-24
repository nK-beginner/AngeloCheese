<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<style>
    body {
        display: flex;
        flex-direction: column;
        width: 100px;
        gap: 12px;
    }
</style>
<body>
    <input type="text" id="1" value="1">
    <input type="text" id="2" value="2">
    <input type="text" id="3" value="3">
    <input type="text" id="4" value="4">
    <input type="text" id="5" value="5">
    <input type="text" id="6" value="6">

    <script>
        const one = document.getElementById('1');
        one.addEventListener('change', () => {
            console.log('changed');
        });
    </script>
</body>
</html>