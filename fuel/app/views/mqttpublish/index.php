<?php
	// actionが未定義の場合は自分に投げる
	$formAttr = [
		'accept-charset' => 'utf-8',
		'method' => 'post',
	];
    // プルダウンの設定
    $envDefault = 'uhuru-dev';
    $envSelect = [
        'dev' => 'uhuru-backend-dev',
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

<div class="container">
    <div class="mt-1">
        <hr>
        <h2>シリアル番号登録</h2>
        <div>
            <?= Form::open($formAttr); // フォームの開きタグ ?>
                <p>シリアル番号：<?= Form::input('printer_serial'); ?></p>
                <p>登録先アプリ：<?= Form::select('regist_env', $envDefault, $envSelect);?></p>
                <?= Form::hidden('regist_type', 'serial'); ?>
                <p><?= Form::button('submit', '登録'); ?></p>
            <?= Form::close(); // フォームの閉じタグ ?>
        </div>
        <hr>
        <h2>仮想プリンタ追加</h2>
        <div>
            <?= Form::open($formAttr); // フォームの開きタグ ?>
                <p>シリアル番号：<?= Form::input('printer_serial'); ?></p>
                <p>登録先アプリ：<?= Form::select('regist_env', $envDefault, $envSelect);?></p>
                <?= Form::hidden('regist_type', 'printer'); ?>
                <p><?= Form::button('submit', '登録'); ?></p>
            <?= Form::close(); // フォームの閉じタグ ?>
        </div>
        <hr>
    </div>
</div>
<!-- こっからフッター -->

</body>
</html>