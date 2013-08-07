<?php $this->setLayoutVar('title','RSS reader');?>

<h2>Rss test</h2>


<div id = "rss">
	<?php foreach($entries as $entry ):?>
	<?php echo $entry['title'];?>
	<hr>
	<?php echo $entry['content'];?>
	<hr>
	<?php echo $entry['date'];?>
	<hr>
	<?php endforeach;?>
 </div>
