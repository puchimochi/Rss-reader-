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
	public function insertRssList($siteId,$userId,$entryId)
	{
		$sql = "
			INSERT INTO sitelist(user_id,site_id,entry_id) VALUES (:user_id,:site_id,:entry_id)
			";

		$stmt = $this->execute($sql,array(
			':user_id' => $userId,
			':site_id' => $siteId,
			':entry_id' =>$entryId
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
	public function insertEntry($siteId,$title,$link,$content,$photo,$date)
	{
		$sql ="
		INSERT INTO entry(site_id, title, link, content,photo, created_at) VALUES (:site_id,:title,:link,:content,:photo,:created_at)
		";

		$stmt = $this->execute($sql,array(
			':site_id' 		=> $siteId,
			':title' 		=> $title,
			':link' 		=> $link,
			':content' 		=> $content,
			':photo'		=>$photo,
			':created_at'	=> $date
			));
	}
	//RSSを追加する際にentryIDを取り出すために
	public function fetchAllEntryId($siteId)
	{
		$sql="
			SELECT id FROM entry WHERE site_id = :site_id
		";
		return $this->fetchAll($sql, array(
			':site_id'=>$siteId));

	}

	/*ユーザーのsite_listを取得
	@params userId
	@return result
	*/
	public function fetchAllRssId($userId)
	{
		$sql ="
			SELECT DISTINCT site_id FROM sitelist WHERE user_id = :user_id ORDER BY seq ASC
		";

		return $this->fetchAll($sql,array(':user_id' => $userId));
	}

	/*entryDBからデータを取り出す
	*@params siteId
	*@return result
	*/
	public function fetchAllEntry($userId,$status = 'unread')
	{
		$sql = "
			SELECT * FROM entry WHERE id =ANY(SELECT entry_id FROM sitelist WHERE user_id = :user_id AND status = :status) ORDER BY created_at ASC
		";

		return $this->fetchall($sql,array(
			':status' => $status,
			':user_id' => $userId
			));
	}

	// 個別のサイト記事をとる
	public function fetchEntryForOneRss($siteId,$status = 'unread')
	{
		$sql="
			SELECT entry_id FROM sitelist WHERE site_id = :site_id AND status = :status
		";
		$stmt = $this->fetch($sql,array(
			':status' => $status,
			':site_id'=> $siteId
			));

		if ($stmt) {
			$sql = "
				SELECT * FROM entry WHERE id = ANY(SELECT entry_id FROM sitelist WHERE site_id = :site_id AND status = :status) ORDER BY created_at DESC
			";

		return $this->fetchAll($sql,array(
			':status' => $status,
			':site_id'=> $siteId
			));
		}else{
			return false;
		}
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

	//順番を更新
	public function updateSequence($count,$siteId)
	{
		$sql = " UPDATE sitelist SET seq = :seq  WHERE site_id = :site_id
			";
		$stmt = $this->execute($sql, array(
			':seq' => $count,
			'site_id'=> $siteId,
			));
	}

	//既読未読状態を変える
	public function updateEntryStatus($status,$userId,$entryId)
	{
		$sql = "
			UPDATE sitelist SET status = :status WHERE user_id = :user_id AND entry_id = :entry_id
		";

		$stmt = $this->execute($sql, array(
			':status' =>$status,
			':user_id' =>$userId,
			':entry_id' =>$entryId
			));

		return $stmt;
	}

}