<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/tR/xhtml/DTD/xhtml-transitional.dtd">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?php if (isset($title)): echo $this->escape($title).'-'; endif;?>Mochi Blog</title>
	<!-- Bootstrap -->
	<link href="/css/bootstrap.min.css" rel="stylesheet" media="screen">
	<style>
	body {
		padding-top: 60px; /* 60px to make the container go all the way to the bottom of the topbar */
	}
	</style>
	<link rel="stylesheet" type="text/css" href="/css/bootstrap-responsive.min.css">
	<script src="/js/jquery-2.0.3.min.js"></script>
	<script src="/js/bootstrap.min.js"></script>
	<script type="text/javascript">
	jQuery(document).ready(function(){
		jQuery('a[href^=http]')
		.not('[href*="'+location.hostname+'"]')
		.attr({target:"_blank"})
		.addClass("ex_link")
	;})
	</script>
</head>

<body>
	<div class="navbar navbar-fixed-top navbar-inverse">
		<div class="navbar-inner">
			<a class="brand " href="<?php echo $base_url;?>/">Mochi Blog</a>
			<div class="nav-collapse collapse">
				<ul class="nav">
				<?php if($session->isAuthenticated()):?>
					<li class="active"><a href="<?php echo $base_url;?>/">Home</a></li>
					<li><a href="<?php echo $base_url;?>/account">アカウント</a></li>
					<li><a href="<?php echo $base_url;?>/rss">RSSリーダー</a></li>
					<li><a href="<?php echo $base_url;?>/account/signout">ログアウト</a></li>
				<?php else:?>
					<li><a href="<?php echo $base_url;?>/account/signin">ログイン</a></li>
					<li><a href="<?php echo $base_url;?>/account/signup">アカウント登録</a></li>
				<?php endif;?>
			</ul>
			</div><!--/.nav-collapse -->
		</div>
	</div>

	<div id="main">
		<?php echo $_content;?>
	</div>


</body>
</html>