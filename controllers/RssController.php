<?php

class RssController extends Controller
{
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
			'sites' => $sites,
			'entries'=> $entries,
			));

	}

	/**
	*新サイトを追加する
	*@params $url
	*/
	public function addAction()
	{
		$user = $this->session->get('user');
		$userId = $user['id'];

		$url = 'http://www.wretch.cc/blog/strawberry45&rss20=1';
		$title = "haru";
		$description = "test";
		$result = $this->db_manager->Rss->insert($url,$title,$description);

		if (!$result['isExisted']) {
			$this->updateSiteAction($url,$result['site_id']);
		}

		$this->updateSiteListAction($result['site_id'],$userId);

		return $this->redirect('/rss');
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