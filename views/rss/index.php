<?php $this->setLayoutVar('title','RSS reader');?>
<h2>Rss Reader</h2>
<script src="/js/test.js"></script>

<form action= "<?php echo $base_url;?>/rss/add" method = "post">
	<input type="hidden" name="_token" value="<?php echo $this->escape($_token);?>" id="token">
	<?php if(isset($errors) && count($errors) > 0 ):?>
	<?php echo $this->render('errors' , array('errors' => $errors));?>
	<?php endif;?>
	<input class="span2"  type = "text" name="url" size="100" id="url">
	<p><input type="submit" id="addbtn" value="追加"></p>
	</div>
</form>
<hr>

<div class="container-fluid">
	<div class="row-fluid">
		<div class="col-lg-9 col-lg-push-3" id="content">
			<?php if(count($entries) === 0):?>
			<h3>Rssを追加してください。</h3>
		<?php else:?>
			<?php foreach($entries as $entry):?>
			<?php echo $this->render('rss/rss',array('entry' => $entry));?>
			<?php endforeach;?>
		<?php endif;?>
		</div>
		<div class="col-lg-3 col-lg-pull-9">
			<div id="addcategory">
				<form action="<?php echo $base_url;?>/rss/addCategory" method="post">
					<input class="span4"  type = "text" name="category" size="100" id="category">
					<p><input type="submit" id="addbtn" value="追加"></p>
				</form>
			</div>
		<hr>
			<div class="well sidebar-nav">
				<ul class="nav nav-list"　>
					<li class="nav-header"><a href="<?php echo $base_url;?>/rss">RSSホーム</a></li>
				<?php foreach($categories as $category => $sites):?>
				<?php if($category !== 'uncategorized'):?>
					<li id="category" class="active" data-id="<?php echo $this->escape($category);?>"><strong><?php echo $this->escape($category);?></strong></li>
						<ul>
							<?php foreach($sites as $key =>$site):?>
							<?php if(($site['site_id']) !== 'null'):?>
							<li class= "active lists" id = "siteId_<?php echo $this->escape($site['site_id']);?>" data-id="<?php echo $this->escape($site['site_id']);?>">
								<a id = "blog"><?php echo $this->escape(mb_strimwidth($site['site_title'], 0, 35,"..."));?></a>
								<span class="delete">X</span>
							</li>
						<?php endif;?>
						<?php endforeach;?>
						</ul>
					<?php else:?>
					<ul>
						<br>
							<?php foreach($sites as $key =>$site):?>
							<li class= "active lists" id = "siteId_<?php echo $this->escape($site['site_id']);?>" data-id="<?php echo $this->escape($site['site_id']);?>">
								<a id = "blog"><?php echo $this->escape(mb_strimwidth($site['site_title'], 0, 35,"..."));?></a>
								<span class="delete">X</span>
							</li>
						<?php endforeach;?>
						</ul>
					<?php endif;?>
				<?php endforeach;?>
				</ul>
			</div>
			<hr>
			</div>
<!--
			<div class="well sidebar-nav">
			<ul id="lists" class="nav nav-list"　>
				<li class="nav-header"><a href="<?php echo $base_url?>/rss">RSSホーム</a></li>
				<?php foreach ($siteTitles as $siteTitle):?>
				<li class="active" id="siteTitleId_<?php echo $this->escape($siteTitle['site_id']);?>" data-id="<?php echo $this->escape($siteTitle['site_id']);?>"><a id="blog"><?php echo $this->escape($siteTitle['site_title']);?></a>
					<span class="delete">X</span>
				</li>
				<?php endforeach;?>

			</ul>
			</div><!--/.well -->
		</div>
	</div>
</div>








