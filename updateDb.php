<?php

private $dsn ='mysql:dbname=mini_blog;host=localhost';
private $user = 'root';
private $password = '0618';

// access to database
$con = new PDO($dsn,$user,$password);

$sql = " SELECT * FROM site ";
$stmt = $con->query($sql);
$sites = $stmt->fetchAll();

foreach ($sites as $site) {
	$rss = simplexml_load_file($site['site_url'],'SimpleXMLElement', LIBXML_NOCDATA);
	$siteId = $site['site_id'];

	foreach ($rss->channel->item as $item ) {
		date_default_timezone_set('Asia/Tokyo');
		$date = date('Y-m-d H:i:s',strtotime($item -> pubDate));
		if (($item->children('http://purl.org/rss/1.0/modules/content/'))) {
			$content =$item->children('http://purl.org/rss/1.0/modules/content/')->encoded;
			}else{
				$content = $item->description;
			}
		$entry =array(
					'site_id' => $siteId,
					'title' => $item->title,
					'link' => $item->link,
					'content' => $content,
					'date' => $date
					);
		$sql = "INSERT INTO entry(site_id, title, link, content, created_at) VALUES (:site_id,:title,:link,:content,:created_at) WHERE created_at < :created_at";

		$stmt = $con->prepare($sql);
				$stmt->bindValue(':site_id',$entry['site_id'],PDO::PARAM_STR);
				$stmt->bindValue(':title',$entry['title'],PDO::PARAM_STR);
				$stmt->bindValue(':link',$entry['link'],PDO::PARAM_STR);
				$stmt->bindValue(':content',$entry['content'],PDO::PARAM_STR);
				$stmt->bindValue(':created_at',$entry['date'],PDO::PARAM_STR);
				$result = $stmt->execute();
	}
}

unset($con);






