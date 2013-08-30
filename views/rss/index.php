<?php $this->setLayoutVar('title','RSS reader');?>
<script src="/js/test.js"></script>

<div class="well">
	<h2>Rss Reader</h2>
	<div id="addrss">
		<form>
		<input type="hidden" name="_token" value="<?php echo $this->escape($_token);?>" id="token">
		<!-- <?php if(isset($errors) && count($errors) > 0 ):?> -->
		<!-- <?php echo $this->render('errors' , array('errors' => $errors));?> -->
		<!-- <?php endif;?> -->
			<div class="input-append">
			<input class="span2"  type = "text" name="url" size="100" id="url">
			<input type="button" id="addbtn" value="GO">
			</div>
		</form>

		</div>
		<div id="modal">
				<button type="button" data-toggle="modal" data-target="#myModal">執行對話視窗</button>
			</div>
</div>

<hr>
<div class="row-fluid">
	<div class="container-fluid">
		<div class="row-fluid">
			<div class="span3">
				<!-- <div>
					<?php foreach($categories as $category => $sites):?>
						<?php $options .="<option value='".$category."'>". $category."</option>";?>
					<?php endforeach;?>
				</div>
				<div>
					<?php foreach($categories as $category => $sites):?>
						<?php $option .="<li>".$category."</li>";?>
					<?php endforeach;?>
				</div> -->

				<div class="well sidebar-nav">
					<ul class="nav nav-list"　>
						<li class="nav-header">
						<h4>RSSList
							<div class="btn-group pull-right">
								<a class="btn btn-small dropdown-toggle" data-toggle="dropdown" href="#">設定	<span class="caret"></span></a>
								<ul class="dropdown-menu">
									<li><a href="#myModal" data-toggle="modal">RSS追加</a></li>
									<li><a href="#myModal2"  data-toggle="modal">カテゴリ追加</a></li>
									<li><a href="#myModal3" data-toggle="modal">カテゴリ削除</a></li>
								</ul>
							</div>
						</h4>
						</li>
						<li class="active"><a href="<?php echo $base_url;?>/rss"><i class="icon-home icon-white"></i>RSSホーム</a></li>
						<!-- RSSリスト -->
						<?php foreach($categories as $category => $sites):?>
						<?php if($category !== 'uncategorized'):?>
						<li id="category" data-id="<?php echo $this->escape($category);?>"><a><strong><i class="icon-list"></i><?php echo $this->escape($category);?></strong></a></li>
							<ul class="unstyled nav nav-list">
								<?php foreach($sites as $key =>$site):?>
								<?php if(($site['site_id']) !== 'null'):?>
								<li class= "lists" id ="siteId_<?php echo $this->escape($site['site_id']);?>" data-id="<?php echo $this->escape($site['site_id']);?>"><?php echo $this->escape(mb_strimwidth($site['site_title'], 0, 35,"..."));?>
									<!-- <span class="delete">X</span> -->
									<div class="btn-group pull-right">
									<i class="icon-wrench dropdown-toggle" data-toggle="dropdown" href="#"></i>
										<ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
											<li class="delete"><a tabindex="-1" href="#"><i class="icon-trash"></i>delete</a></li>
											<li class="dropdown-submenu">
												<a tabindex="-1" href="#">move</a>
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
						<?php else:?>
						<br>
							<ul class="unstyled">
							<?php foreach($sites as $key =>$site):?>
								<?php if($site['site_id'] !=='null'):?>
								<li class= "lists" id = "siteId_<?php echo $this->escape($site['site_id']);?>" data-id="<?php echo $this->escape($site['site_id']);?>">
									<?php echo $this->escape(mb_strimwidth($site['site_title'], 0, 35,"..."));?>
									<!-- <span class="delete">X</span> -->
									<div class="btn-group pull-right">
									<i class="icon-wrench dropdown-toggle " data-toggle="dropdown" href="#"></i>
										<ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
										<li class="delete"><a tabindex="-1" href="#"><i class="icon-trash"></i>delete</a></li>
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
									<!-- <div name="categorize">
										<form method="POST" action ="<?php echo $base_url;?>/rss/categorize">
											<input type="hidden" name="site_id" value="<?php echo $this->escape($site['site_id']);?>">
											<select name="test" ><?php echo $options?></select>
											<input type="submit" value="送信">
										</form>
									</div> -->
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
			<div class="span9" id="content">
				<?php if(count($entries) === 0):?>
				<h3>Rssを追加してください。</h3>
				<?php else:?>
					<?php foreach($entries as $entry):?>
					<?php echo $this->render('rss/rss',array('entry' => $entry));?>
					<?php endforeach;?>
				<?php endif;?></div>
		</div>
	</div>
	</div>
</div>



				<!-- Modal
				<div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
						<h3 id="myModalLabel">Rss追加</h3>
					</div>
					<div class="modal-body">
						<div class="input-append">
							<form action= "<?php echo $base_url;?>/rss/add" method = "post">
								<input type="hidden" name="_token" value="<?php echo $this->escape($_token);?>" id="token">
								<?php if(isset($errors) && count($errors) > 0 ):?>
								<?php echo $this->render('errors' , array('errors' => $errors));?>
								<?php endif;?>
								<input class="span2"  type = "text" name="url" size="100" id="appendedInputButton">
								<button class="btn" type="submit" id="addbtn">Go!</button>
							</form>
						</div>
					</div>
					<div class="modal-footer">
						<button class="btn" data-dismiss="modal" aria-hidden="true">close</button>
						<button class="btn btn-primary">save</button>
					</div>
				</div>-- Modal -->

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
				</div><!-- Moda2 -->


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
				</div><!-- Moda3 -->

<!-- 				<div id="addcategory">
					<form action="<?php echo $base_url;?>/rss/addCategory" method="post">
						<input class="span4"  type = "text" name="category" size="100" >
						<p><input type="submit" id="addbtn" value="追加"></p>
					</form>
				</div>
				<hr>-->