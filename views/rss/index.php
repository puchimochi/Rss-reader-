<?php $this->setLayoutVar('title','RSS reader');?>

<h2>Rss Reader</h2>
<form>
</form>

<hr>
<div>
	<?php foreach($entries as $entry):?>
	title :<?php echo $this->escape($entry['title']);?>
	<hr>
	<br>
	内容：<?php echo $entry['content'];?>
	<hr>
<?php endforeach;?>
</div>
