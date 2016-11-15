<?php
	// actionが未定義の場合は自分に投げる
	$formAttr = [
		'accept-charset' => 'utf-8',
		'method' => 'post',
	];
    // プルダウンの設定
    $envDefault = 'jp';
    $envSelect = [
        'jp' => 'sato-backend-jp',
        'qa' => 'sato-backend-qa',
    ];
?>
<!DOCTYPE html>
<html lang="ja">
<head>
<?php
    // cssのcdn
    $bootstrapCdn = 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css';
?>
    <meta charset="utf-8">
    <link rel="stylesheet" href="<?= $bootstrapCdn; ?>">
	<title>仮想プリンタ登録ツール</title>
</head>
<body>

<!-- ここまでヘッダー -->

<div>
	<?= Form::open($formAttr); // フォームの開きタグ ?>
        <p>シリアル：<?= Form::input('printer_serial'); ?></p>
        <p>登録先：<?= Form::select('regist_env', $envDefault, $envSelect);?></p>
		<p><?= Form::button('submit', '登録'); ?></p>
	<?= Form::close(); // フォームの閉じタグ ?>
</div>

<!-- こっからフッター -->

</body>
</html>