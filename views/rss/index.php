<?php $this->setLayoutVar('title','RSS reader');?>
<h2>Rss Reader</h2>
<script src="/js/test.js"></script>

<div class="container-fluid">
<div class="row-fluid">
	<div class="span12">

		<div class="input-append">
			<form action= "<?php echo $base_url;?>/rss/add" method = "post">
				<input type="hidden" name="_token" value="<?php echo $this->escape($_token);?>" id="token">
				<?php if(isset($errors) && count($errors) > 0 ):?>
				<?php echo $this->render('errors' , array('errors' => $errors));?>
				<?php endif;?>
				<input class="span2"  type = "text" name="url" size="100" id="appendedInputButton">
				<button class="btn" type="submit" id="addbtn">Go!</button>
			</form>
			<div id="modal">
			</div>
		</div>
		<hr>

		<div class="row-fluid">
			<div class="span9" id="content">
				<?php if(count($entries) === 0):?>
				<h3>Rssを追加してください。</h3>
				<?php else:?>
					<?php foreach($entries as $entry):?>
					<?php echo $this->render('rss/rss',array('entry' => $entry));?>
					<?php endforeach;?>
				<?php endif;?>
			</div>

			<div class="span3">
				<div id="addcategory">
					<form action="<?php echo $base_url;?>/rss/addCategory" method="post">
						<input class="span4"  type = "text" name="category" size="100" >
						<p><input type="submit" id="addbtn" value="追加"></p>
					</form>
				</div>
				<hr>
				<div class="well sidebar-nav">
					<ul class="nav nav-list"　>
						<li class="nav-header"><a href="<?php echo $base_url;?>/rss">RSSホーム</a></li>

					<div>
						<?php foreach($categories as $category => $sites):?>
							<?php $options .="<option value='".$category."'>". $category."</option>";?>
						<?php endforeach;?>
					</div>
<!-- RSSリスト -->
					<div>
						<?php foreach($categories as $category => $sites):?>
						<?php if($category !== 'uncategorized'):?>
						<li id="category" class="active" data-id="<?php echo $this->escape($category);?>"><strong><?php echo $this->escape($category);?></strong></li>
							<ul>
								<?php foreach($sites as $key =>$site):?>
								<?php if(($site['site_id']) !== 'null'):?>
								<li class= "active lists" id = "siteId_<?php echo $this->escape($site['site_id']);?>" data-id="<?php echo $this->escape($site['site_id']);?>">
									<a id = "blog"><?php echo $this->escape(mb_strimwidth($site['site_title'], 0, 35,"..."));?></a>
									<span class="delete">X</span>
									<div name="categorize">
										<form method="POST" action ="<?php echo $base_url;?>/rss/categorize">
											<input type="hidden" name="site_id" value="<?php echo $this->escape($site['site_id']);?>">
											<select name="test"><?php echo $options?></select>
											<input type="submit" value="送信">
										</form>
									</div>
								</li>
								<?php endif;?>
								<?php endforeach;?>
							</ul>
						<?php else:?>
						<br>
						<ul>
							<?php foreach($sites as $key =>$site):?>
								<?php if($site['site_id'] !=='null'):?>
								<li class= "active lists" id = "siteId_<?php echo $this->escape($site['site_id']);?>" data-id="<?php echo $this->escape($site['site_id']);?>">
									<a id = "blog"><?php echo $this->escape(mb_strimwidth($site['site_title'], 0, 35,"..."));?></a>
									<span class="delete">X</span>
									<div name="categorize">
										<form method="POST" action ="<?php echo $base_url;?>/rss/categorize">
											<input type="hidden" name="site_id" value="<?php echo $this->escape($site['site_id']);?>">
											<select name="test"><?php echo $options?></select>
											<input type="submit" value="送信">
										</form>
									</div>
								</li>
								<?php endif;?>
							<?php endforeach;?>
						</ul>
						<?php endif;?>
						<?php endforeach;?>
					</div>
					</ul>
				</div>
			<hr>
			</div>
		</div>

	</div>
</div>
</div>