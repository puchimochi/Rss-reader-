<?php

class RssRepository extends DbRepository{

	//siteテーブルにレコード追加
	public function insert($url){
		$now = new DateTime();
		$isExisted = false;

		$sql = " SELECT site_url FROM site WHERE site_url = :site_url
		";
		$stmt = $this->fetch($sql,array(':site_url' => $url));

		if(!$stmt){
			$sql ="
				INSERT INTO site(site_url, created_at) VALUES (:site_url,:created_at)";

			$stmt = $this->execute($sql,array(
				':site_url' => $url,
				':created_at' => $now->format('Y-m-d H:i:s'),
				));

			//PDOオブジェクトを取得
			$con = $this->getConnection();
			//最後に挿入された行の ID あるいはシーケンスの値を返す
			$siteId =$con->lastInsertId();
			$isExisted = false;
		} else {
			$sql = " SELECT site_id FROM site WHERE site_url = :site_url";
			$site= $this->fetch($sql,array(':site_url' => $url));
			$siteId = $site['site_id'];
			$isExisted = true;
		}
		return array(
			'site_id'=> $siteId,
			'isExisted'=>$isExisted
			);
	}

	//sitelistテーブルにレコード追加
	public function insertRssList($siteId,$userId){

		$sql = "
			INSERT INTO sitelist(user_id,site_id) VALUES (:user_id,:site_id)
			";

		$stmt = $this->execute($sql,array(
				':user_id' => $userId,
				':site_id' => $siteId,
				));
	}

	//siteテーブルにサイトタイトルを追加
	public function updateSiteTitle($siteId,$siteTitle)
	{
		$sql ="
			UPDATE site SET site_title = :site_title
			WHERE site_id = :site_id
		";

		$stmt = $this->execute($sql,array(
			':site_title' => $siteTitle,
			':site_id' => $siteId
			));
	}

	//entryテーブルにレコード追加
	public function insertEntry($siteId,$title,$link,$content,$date)
	{
		$sql ="
		INSERT INTO entry(site_id, title, link, content, created_at) VALUES (:site_id,:title,:link,:content,:created_at)
		";

		$stmt = $this->execute($sql,array(
			':site_id' 		=> $siteId,
			':title' 		=> $title,
			':link' 		=> $link,
			':content' 		=> $content,
			':created_at'	=> $date
			));
	}

	/*ユーザーのsite_listを取得
	@params userId
	@return result
	*/
	public function fetchAllRssId($userId)
	{
		$sql ="
			SELECT site_id FROM sitelist WHERE user_id = :user_id
		";

		return $this->fetchAll($sql,array(':user_id' => $userId));
	}

	/*entryDBからデータを取り出す
	*@params siteId
	*@return result
	*/
	public function fetchAllEntry($siteId)
	{
		$sql = "
			SELECT * FROM entry WHERE site_id = :site_id ORDER BY created_at ASC
		";

		return $this->fetchall($sql,array(':site_id' => $siteId));
	}

	//siteDBから各RSSサイトタイトルを取り出す
	public function fetchAllTitle($siteId)
	{
		$sql = "
			SELECT site_id,site_title  FROM site WHERE site_id = :site_id
		";

		return $this->fetchall($sql,array(
			':site_id' => $siteId));
	}

	//sitelistテーブルのsiteid削除
	public function deleteRssFromList($userId,$siteId)
	{
		$sql = "
			DELETE FROM sitelist WHERE user_id = :user_id AND site_id = :site_id
		";

		$stmt = $this->execute($sql,array(
			':user_id' => $userId,
			':site_id' => $siteId,
			));

		return $stmt;
	}

}