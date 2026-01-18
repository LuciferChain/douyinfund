<?php
header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>创作者基金 - 新代币列表</title>
    <style>
        body { font-family: Arial, sans-serif; text-align: center; background: #f0f0f0; }
        h1 { color: #333; }
        iframe { width: 100%; height: 80vh; border: none; margin-top: 20px; }
        a { display: block; margin: 20px; font-size: 20px; color: #007bff; }
    </style>
</head>
<body>
    <h1>创作者基金</h1>
    <p>通过本平台创建的代币，3% 交易税全部支持基金地址。<br>查看所有新代币（图片、市值、持币人数、交易情况）：</p>
    <iframe src="https://flap.sh/board" allowfullscreen></iframe>
    <a href="create.php">去创建新代币 →</a>
</body>
</html>
