<?php $this->setLayoutVar('title','RSS reader');?>
<script src="/js/test.js"></script>

<div class="well">
	<h2>Rss Reader</h2>
</div>
<hr>
<div class="row-fluid">
	<div class="container-fluid">
		<div class="row-fluid">
			<div class="span3 well">
				<div class="sidebar-nav">
					<!-- 設定ボタングループ -->
					<div class="btn-group">
						<button class="btn" data-toggle="modal" data-target="#addRss"><i class="icon-plus"></i>Rss</button>
						<button class="btn" data-toggle="modal" data-target="#addTag"><i class="icon-plus"></i>Tag</button>
					</div><!-- 設定ボタングループ -->
					<!-- RSSリスト -->
					<ul class="nav nav-list"　>
						<li class="nav-header">
						<h4>RSSList</h4>
						</li>
						<li class="active"><a href="<?php echo $base_url;?>/rss"><i class="icon-home icon-white"></i>RSSホーム</a></li>
						<!-- カテゴリ別RSSリスト -->
						<div>
						<?php foreach($categories as $category => $sites):?>
						<?php if($category!== "uncategorized"):?>
							<li id="category" data-id="<?php echo $this->escape($category);?>"><a id="deleteCategory"><strong><i class="icon-list"></i><?php echo $this->escape($category);?></strong></a>
								<ul class="nav nav-list unstyled">
									<?php foreach($sites as $key =>$site):?>
									<?php if(($site['site_id']) !== 'null'):?>
									<li class= "lists" id ="siteId_<?php echo $this->escape($site['site_id']);?>" data-id="<?php echo $this->escape($site['site_id']);?>"><?php echo $this->escape(mb_strimwidth($site['site_title'], 0, 35,"..."));?>
									<div class="btn-group pull-right">
										<i class="icon-wrench dropdown-toggle " data-toggle="dropdown" href="#"></i>
										<ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
											<li class="delete"><a tabindex="-1" href="#"><i class="icon-minus"></i>delete</a>
											</li>
											<li class="dropdown-submenu">
												<a tabindex="-1" href="#"><i class="icon-tags"></i>move</a>
												<ul class="dropdown-menu">
												<?php foreach($categories as $category => $sites):?>
													<li class="categorize"id="categoryName_<?php echo $this->escape($category);?>" data-id="<?php echo $this->escape($category);?>"><a tabindex="-1" class="categories"><?php echo $this->escape($category);?></a></li>
												<?php endforeach;?>
												</ul>
											</li>
										</ul>
									</div>
									</li>
									<?php endif;?>
									<?php endforeach;?>
								</ul>
							</li>
						<?php endif;?>
						<?php endforeach;?>
						</div>
						<br>
						<!-- uncategorizedのRSSリスト -->
						<div>
						<?php foreach($categories as $category => $sites):?>
	 					<?php if ($category === 'uncategorized'):?>
							<ul class="nav nav-list unstyled">
								<?php foreach($sites as $key =>$site):?>
								<?php if($site['site_id'] !=='null'):?>
									<li class= "lists" id = "siteId_<?php echo $this->escape($site['site_id']);?>" data-id="<?php echo $this->escape($site['site_id']);?>">
									<?php echo $this->escape(mb_strimwidth($site['site_title'], 0, 35,"..."));?>
									<div class="btn-group pull-right">
										<i class="icon-wrench dropdown-toggle " data-toggle="dropdown" href="#"></i>
										<ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
											<li class="delete"><a tabindex="-1" href="#"><i class="icon-minus"></i>delete</a></li>
											<li class="dropdown-submenu">
												<a tabindex="-1" href="#"><i class="icon-tags"></i>move</a>
												<ul class="dropdown-menu">
												<?php foreach($categories as $category => $sites):?>
													<li class="categorize"id="categoryName_<?php echo $this->escape($category);?>" data-id="<?php echo $this->escape($category);?>"><a tabindex="-1" class="categories"><?php echo $this->escape($category);?></a></li>
												<?php endforeach;?>
												</ul>
											</li>
										</ul>
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
			</div>
			<div class="span9" id="content">
				<?php if(count($entries) === 0):?>
				<h3>Rssを追加してください。</h3>
				<?php else:?>
					<?php foreach($entries as $entry):?>
					<?php echo $this->render('rss/rss',array('entry' => $entry));?>
					<?php endforeach;?>
				<?php endif;?>
			</div>
		</div>
	</div>
</div>


<!-- RSS追加Modal 4-->
<div id="addRss" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		<h3 id="myModalLabel">rss追加</h3>
	</div>
	<div class="modal-body">
		<div id="addrss">
			<form class="addRss" >
				<input class="span4"  type = "text" name="category" size="100" >
			</form>
		</div>
	</div>
	<div class="modal-footer">
		<button class="btn btn-primary" id="add">add</button>
		<button class="btn" data-dismiss="modal" aria-hidden="true">close</button>
	</div>
</div><!-- Moda4 -->


<!-- Modal 2-->
<div id="addTag" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		<h3 id="myModalLabel">カテゴリ追加</h3>
	</div>
	<div class="modal-body">
		<div>
			<form class="addCategory">
				<input class="span4" type = "text" name="category" size="100" >
			</form>
		</div>
	</div>
	<div class="modal-footer">
		<button class="btn btn-primary" id="addbtn">add</button>
		<button class="btn" data-dismiss="modal" aria-hidden="true">close</button>
	</div>
</div><!-- Moda2 -->
