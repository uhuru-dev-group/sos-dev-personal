<?php
	// actionが未定義の場合は自分に投げる
	$formAttr = [
		'accept-charset' => 'utf-8',
		'method' => 'post',
	];
    // cssのcdn
    $bootstrapCdn = 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css';
?>
<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="utf-8">
    <link rel="stylesheet" href="<?= $bootstrapCdn; ?>">
	<title>仮想プリンタ登録ツール</title>
</head>
<body>
	<?= Form::open($formAttr); // フォームの開きタグ ?>
		<p>シリアル：<input type="text" name="printer_serial"></p>
		<p><input type="submit" value="登録"></p>
	<?= Form::close(); // // フォームの閉じタグ ?>
</body>
</html>