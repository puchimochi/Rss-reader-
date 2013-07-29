<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/tR/xhtml/DTD/xhtml-transitional.dtd">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<title><?php if (isset($title)): echo $this->escape($title).'-'; endif;?>Mochi Blog</title>
</head>
<body>

	<div id ="header">
		<h1><a href="<?php echo $base_url;?>/">Mochi Blog</a></h1>
	</div>

	<div id ="navibar">
		<p>
			<?php if($session->isAuthenticated()):?>
			<a href="<?php echo $base_url;?>/">ホーム</a>
			<a href="<?php echo $base_url;?>/account">アカウント</a>
			<a href="<?php echo $base_url;?>/account/signout">ログアウト</a>
		<?php else:?>
			<a href="<?php echo $base_url;?>/accoutn/signin">ログイン</a>
			<a href="<?php echo $base_url;?>/account/signuo">アカウント登録</a>
		<?php endif;?>
		</p>
	</div>

	<div id="main">
		<?php echo $_content;?>
	</div>

</body>
</html>