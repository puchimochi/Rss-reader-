<?php $this->setLayoutVar('title','アカウント');?>

<h2><?php echo $this->escape($user['user_name']); ?>のアカウント</h2>

<p>
	ユーザーID：
	<a href="<?php echo $base_url?>/user/<?php echo $this->escape($user['user_name']);?>"><strong><?php echo $this->escape($user['user_name']);?></strong>
	</a>
</p>

<h3>フォロー中</h3>
<?php if(count($followings) > 0): ?>
<ul>
	<?php foreach ($followings as $following): ?>
	<li>
		<a href="<?php echo $base_url;?>/user/<?php echo $this->escape($following['user_name']);?>">
			<?php echo $this->escape($following['user_name']);?>
		</a>
	</li>
<?php endforeach;?>
</ul>
<?php endif;?>


<ul>
	<li>
		<a href="<?php echo $base_url;?>/">ホーム</a>
	</li>
	<li>
		<a href="<?php echo $base_url;?>/account/signout">ログアウト</a>
	</li>
</ul>
