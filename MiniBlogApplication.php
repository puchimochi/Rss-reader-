<?php

class MiniBlogApplication extends Application{

	protected $login_action = array('account','signin');

	public function getRootDir()
	{
		return dirname(__FILE__);
	}

	protected function registerRoutes()
	{
		return array(
			'/account'
				=>array('controller'=>'account','action'=>'index'),

			'/account/:action'
				=>array('controller'=>'account'),
			'/'
				=>array('controller' => 'status','action' =>'index'),
			'/status/post'
				=>array('controller' => 'status','action' => 'post'),
			'/user/:user_name'
				=>array('controller' => 'status','action' => 'user'),
			'/user/:user_name/status/:id'
				=>array('controller' => 'status','action' => 'show'),
			'/follow'
				=>array('controller' => 'account' , 'action' => 'follow'),
			'/rss'
				=>array('controller' => 'rss','action' => 'index'),
			'/rss/add'
				=>array('controller' => 'rss' ,'action' => 'add'),
			'/rss/delete'
				=>array('controller' => 'rss' ,'action' => 'deleteRss'),
			'/rss/showlist'
				=>array('controller' => 'rss' ,'action' => 'showlist'),
			'/rss/updatelist'
				=>array('controller' => 'rss' ,'action' => 'updateList'),
			'/rss/change'
				=>array('controller' => 'rss' ,'action' => 'changeEntryStatus'),
			'/rss/addCategory'
				=>array('controller' => 'rss' ,'action' => 'addCategory'),
			'/rss/show'
				=>array('controller' => 'rss' ,'action' => 'show'),
			'/rss/categorize'
				=>array('controller' => 'rss' ,'action' => 'changeCategory'),
			'/rss/changecategoryname'
				=>array('controller' => 'rss' ,'action' => 'changeCategoryName'),
			'/rss/deletecategory'
				=>array('controller' => 'rss' ,'action' => 'deleteRssCategory'),

			);
	}

	protected function configure()
	{
		$this->db_manager->connect('master',array(
			'dsn'      => 'mysql:dbname=mini_blog;host=localhost',
			'user'     => 'root',
			'password' => '0618',
			));
	}

}