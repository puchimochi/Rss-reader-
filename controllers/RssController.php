<?php

class RssController extends Controller
{
	//RSSフィード一覧の表示する
	public function indexAction()
	{
		$rss_url = array(
			'http://www.wretch.cc/blog/benshee&rss20=1',
			'http://www.wretch.cc/blog/yuanx2&rss20=1',
			'http://feed.pixnet.net/blog/posts/rss/haruhii',
			//'http://feed.pixnet.net/blog/posts/rss/ccsophia',
			);


		//$cnt = 0;
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
			'site_title' => $site_title,
			'title' => $site_title,
			'entries' => $entryArr,
			'count' => $count,
			));
	}


}