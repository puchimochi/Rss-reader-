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
<p>全<?php echo $total; ?>件の<?php echo $from;?>～<?php echo $to ;?>件を表示しています。</p>
<div id="statuses">
	<?php foreach ($statuses as $status):?>
	<?php echo $this->render('status/status',array('status'=>$status));?>
	<hr>
<?php endforeach; ?>
</div>

<div id = "pages">
	<?php if($page > 1):?>
	<a href="?page=<?php echo ($page-1);?>">前へ</a>
<?php endif;?>
	<?php for ($i = 1;$i <= $totalPages;$i++):?>
		<?php if($page === $i):?>
			<a href="?page=<?php echo $i;?>"><?php echo $i;?></a>
		<?php else: ?>
			<a href="?page=<?php echo $i;?>"><?php echo $i;?></a>
		<?php endif;?>
	<?php endfor;?>

<?php if($page < $totalPages):?>
	<a href="?page=<?php echo ($page +1);?>">次へ</a>
<?php endif;?>
</div>
