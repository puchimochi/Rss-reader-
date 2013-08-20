<?php

$dsn ='mysql:dbname=mini_blog;host=localhost';
$user = 'root';
$password = '0618';

// access to database
$con = new PDO($dsn,$user,$password);


$sql = " SELECT * FROM site ";
$stmt = $con->query($sql);
$sites = $stmt->fetchAll();

foreach ($sites as $site) {
	$rss = simplexml_load_file($site['site_url'],'SimpleXMLElement', LIBXML_NOCDATA);

	$siteId = $site['site_id'];
	$sql = "SELECT MAX(created_at) AS created_at FROM entry WHERE site_id = :site_id";
	$stmt = $con->prepare($sql);
	$stmt->bindValue(':site_id',$siteId,PDO::PARAM_STR);
	$stmt->execute();
	$result = $stmt->fetch(PDO::FETCH_ASSOC);
	//$maxDate = $result['created_at'];
	$maxDate = date('Y-m-d H:i:s',strtotime($result['created_at']));

	foreach ($rss->channel->item as $item ) {
		date_default_timezone_set('Asia/Tokyo');
		$date = date('Y-m-d H:i:s',strtotime($item -> pubDate));

		$entries = array();
		if($date > $maxDate){
			//CDATAに入ってるかどうかをチェック
			if (($item->children('http://purl.org/rss/1.0/modules/content/'))) {
				$content =$item->children('http://purl.org/rss/1.0/modules/content/')->encoded;
			}else{
				$content = $item->description;
			}

			$entries[] =array(
				'site_id' => $siteId,
				'title' => $item->title,
				'link' => $item->link,
				'content' => $content,
				'date' => $date
				);

			if (count($entries) > 0) {
				foreach ($entries as $entry) {
					$siteId = $entry['site_id'];
					$title = $entry['title'];
					$link = $entry['link'];
					$content = $entry['content'];
					$date = $entry['date'];

					$sql = "INSERT INTO entry(site_id, title, link, content, created_at) VALUES (:site_id,:title,:link,:content,:created_at) ";

					$stmt = $con->prepare($sql);
					$stmt->bindValue(':site_id',$entry['site_id'],PDO::PARAM_STR);
					$stmt->bindValue(':title',$entry['title'],PDO::PARAM_STR);
					$stmt->bindValue(':link',$entry['link'],PDO::PARAM_STR);
					$stmt->bindValue(':content',$entry['content'],PDO::PARAM_STR);
					$stmt->bindValue(':created_at',$entry['date'],PDO::PARAM_STR);
					$stmt->execute();

					$result = $stmt->execute();

					if (!$result) {
					echo "watch!";
					}
				}
			}else{
				echo "Empty!";
			}
		}
	}
}

unset($con);






