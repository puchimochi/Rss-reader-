<?php $this->setLayoutVar('title','RSS reader');?>

<h2>Rss Reader</h2>
<form action= "<?php echo $base_url;?>/rss/add" method = "post">
	<input type="hidden" name="_token" value="<?php echo $this->escape($_token);?>">
	<?php if(isset($errors) && count($errors) > 0 ):?>
	<?php echo $this->render('errors' , array('errors' => $errors));?>
	<?php endif;?>
	<div class="input-append">
	<input class="span2"  type = "text" name="url" size="100">
	<input type="submit" value="追加"></p>
	</div>
</form>
<hr>

<div class="container-fluid">
	<div class="row-fluid">
		<div class="col-lg-9 col-lg-push-3">
		<?php foreach($entries as $entry):?>
			<?php echo $this->render('rss/rss',array('entry' => $entry));?>
			<hr>
		<?php endforeach;?>
		</div>
		<div class="col-lg-3 col-lg-pull-9">
			<div class="well sidebar-nav">
			<ul class="nav nav-list">
				<li class="nav-header">Sidebar</li>
				<?php foreach ($siteTitles as $siteTitle):?>
				<li class="active"><a href="#"><?php echo $this->escape($siteTitle['site_title']) ;?></a></li>
				<form action="<?php echo $base_url;?>/rss/delete" method ="post">
				<input type="hidden" name="_token" value="<?php echo $this->escape($_token)?>"/>
				<input type="hidden" name="site_id" value="<?php echo $this->escape($siteTitle['site_id']);?>">
				<button type="submit" class="btn btn-small btn-primary">削除</button>
				</form>
				<?php endforeach;?>
			</ul>
			</div><!--/.well -->
		</div>
	</div>
</div>

		<!-- <?php foreach ($siteTitles as $siteTitle):?> -->
		<!-- <?php echo $this->escape($siteTitle['site_title']) ;?> -->
		<!-- <form action="<?php echo $base_url;?>/rss/delete" method ="post"> -->
			<!-- <input type="hidden" name="_token" value="<?php echo $this->escape($_token)?>"/> -->
			<!-- <input type="hidden" name="site_id" value="<?php echo $this->escape($siteTitle['site_id']);?>"> -->
			<!-- <button type="submit" class="btn btn-small btn-primary">削除</button> -->
		<!-- </form> -->
		<!-- <?php echo $this->escape($siteTitle['site_id']);?> -->
		<!-- <br> -->
	<!-- <?php endforeach;?> -->








