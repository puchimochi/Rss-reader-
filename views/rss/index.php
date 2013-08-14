<?php $this->setLayoutVar('title','RSS reader');?>
<h2>Rss Reader</h2>
<script type="text/javascript" src="/js/rss_ajax.js"></script>
<!-- <form action= "<?php echo $base_url;?>/rss/add" method = "post" id="addform"> -->
<form>
	<input type="hidden" name="_token" value="<?php echo $this->escape($_token);?>" id="token">
	<!-- <?php if(isset($errors) && count($errors) > 0 ):?> -->
	<!-- <?php echo $this->render('errors' , array('errors' => $errors));?> -->
	<!-- <?php endif;?> -->
	<div class="input-append">
	<input class="span2"  type = "text" name="url" size="100" id="url">
	<input type="button" id="addbtn" value="追加"></p>
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
				<li class="active" id="siteTitleId_<?php echo $this->escape($siteTitle['site_id']);?>" data-id="<?php echo $this->escape($siteTitle['site_id']);?>">
				<?php echo $this->escape($siteTitle['site_title']) ;?>
					<span class="delete">X</span>
				</li>
				<!-- <form action="<?php echo $base_url;?>/rss/delete" method ="post">
				<input type="hidden" name="_token" value="<?php echo $this->escape($_token)?>"/>
				<input type="hidden" name="site_id" value="<?php echo $this->escape($siteTitle['site_id']);?>">
				<button type="submit" class="btn btn-small btn-primary" id="deletebtn">削除</button>
				</form>-->
				<?php endforeach;?>
			</ul>
			</div><!--/.well -->
		</div>
	</div>
</div>

<script type="text/javascript" >
$(function(){

});
</script>
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








