<?php

class RssController extends Controller
{
	//RSSフィード一覧の表示する
	public function indexAction()
	{

		//$cnt = 0;
		$rss_url = array(
			'http://www.wretch.cc/blog/benshee&rss20=1',
			'http://www.wretch.cc/blog/yuanx2&rss20=1',
			'http://feed.pixnet.net/blog/posts/rss/haruhii',
			//'http://feed.pixnet.net/blog/posts/rss/ccsophia',
			);
		foreach ($rss_url as $url) {
			$rss = simplexml_load_file($url,'SimpleXMLElement', LIBXML_NOCDATA);
			//$num_of_data = 0;
			if(!$rss){
				$this->forward404();
			}else{
				$site_title = $rss->channel->title;
				foreach ($rss->channel->item as $item ) {
					date_default_timezone_set('Asia/Tokyo');
					$date = date('Y/m/d',strtotime($item -> pubDate));
					if (($item->children('http://purl.org/rss/1.0/modules/content/'))) {
						$content =$item->children('http://purl.org/rss/1.0/modules/content/')->encoded;
					}else{
						$content = $item->description;
					}

					$entryArr[] =array(
						'title' => $item->title,
						'content' => $content,
						'date' => $date,);

					//$num_of_data ++;
					//if ($num_of_data > 3) {break;}
				}
			}
		}

		usort($entryArr, create_function('$a,$b','return(strtotime($a[\'date\']) < strtotime($b[\'date\']));'));
		$count = count($entryArr);


		return $this->render(array(
			//'site' => $site,
			//'articles' => $articles,

			'site_title' => $site_title,
			'title' => $site_title,
			'entries' => $entryArr,
			'count' => $count,

			));
	}

	/**
	*新サイトを追加する
	*@params $url
	*/
	public function addAction()
	{
		$url = 'http://www.wretch.cc/blog/benshee&rss20=1';

		$title = 'mochimochi';
		$description = 'test';
		$user = $this->session->get('user');
		$user_id = $user['id'];

		$site_id = $this->db_manager->get('Rss')->insert($url,$title,$description,$user_id);
		$this->updateSiteAction($url,$site_id);

		return $this->redirect('/rss');
	}

	/**
	* 新しいURLが追加された時にデータを取得しDBに格納する
	* @params $url
	*/
	public function updateSiteAction($url,$site_id)
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
						'site_id' => $site_id,
						'title' => $item->title,
						'link' => $item->link,
						'content' => $content,
						'date' => $date);
				}

				usort($entryArr, create_function('$a,$b','return(strtotime($a[\'date\']) > strtotime($b[\'date\']));'));

				foreach ($entryArr as $entry ) {
					$site_id = $entry['site_id'];
					$title = $entry['title'];
					$link = $entry['link'];
					$content = $entry['content'];
					$date = $entry['date'];
					$this->db_manager->Rss->insertRssData($site_id,$title,$link,$content,$date);
				}
			}
	}




}