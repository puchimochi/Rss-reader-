<?php

class StatusRepository extends DbRepository{

	public function insert($user_id,$comment){
		$now = new DateTime();

		$sql = "
			INSERT INTO status(user_id,comment,created_at) VALUES (:user_id,:comment,:created_at)
		";

		$stmt = $this->execute($sql,array(
			':user_id' => $user_id,
			':comment' => $comment,
			':created_at' => $now->format('Y-m-d H:i:s'),
			));
	}

	//フォロー中のユーザー投稿と自分の投稿を表示
	public function fetchAllArchivesByUserId($user_id){
		$sql ="
			SELECT a.*,u.user_name
			FROM status a LEFT JOIN user u ON a.user_id = u.id LEFT JOIN following f ON f.following_id = a.user_id AND f.user_id = :user_id
			WHERE f.user_id = :user_id OR u.id = :user_id
			ORDER BY a.created_at DESC
		";

		return $this->fetchAll($sql,array(
			':user_id' => $user_id));
	}

	//ユーザーIDに一致する投稿を投稿日時の降順に全件取得
	public function fetchAllByUserId($user_id){
		$sql ="
			SELECT a.*,u.user_name
			FROM status a LEFT JOIN user u ON a.user_id = u.id
			WHERE u.id = :user_id
			ORDER BY a.created_at DESC
		";

		return $this->fetchAll($sql,array(
			':user_id' => $user_id));
	}

	//コメントIDとユーザーIDはに一致するレコードを1件取得
	public function fetchByIdAndUserName($id,$user_name){
		$sql ="
			SELECT a.*,u.user_name
			FROM status a LEFT JOIN user u ON a.user_id = u.id
			WHERE a.id = :id
				AND u.user_name = :user_name
		";

		return $this->fetch($sql,array(
			':id' => $id,
			':user_name' => $user_name,
			));
	}
}