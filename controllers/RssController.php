<?php

class RssController extends Controller
{
	public function indexAction()
	{
		$rss_url = array(
			'http://www.wretch.cc/blog/benshee&rss20=1',
			'http://www.wretch.cc/blog/yuanx2&rss20=1',
			'http://feed.pixnet.net/blog/posts/rss/haruhii',
			);

		foreach ($rss_url as $url) {
			$rss = simplexml_load_file($url,'SimpleXMLElement', LIBXML_NOCDATA);
			$site_title = $rss->channel->title;
			foreach ($rss->channel->item as $item ) {
				date_default_timezone_set('Asia/Tokyo');
				$date = date('Y/m/d',strtotime($item -> pubDate));
				$entryArr[$date] =array(
					'title' => $item->title,
					'content' => $item->description,
					'date' => $date,);
			}
		}

		usort($entryArr, create_function('$a,$b','return(strtotime($a[\'date\']) < strtotime($b[\'date\']));'));

		return $this->render(array(
			'title' => $site_title,
			'entries' => $entryArr,
			));
	}


}