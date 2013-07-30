	<div class ="status">
		<div class="status_content">
			名前：
				<a href="<?php echo $base_url;?>/user/<?php echo $this->escape($status['user_name']);?>"><?php echo $this->escape($status['user_name']);?>
				</a>

			<br>

			コメント：<?php echo $this->escape($status['comment']); ?>
		</div>
		<div>
			投稿日時：
				<a href="<?php echo $base_url;?>/user/<?php echo $this->escape($status['user_name']); ?>/status/<?php echo $this->escape($status['id']);?>"><?php echo $this->escape($status['created_at']); ?>
				</a>
		</div>
	</div>

