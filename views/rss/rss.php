<div class = "article">
	<div class = "article_content">
	title:<?php echo $this->escape($entry['title']);?>
	<br>
	投稿日時：<?php echo $this->escape($entry['created_at']);?>
	<br>
	内容：<br>
	<?php echo $entry['content'];?>
	</div>
</div>