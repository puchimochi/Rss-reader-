<?php

class CategoryRepository extends DbRepository
{
	//category更新
	public function setRssCategory($siteId,$userId)
	{
			$sql = "
			INSERT INTO category(user_id,site_id) VALUES (:user_id,:site_id)
			";
			$stmt = $this->execute($sql,array(
			':user_id' => $userId,
			':site_id' => $siteId,
			));
	}

	//カテゴリを追加
	public function addCategory($userId,$categoryName="uncategorized")
	{
		$sql = "
			INSERT INTO category(user_id,category_name) VALUES (:user_id,:category_name)
			";
			$stmt = $this->execute($sql,array(
			':user_id' => $userId,
			':category_name' => $categoryName,
			));
	}
	//ユーザーが持つカテゴリ情報を取得
	public function getRssInfo($userId)
	{
		$sql=" SELECT site_id ,category_name  FROM category WHERE user_id = :user_id ORDER BY category_name
		";
		return $this->fetchAll($sql, array(
			':user_id' => $userId
			));
	}

	public function getRssId($userId,$categoryName)
	{
		$sql = "
			SELECT site_id FROM category WHERE user_id = :user_id AND category_name = :category_name AND site_id IS NOT NULL
		";
		return $this->fetchAll($sql, array(
			':user_id'=>$userId,
			':category_name'=>$categoryName
			));
	}

	public function deleteFromCategory($userId,$siteId)
	{
		$sql = " DELETE FROM category WHERE user_id = :user_id AND site_id = :site_id
		";
		$stmt = $this->execute($sql, array(
			':user_id'=>$userId,
			':site_id'=>$siteId,
			));
		return $stmt;
	}

	public function changeCategory($userId,$siteId,$categoryName)
	{
		$sql=" UPDATE category SET category_name = :category_name WHERE user_id = :user_id AND site_id = :site_id
		";
		$stmt = $this->execute($sql, array(
			':category_name'=>$categoryName,
			':user_id'=>$userId,
			':site_id'=>$siteId,
			));
		return $stmt;
	}

}