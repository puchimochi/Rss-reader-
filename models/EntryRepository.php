<?php

class EntryRepository extends DbRepository
{

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
		$stmt = $this->fetchAll($sql,array(
			':status' => $status,
			':site_id'=> $siteId
			));

		if ($stmt) {
			$sql = "
				SELECT * FROM entry WHERE id = ANY(SELECT entry_id FROM sitelist WHERE status = :status) AND site_id = :site_id ORDER BY created_at DESC
			";

		return $this->fetchAll($sql,array(
			':status' => $status,
			':site_id'=> $siteId
			));
		}else{
			return false;
		}
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

	//RSSを追加する際にentryIDを取り出すために
	public function fetchAllEntryId($siteId)
	{
		$sql="
			SELECT id FROM entry WHERE site_id = :site_id
		";
		return $this->fetchAll($sql, array(
			':site_id'=>$siteId));
	}
}