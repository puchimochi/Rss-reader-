<ul class="error_list">
	<?php foreach ($errors as $error): ?>
	<li><p style="color: red"><?php echo $this->escape($error); ?></p></li>
	<?php endforeach;?>
</ul>