<?php $this->setLayoutVar('title','TEST');?>
<?php foreach($entries as $entry):?>
			<?php echo $this->render('rss/rss',array('entry' => $entry));?>
			<hr>
<?php endforeach;?>
