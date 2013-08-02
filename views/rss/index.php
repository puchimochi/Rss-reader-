<?php $this->setLayoutVar('title','RSS reader');?>


<h2>Rss test</h2>
<div id = "rss">
	<hr>

	<?php foreach($entries as $entry ):?>
	<?php echo $this->escape($entry['date']);?>
	<hr>
	<?php echo $this->escape($entry['title']);?>
	<hr>
	<?php echo $entry['content'];?>
	<hr>

<?php endforeach;?>

</div>
