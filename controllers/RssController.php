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
		// $siteId = '32';
		// $items = $this->db_manager->Rss->fetchAllEntry($siteId);

		return $this->render(array(
			'entries'	=> $entries,
			'siteTitles'=> $siteTitles,
			'_token'	=> $this->generateCsrfToken('rss/add'),
			// 'items' => $items,
			));
	}

	/**
	*新サイトを追加する
	*/
	public function addAction()
	{

		if (!$this->request->isPost()) {
			$this->forward404();
		}

		$token = $this->request->getPost('_token');
		if (! $this->checkCsrfToken('rss/add',$token)) {
			return $this->redirect('/rss');
		}

		$url = $this->request->getPost('url');
		$errors = array();


		if (! strlen($url)) {
			$errors[] = 'URLを入力してください。';
		}else{

			$rss = @simplexml_load_file($url,'SimpleXMLElement', LIBXML_NOCDATA);
			$header = get_headers($url);
			if (!$rss) {
				// 読み込めなかった場合のエラー処理
				$errors[] = '正しいRSSのURLを入力してください。';
			}elseif (!(strstr($header[0], '200'))) {
				//URLが有効かどうかをチェック
				$errors[] = '正しいURLを入力してください。';
			}
		}

		if (count($errors) === 0) {
			$user = $this->session->get('user');
			$result = $this->db_manager->Rss->insert($url);
			$siteId =$result['site_id'];
			if (!$result['isExisted']) {
				$this->updateSiteAction($rss,$siteId);
			}
			$items = $this->db_manager->Rss->fetchAllEntry($siteId);
		foreach ($items as $item =>$value ) {
				$entryId = $value['id'];
				$this->db_manager->Rss->insertRssList($siteId,$user['id'],$entryId);
			}

		return $this->redirect('/rss');
		}

		$user = $this->session->get('user');
		$blog = $this->showAllRss($user);
		$entries = $blog['entries'];
		$siteTitles = $blog['siteTitles'];

		return $this->render(array(
			'errors'	=> $errors,
			'_token'	=> $this->generateCsrfToken('rss/add'),
			'entries'	=> $entries,
			'siteTitles'	=>$siteTitles,
			),'index');

	}

	/*ユーザーのRSSフィードを表示
	@params $user
	*/
	public function showAllRss($user)
	{
		$entries = array();
		$sites = $this->db_manager->Rss->fetchAllRssId($user['id']);
		foreach ($sites as $site) {
			$items= $this->db_manager->Rss->fetchAllEntry($site['site_id']);
			foreach ($items as $item){
				$entries[] = $item;
			}
		}

		usort($entries, create_function('$a,$b','return(strtotime($a[\'created_at\']) < strtotime($b[\'created_at\']));'));

		$siteTitles = array();
		$sites = $this->db_manager->Rss->fetchAllRssId($user['id']);
		foreach ($sites as $site) {
			$items = $this->db_manager->Rss->fetchAllTitle($site['site_id']);
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
			$this->redirect('/');
		}
		//return $this->redirect('/rss');
		$msg = "success";
		echo "success";
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
				$img = ''.$image[1].'.jpg';}

			$entryArr[] =array(
				'site_id' => $siteId,
				'title' => $item->title,
				'link' => $item->link,
				'content' => $content,
				'photo' => $img,
				'date' => $date);
			}

			usort($entryArr, create_function('$a,$b','return(strtotime($a[\'date\']) > strtotime($b[\'date\']));'));

			foreach ($entryArr as $entry ) {
				$siteId = $entry['site_id'];
				$title = $entry['title'];
				$link = $entry['link'];
				$content = $entry['content'];
				$photo  =$entry['photo'];
				$date = $entry['date'];
				$this->db_manager->Rss->insertEntry($siteId,$title,$link,$content,$photo,$date);
			}
		}
	}

//サイドバーのタイトルが押された時にajaxで記事を送信
	public function showlistAction()
	{
		ob_start();
		$siteId = $this->request->getPost('site_id');
		$entries = $this->db_manager->Rss->fetchAllEntry($siteId);
		if (!$entries) {
			echo "false";
		}

		$result =json_encode($entries);
		header("Content-Type: application/json; charset=utf-8");
		echo $result;
		ob_end_flush();
		exit();
	}

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


