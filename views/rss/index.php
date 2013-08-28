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
				<button type="button" data-toggle="modal" data-target="#myModal">執行對話視窗</button>

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
				<div>
					<?php foreach($categories as $category => $sites):?>
						<?php $options .="<option value='".$category."'>". $category."</option>";?>
					<?php endforeach;?>
				</div>
<!-- 				<div id="addcategory">
					<form action="<?php echo $base_url;?>/rss/addCategory" method="post">
						<input class="span4"  type = "text" name="category" size="100" >
						<p><input type="submit" id="addbtn" value="追加"></p>
					</form>
				</div>
				<hr>-->
				<div class="well sidebar-nav">
					<ul class="nav nav-list"　>
						<li class="nav-header">
							<h4>RSSList
								<div class="btn-group pull-right">
								<a class="btn btn-small dropdown-toggle" data-toggle="dropdown" href="#">
								設定
								<span class="caret"></span>
								</a>
								<ul class="dropdown-menu">
									<li><a href="#myModal"  data-toggle="modal">カテゴリ追加</a></li>
									<li><a href="#myModal2" data-toggle="modal">RSS追加</a></li>
								</ul>
								</div>
							</h4>
						</li>
						<li class="active"><a href="<?php echo $base_url;?>/rss"><i class="icon-home icon-white"></i>RSSホーム</a></li>
						<!-- RSSリスト -->
						<?php foreach($categories as $category => $sites):?>
						<?php if($category !== 'uncategorized'):?>
						<li id="category" data-id="<?php echo $this->escape($category);?>"><a><strong><i class="icon-list"></i><?php echo $this->escape($category);?></strong></a></li>
							<ul class="unstyled">
								<?php foreach($sites as $key =>$site):?>
								<?php if(($site['site_id']) !== 'null'):?>
								<li class= "lists" id ="siteId_<?php echo $this->escape($site['site_id']);?>" data-id="<?php echo $this->escape($site['site_id']);?>">
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
							<ul class="unstyled">
							<?php foreach($sites as $key =>$site):?>
								<?php if($site['site_id'] !=='null'):?>
								<li class= "lists" id = "siteId_<?php echo $this->escape($site['site_id']);?>" data-id="<?php echo $this->escape($site['site_id']);?>">
									<a id = "blog"><?php echo $this->escape(mb_strimwidth($site['site_title'], 0, 35,"..."));?></a>
									<!-- <span class="delete">X</span> -->
									<div class="btn-group">
									<i class="icon-wrench dropdown-toggle" data-toggle="dropdown" href="#"></i>
									<ul class="dropdown-menu">
										<li class="delete">delete</li>
									</ul>
									</div>


									<div name="categorize">
										<form method="POST" action ="<?php echo $base_url;?>/rss/categorize">
											<input type="hidden" name="site_id" value="<?php echo $this->escape($site['site_id']);?>">
											<select name="test" ><?php echo $options?></select>
											<input type="submit" value="送信">
										</form>
									</div>
								</li>
								<?php endif;?>
							<?php endforeach;?>
						</ul>
						<?php endif;?>
						<?php endforeach;?>

					</ul>
				</div>
			<hr>
			</div>
		</div>

	</div>
</div>
</div>


				<!-- Modal -->
				<div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
						<h3 id="myModalLabel">カテゴリ追加</h3>
					</div>
					<div class="modal-body">
						<div id="addcategory">
							<form action="<?php echo $base_url;?>/rss/addCategory" method="post">
								<input class="span4"  type = "text" name="category" size="100" >
								<p><input type="submit" id="addbtn" value="追加"></p>
						</form>
						</div>
					</div>
					<div class="modal-footer">
						<button class="btn" data-dismiss="modal" aria-hidden="true">close</button>
						<button class="btn btn-primary">save</button>
					</div>
				</div><!-- Modal -->

				<!-- Modal 2-->
				<div id="myModal2" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
						<h3 id="myModalLabel">カテゴリ追加</h3>
					</div>
					<div class="modal-body">
						<div id="addcategory">
							<form action="<?php echo $base_url;?>/rss/addCategory" method="post">
								<input class="span4"  type = "text" name="category" size="100" >
								<p><input type="submit" id="addbtn" value="追加"></p>
						</form>
						</div>
					</div>
					<div class="modal-footer">
						<button class="btn" data-dismiss="modal" aria-hidden="true">close</button>
						<button class="btn btn-primary">save</button>
					</div>
				</div><!-- Modal -->


				<!-- Modal 3-->
				<div id="myModal3" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
						<h3 id="myModalLabel">カテゴリ追加</h3>
					</div>
					<div class="modal-body">
						<div id="addcategory">
							<form action="<?php echo $base_url;?>/rss/addCategory" method="post">
								<input class="span4"  type = "text" name="category" size="100" >
								<p><input type="submit" id="addbtn" value="追加"></p>
						</form>
						</div>
					</div>
					<div class="modal-footer">
						<button class="btn" data-dismiss="modal" aria-hidden="true">close</button>
						<button class="btn btn-primary">save</button>
					</div>
				</div><!-- Modal -->

				<div class="btn-group">
				<i class="icon-home  dropdown-toggle" data-toggle="dropdown" href="#"></i>
				<ul class="dropdown-menu">
    <!-- dropdown menu links -->
				</ul>
				</div>
