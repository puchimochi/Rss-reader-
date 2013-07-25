<?php

class StatusRepository extends DbRepository{

	public function insert($user_id,$comment){
		$now = new DateTime();

		$sql = "
			INSERT INTO status(user_id,comment,created_at) VALUES (:user_id,:comment,:created_at)
		";

		$stmt = $this->execute($sql,array(
			':user_id' => $user_id,
			'comment' => $comment,
			':created_at' => $now->format('Y-m-d H:i:s'),
			));
	}
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


}