<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/tR/xhtml/DTD/xhtml-transitional.dtd">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<script src="/js/jquery-2.0.3.min.js"></script>
	<link rel="stylesheet" type="text/css" href="/css/bootstrap.min.css">
	<link href="//cdnjs.bootcss.com/ajax/libs/twitter-bootstrap/2.3.1/css/bootstrap-responsive.min.css" rel="stylesheet">
	<style type="text/css">
      body {
        padding-top: 50px;
        padding-bottom: 40px;
      }
      .sidebar-nav {
        padding: 9px 0;
      }

      @media (max-width: 980px) {
        /* Enable use of floated navbar text */
        .navbar-text.pull-right {
          float: none;
          padding-left: 5px;
          padding-right: 5px;
        }
      }
    </style>
	<title><?php if (isset($title)): echo $this->escape($title).'-'; endif;?>Mochi Blog</title>
</head>
<body>
	<div class="navbar navbar-fixed-top">
		<a class="navbar-brand" href="<?php echo $base_url;?>/">Mochi Blog</a>
		<ul class="nav navbar-nav">
		<?php if($session->isAuthenticated()):?>
			<li class="active"><a href="<?php echo $base_url;?>/">Home</a></li>
			<li><a href="<?php echo $base_url;?>/account">アカウント</a></li>
			<li><a href="<?php echo $base_url;?>/rss">RSSリーダー</a></li>
			<li><a href="<?php echo $base_url;?>/account/signout">ログアウト</a></li>
		<?php else:?>
			<li><a href="<?php echo $base_url;?>/account/signin">ログイン</a></li>
			<li><a href="<?php echo $base_url;?>/account/signuo">アカウント登録</a></li>
		<?php endif;?>
		</ul>
	</div>

	<div id="main">
		<?php echo $_content;?>
	</div>

</body>
</html>