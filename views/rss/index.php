<?php $this->setLayoutVar('title','RSS reader');?>

<h2>Rss Reader</h2>

<form action= "<?php echo $base_url;?>/rss/add" method = "post">
	<input type="hidden" name="_token" value="<?php echo $this->escape($_token);?>">
	<?php if(isset($errors) && count($errors) > 0 ):?>
	<?php echo $this->render('errors' , array('errors' => $errors));?>
<?php endif;?>
	<p><input type = "text" name="url" size="100"></p>
	<p><input type="submit" value="追加"></p>
</form>

<hr>
<div id="entries">
	<?php foreach($entries as $entry):?>
	<?php echo $this->render('rss/rss',array('entry' => $entry));?>
	<hr>
<?php endforeach;?>
</div>
