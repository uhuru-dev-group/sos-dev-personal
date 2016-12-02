<!DOCTYPE html>
<html>
<head>
</head>
<body>
    <header>
        <!-- header.phpファイルを読み込む-->
        <?php echo $header; ?>
    </header>
    <div id="content">
        <!-- 各アクションの内容を読み込む-->
        <?php echo $content; ?>
    </div>
    <footer>
        <!-- footer.phpファイルを読み込む-->
        <?php echo $footer; ?>
    </footer>
</body>
</html>