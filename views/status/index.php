<?php $this->setLayoutVar('title','★ホーム★'); ?>

<h2>ホーム</h2>

<form action="<?php echo $base_url;?>/status/post" method="post">
	<input type="hidden" name="_token" value="<?php echo $this->escape($_token);?>" />

	<?php if(isset($errors) && count($errors) >0 ):?>
	<?php echo $this->render('errors',array('errors'=>$errors));?>
<?php endif; ?>

	<textarea name="comment" row="2" cols="60"><?php echo $this->escape($comment);?></textarea>

	<p>
		<input type="submit" value="コメント">
	</p>
</form>

<hr size="10" color="#0000ff" noshade>

<div id="statuses">
	<?php foreach ($statuses as $status):?>
	<?php echo $this->render('status/status',array('status'=>$status));?>
	<hr>
<?php endforeach; ?>
</div>
