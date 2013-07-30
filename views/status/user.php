<?php $this->setLayoutVar('title',$user['user_name']);?>

<h2><?php echo $this->escape($guestUser['user_name']);?>のひとこと</h2>

<?php if(! is_null($followings)): ?>
<?php if($followings): ?>
	<p>フォロー中</p>
<?php else: ?>
	<form action ="<?php echo $base_url;?>/follow" method ="post">
		<input type="hidden" name="_token" value="<?php echo $this->escape($_token);?>"/>
		<input type="hidden" name="following_name" value="<?php echo $this->escape($user['user_name']);?>"/>
		<input type="submit" value="フォローする？">
	</form>

<?php endif; ?>
<?php endif; ?>

<div id="statuses">
	<?php foreach ($statuses as $status):?>
	<?php echo $this->render('status/status',array('status'=>$status));?>
	<hr>
<?php endforeach;?>
</div>