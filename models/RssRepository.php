<?php

class RssRepository extends DbRepository{

	public function insert($url,$title,$description,$user_id){
		$now = new DateTime();

		$sql ="
		INSERT INTO site(site_url, site_title, description, created_at) VALUES (:site_url,:site_title,:description,:created_at)
		";

		$stmt = $this->execute($sql,array(
			':site_url' => $url,
			':site_title' => $title,
			':description' => $description,
			':created_at' => $now->format('Y-m-d H:i:s'),
			));
			//PDOオブジェクトを取得
			$con = $this->getConnection();
			//最後に挿入された行の ID あるいはシーケンスの値を返す
			$site_id =$con->lastInsertId();

		$sql = "
			INSERT INTO sitelist(user_id,site_id) VALUES (:user_id,:site_id)
			";

		$stmt = $this->execute($sql,array(
				':user_id' => $user_id,
				':site_id' => $site_id,
				));
		return $site_id;
	}

	public function insertRssData($site_id,$title,$link,$content,$date)
	{
		$sql ="
		INSERT INTO entry(site_id, title, link, content, created_at) VALUES (:site_id,:title,:link,:content,:created_at)
		";

		$stmt = $this->execute($sql,array(
			':site_id' => $site_id,
			':title' => $title,
			':link' => $link,
			':content' => $content,
			':created_at' => $date
			));
	}

	// ユーザーのsite_listを取得
	public function fetchAllUrlId($user_id)
	{
		$sql ="
			SELECT site_id FROM sitelist WHERE user_id = :user_id
		";

		return $this->fetchAll($sql,array(':user_id' => $user_id));
	}

	//entryDBからデータを取り出す
	public function fetchAllEntry($site_id)
	{
		$sql = "
			SELECT * FROM entry WHERE site_id = :site_id
		";

		return $this->fetchAll($sql,array(':site_id' => $site_id));
	}


}