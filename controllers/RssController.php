<?php

class RssController extends Controller
{
		protected $auth_actions = array('index','post');

	//RSSフィード一覧の表示する
	public function indexAction()
	{
		$user = $this->session->get('user');
		$blog = $this->showAllRss($user);
		$entries = $blog['entries'];
		$siteTitles = $blog['siteTitles'];
		$categories = $this->getRssInfo();

		return $this->render(array(
			'entries'	=> $entries,
			'siteTitles'=> $siteTitles,
			'_token'	=> $this->generateCsrfToken('rss/add'),
			'categories'	=>$categories,
			));
	}

	//	新サイトを追加する
	public function addAction()
	{
		if (!$this->request->isPost()) {
			$msg="forbidden!";
			echo $msg;
			exit();
			// $this->forward404();
		}

		$token = $this->request->getPost('_token');
		if (! $this->checkCsrfToken('rss/add',$token)) {
			// return $this->redirect('/rss');
			$msg="forbidden!";
			echo $msg;
			exit();
		}

		$url = $this->request->getPost('url');
		$errors = array();

		if (! strlen($url)) {
			$errors[] = 'URLを入力してください。';
			$msg="error!";
			echo $msg;
			exit();
		}else{
			$rss = @simplexml_load_file($url,'SimpleXMLElement', LIBXML_NOCDATA);
			$header = get_headers($url);
			if (!$rss) {
				// 読み込めなかった場合のエラー処理
				$errors[] = '正しいRSSのURLを入力してください。';
				$msg="error!";
				echo $msg;
				exit();
			}elseif (!(strstr($header[0], '200'))) {
				//URLが有効かどうかをチェック
				$errors[] = '正しいURLを入力してください。';
				$msg="error!";
				echo $msg;
				exit();
			}
		}

		if (count($errors) === 0) {
			$user = $this->session->get('user');
			$userId =$user['id'];
			$result = $this->db_manager->Rss->insert($url);
			$siteId =$result['site_id'];

			if (!$result['isExisted']) {
				$this->updateSiteAction($rss,$siteId);
			}

			$items = $this->db_manager->Entry->fetchAllEntryId($siteId);
			$this->db_manager->Category->setRssCategory($siteId,$userId);
			foreach ($items as $item =>$value ) {
				$entryId = $value['id'];
				$this->db_manager->Rss->insertRssList($siteId,$userId,$entryId);
			}
			$msg="success";
			echo $msg;
			exit();
		}
	}

	/*ユーザーのRSSフィードを表示
	@params $user
	*/
	public function showAllRss($user)
	{
		$entries = array();
		$userId = $user['id'];
		$siteIds = $this->db_manager->Rss->showRssList($userId);
		$items = $this->db_manager->Entry->fetchAllEntry($userId);
		foreach ($items as $item) {
			$entries[] = $item;
		}

		usort($entries, create_function('$a,$b','return(strtotime($a[\'created_at\']) < strtotime($b[\'created_at\']));'));

		$siteTitles = array();
		foreach ($siteIds as $site =>$value) {
			$items = $this->db_manager->Rss->fetchAllTitle($value['site_id']);
			foreach ($items as $item) {
				$siteTitles[] = $item;
			}
		}

		if (empty($entries)) {
			$entries = array();
		}
		if (empty($siteTitles)) {
			$siteTitles = array();
		}

		$blog = array(
			'entries' => $entries,
			'siteTitles' => $siteTitles);
		return $blog;
	}

	//RSSを削除
	public function deleteRssAction()
	{
		$user = $this->session->get('user');
		$siteId = $this->request->getPost('site_id');
		$result = $this->db_manager->Rss->deleteRssFromList($user['id'],$siteId);
		if (!$result) {
			// $this->redirect('/');
			$msg="false";
			echo $msg;
			exit();
		}

		$result=$this->db_manager->Category->deleteFromCategory($user['id'],$siteId);
		if (!$result) {
			// $this->redirect('/rss');
			$msg="false";
			echo $msg;
			exit();
		}

		$msg = "success";
		echo "success";
		exit();
	}

	/*
	* 新しいURLが追加された時にデータを取得しDBに格納する
	* @params $url $siteId*/
	public function updateSiteAction($rss,$siteId)
	{
		if(!$rss){
			$this->forward404();
		}else{
			$siteTitle = $rss->channel->title;
			$this->db_manager->Rss->updateSiteTitle($siteId,$siteTitle);

			foreach ($rss->channel->item as $item ) {
				date_default_timezone_set('Asia/Tokyo');
				$date = date('Y-m-d H:i:s',strtotime($item -> pubDate));
				if (($item->children('http://purl.org/rss/1.0/modules/content/'))) {
					$content =$item->children('http://purl.org/rss/1.0/modules/content/')->encoded;
				}else{
					$content = $item->description;
				}

				if (preg_match('/<img(?:.*?)src=[\"\']([^>]*?)\.(jpg|JPG)[\"\'](?:.*?)>/e' , $content, $image)){
					$img = ''.$image[1].'.jpg';
				}

				$entryArr[] =array(
					'site_id' => $siteId,
					'title' => $item->title,
					'link' => $item->link,
					'content' => $content,
					'photo' => $img,
					'date' => $date
					);
			}

			usort($entryArr, create_function('$a,$b','return(strtotime($a[\'date\']) > strtotime($b[\'date\']));'));

			foreach ($entryArr as $entry ) {
				$siteId = $entry['site_id'];
				$title = $entry['title'];
				$link = $entry['link'];
				$content = $entry['content'];
				$photo  =$entry['photo'];
				$date = $entry['date'];
				$this->db_manager->Entry->insertEntry($siteId,$title,$link,$content,$photo,$date);
			}
		}
	}

//サイドバーのタイトルが押された時にajaxで記事を送信
	public function showlistAction()
	{
		ob_start();
		$siteId = $this->request->getPost('site_id');

		$entries = $this->db_manager->Entry->fetchEntryForOneRss($siteId);
		if (!$entries) {
			$msg = 'false';
			echo  $msg;
			exit();
		}else{
			$result =json_encode($entries);
			header("Content-Type: application/json; charset=utf-8");
			echo $result;
			ob_end_flush();
			exit();
		}
	}
/*
	//JqueryUIで並び替え、データーベースに順番を保存
	public function updateListAction()
	{
		$list=$this->request->getPost('list');
		parse_str($list);
		$count = 1;
		foreach ($siteTitleId as $key => $siteId) {
			$this->db_manager->Rss->updateSequence($count,$siteId);
			$count ++;
		}
	}
*/

	//記事に既読フラグを立つ
	public function changeEntryStatusAction()
	{
		$user = $this->session->get('user');
		$userId = $user['id'];
		$entryId = $this->request->getPost('entry_id');
		$status = 'read';
		$result = $this->db_manager->Entry->updateEntryStatus($status,$userId,$entryId);

		if (!$result) {
			return $this->render(array(
				'result'=>$result
				),'test');
		}else{
			return $this->redirect('/rss');
		}
	}

	// カテゴリ相関メソッド
	/*カテゴリを作る
	@param $user
	*/
	public function addCategoryAction()
	{
		$user = $this->session->get('user');
		$userId = $user['id'];
		$categoryName = $this->request->getPost('category');

		if (!$this->request->isPost()) {
			$msg="forbidden!";
			echo $msg;
			exit();
		}

		if (! strlen($categoryName)) {
			$msg="error!";
			echo $msg;
			exit();
		}

		$result = $this->db_manager->Category->addCategory($userId,$categoryName);
		if (!$result) {
			$msg="error!";
			echo $msg;
			exit();
		}
		$msg = "success";
		echo "success";
		exit();
	}

//カテゴリ情報を取得
	public function getRssInfo()
	{
		$user = $this->session->get('user');
		$userId = $user['id'];
		$sites = $this->db_manager->Category->getRssInfo($userId);
		if (count($sites)===0) {
			$categories = array();
			}
		foreach ($sites as $key => $site) {
			if (isset($site['site_id'])) {
				$siteTitle = $this->db_manager->Rss->fetchTitle($site['site_id']);
				$categories[$site['category_name']][] =array(
							'site_id' => $site['site_id'],
							'site_title' => $siteTitle['site_title'],
						);
			}else{
				$categories[$site['category_name']][]=array(
					'site_id'=>'null',
					'site_title'=>'null');
			}
		}

		if (count($categories)=== 0) {
			$categories = array();
		}
		return $categories;
	}

//カテゴリ別に記事を表示
	public function showAction()
	{
		ob_start();
		$user = $this->session->get('user');
		$userId = $user['id'];
		$category_name = $this->request->getPost('category_name');
		$sites= $this->db_manager->Category->getRssId($userId,$category_name);
		if (!$sites) {
			$msg = 'empty';
			echo  $msg;
			exit();
		}else{
			foreach ($sites as $site) {
			$siteId = $site['site_id'];
			if (!isset($siteId)) {
				$msg = 'bubu';
				echo  $msg;
				exit();
			}
			$items = $this->db_manager->Entry->fetchEntryForOneRss($siteId);
				if (!$items) {
					$msg = 'wrong';
					echo  $msg;
					exit();
				}
				foreach ($items as $item) {
					$entries[]=array(
					'id'=>$item['id'],
					'site_id'=>$item['site_id'],
					'title'=>$item['title'],
					'link'=>$item['link'],
					'content'=>$item['content'],
					'photo'=>$item['photo'],
					'created_at'=>$item['created_at']
					);
				}
			}

			if (count($entries) > 0) {
				usort($entries, create_function('$a,$b','return(strtotime($a[\'created_at\']) < strtotime($b[\'created_at\']));'));
				$result =json_encode($entries);
				header("Content-Type: application/json; charset=utf-8");
				echo $result;
				ob_end_flush();
				exit();
			}else{
				$msg = 'false';
				echo  $msg;
				exit();
			}
		}
	}

//RSSのカテゴリを変更
	public function changeCategoryAction()
	{
		$user = $this->session->get('user');
		$userId = $user['id'];
		if (!$this->request->isPost()) {
			$msg="fobidden";
			echo $msg;
			exit();
		}
		$siteId = $this->request->getPost('site_id');
		if (!isset($siteId)) {
			$msg="error";
			echo $msg;
			exit();
		}
		$categoryName = $this->request->getPost('category_name');
		if (!isset($categoryName)) {
			$msg="error";
			echo $msg;
			exit();
		}
		$result=$this->db_manager->Category->changeCategory($userId,$siteId,$categoryName);
		if (!$result) {
			$msg="error";
			echo $msg;
			exit();
		}
		$msg="success";
			echo $msg;
			exit();
	}

	//カテゴリ名を変更
	public function changeCategoryNameAction()
	{
		if (!$this->request->isPost()) {
			$msg="fobidden";
			echo $msg;
			exit();
		}
		$newCategoryName=$this->request->getPost('new_category_name');
		$categoryName=$this->request->getPost('category_name');

		$user = $this->session->get('user');
		$userId = $user['id'];
		if (!isset($categoryName)) {
			$msg="error";
			echo $msg;
			exit();
		}elseif (!isset($newCategoryName)) {
			$msg="error";
			echo $msg;
			exit();
		}

		$result = $this->db_manager->Category->changeCategoryName($userId,$categoryName,$newCategoryName);
		if (!$result) {
			$msg="bubu";
			echo $msg;
			exit();
		}
		$msg="success";
		echo $msg;
		exit();
	}

	public function deleteRssCategoryAction()
	{
		if (!$this->request->isPost()) {
			$msg="fobidden";
			echo $msg;
			exit();
		}
		$categoryName = $this->request->getPost('category_name');

		if (!isset($categoryName)) {
			$msg="error";
			echo $msg;
			exit();
		}
		$user = $this->session->get('user');
		$userId = $user['id'];
		$result=$this->db_manager->Category->deleteCategory($userId,$categoryName);
		if (!$result) {
			$msg="database";
			echo $msg;
			exit();
		}

		$msg="success";
		echo $msg;
		exit();
	}
}
/*
	//URLごとにデーターを取り出す
	public function showForOneRssAction($params)
	{
		$user = $this->session->get('user');
		$siteId = $params['siteid'];
		$entries = $this->db_manager->Rss->fetchAllEntry($siteId);
		$siteTitle = $this->showAllTitle($user);
		$this->db_manager->Rss->fetchAllTitle($siteId);
		usort($entries, create_function('$a,$b','return(strtotime($a[\'created_at\']) < strtotime($b[\'created_at\']));'));
		return $this->render(array(
			'entries' => $entries,
			'siteTitles' =>$siteTitle,
			),'index');
	}


	// RSSサイトタイトルを表示する
	// @params $user
	// @return site_title,site_id
	public function showAllTitle($user)
	{
		$siteTitles = array();
		$sites = $this->db_manager->Rss->fetchAllRssId($user['id']);
		foreach ($sites as $site) {
			$items = $this->db_manager->Rss->fetchAllTitle($site['site_id']);
			foreach ($items as $item) {
				$siteTitles[] = $item;
			}
		}

		return $siteTitles;
	}
*/


