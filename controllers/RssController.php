<?php

class RssController extends Controller
{
		protected $auth_actions = array('index','post');

	//RSSフィード一覧の表示する
	public function indexAction()
	{
		$user = $this->session->get('user');
		$sites = $this->db_manager->Rss->fetchAllUrlId($user['id']);
		foreach ($sites as $site ) {
			$items= $this->db_manager->Rss->fetchAllEntry($site['site_id']);
					foreach ($items as $item){
						$entries[] = $item;
			}
		}

		usort($entries, create_function('$a,$b','return(strtotime($a[\'created_at\']) < strtotime($b[\'created_at\']));'));
		return $this->render(array(
			'entries'=> $entries,
			'_token' => $this->generateCsrfToken('rss/add'),
			));
	}

	/**
	*新サイトを追加する
	*@params $url
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
		//$header = get_headers($url);

		if (! strlen($url)) {
			$errors[] = 'URLを入力してください。';
		}/*elseif (filter_var($url, FILTER_VALIDATE_URL, FILTER_FLAG_PATH_REQUIRED)) {
			# URLが正しいかチェック
			$errors[] = '正しいURLを入力してください。';
		}

		elseif (!preg_match('#^HTTP/.*\s+[200|302]+\s#i', $header[0])) {
			# URLが存在しているかチェック
			$errors[] = '正しいURLが必要です。';
		}
*/
		if (count($errors) === 0) {
			$user = $this->session->get('user');
			$userId = $user['id'];

			$result = $this->db_manager->Rss->insert($url);

			if (!$result['isExisted']) {
			$this->updateSiteAction($url,$result['site_id']);
			}

			$this->updateSiteListAction($result['site_id'],$userId);

			return $this->redirect('/rss');

		}

		$user = $this->session->get('user');
		$sites = $this->db_manager->Rss->fetchAllUrlId($user['id']);
		foreach ($sites as $site ) {
			$items= $this->db_manager->Rss->fetchAllEntry($site['site_id']);
					foreach ($items as $item){
						$entries[] = $item;
			}
		}

		usort($entries, create_function('$a,$b','return(strtotime($a[\'created_at\']) < strtotime($b[\'created_at\']));'));


		return $this->render(array(
			'errors'	=> $errors,
			'_token'	=> $this->generateCsrfToken('rss/add'),
			'entries' => $entries,
			),'index');
	}

	public function updateSiteListAction($siteId,$userId)
	{
		$this->db_manager->Rss->insertRssList($siteId,$userId);
	}

	/**
	* 新しいURLが追加された時にデータを取得しDBに格納する
	* @params $url $siteId
	*/
	public function updateSiteAction($url,$siteId)
	{
		$rss = simplexml_load_file($url,'SimpleXMLElement', LIBXML_NOCDATA);

			if(!$rss){
				$this->forward404();
			}else{
				$site_title = $rss->channel->title;
				foreach ($rss->channel->item as $item ) {
					date_default_timezone_set('Asia/Tokyo');
					$date = date('Y-m-d H:i:s',strtotime($item -> pubDate));
					if (($item->children('http://purl.org/rss/1.0/modules/content/'))) {
						$content =$item->children('http://purl.org/rss/1.0/modules/content/')->encoded;
					}else{
						$content = $item->description;
					}

					$entryArr[] =array(
						'site_id' => $siteId,
						'title' => $item->title,
						'link' => $item->link,
						'content' => $content,
						'date' => $date);
				}

				usort($entryArr, create_function('$a,$b','return(strtotime($a[\'date\']) > strtotime($b[\'date\']));'));

				foreach ($entryArr as $entry ) {
					$siteId = $entry['site_id'];
					$title = $entry['title'];
					$link = $entry['link'];
					$content = $entry['content'];
					$date = $entry['date'];
					$this->db_manager->Rss->insertRssData($siteId,$title,$link,$content,$date);
				}
			}
	}




}