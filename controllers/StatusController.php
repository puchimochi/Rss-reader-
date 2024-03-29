<?php

class Statuscontroller extends Controller{

	protected $auth_actions = array('index','post');

	//	コメントホーム
	public function indexAction(){
		$user = $this->session->get('user');
		$status = $this->db_manager->Status->fetchAllArchivesByUserId($user['id']);

		if(!isset($_GET['page'])){
			$page = '1';
		} else {
			$page = $_GET['page'];
		}

		$statuses = $this->db_manager->Status->fetchAllArchivesByUserIdForNew($user['id'],$page);
		$comment_per_page=5;
		$total = count($status);
		$totalPages = ceil( $total / $comment_per_page);

		$offset = $comment_per_page * ($page -1);
		$from = $offset +1;
		$to = ($offset + $comment_per_page) < $total ? ($offset + $comment_per_page) : $total;


		return $this->render(array(
			'comment' =>'',
			'statuses' => $statuses,
			'_token' => $this->generateCsrfToken('status/post'),
			'totalPages' =>$totalPages,
			'page' => $page,
			'total' => $total,
			'from' => $from,
			'to' => $to,
			));
	}

	//コメント投稿メソッド
	public function postAction(){
		if(!$this->request->isPost()){
			$this->forward404();
		}

		$token = $this->request->getPost('_token');
		if(! $this->checkCsrfToken('status/post',$token)){
			return $this->redirect('/');
		}

		$comment = $this->request->getPost('comment');
		$errors = array();

		if(! strlen($comment)){
			$errors[] = 'コメントを入力してください。';
		}elseif ( mb_strlen($comment) >200) {
			$errors[] = 'コメントは200文字以内で入力してください。';
		}

		if(count($errors) === 0){
			$user = $this->session->get('user');
			$this->db_manager->get('Status')->insert($user['id'],$comment);
			return $this->redirect('/');
		}

		$user = $this->session->get('user');
		$statuses = $this->db_manager->Status->fetchAllArchivesByUserId($user['id']);

		return $this->render(array(
			'errors' => $errors,
			'comment' => $comment,
			'statuses' => $statuses,
			'_token' => $this->generateCsrfToken('statuses/post'),
			),'index');

	}

	//ユーザー一覧
	public function userAction($params){
		$guestUser = $this->db_manager->User->fetchByUserName($params['user_name']);
		if(! $guestUser){
			$this->forward404();
		}

		$statuses = $this->db_manager->Status->fetchAllByUserId($guestUser['id']);

		$user = $this->session->get('user');
		$followings = $this->db_manager->User->fetchAllFollowingsByUserId($user['id']);


		return $this->render(array(
			'user' => $user,
			'guestUser' => $guestUser,
			'statuses' => $statuses,
			'followings' => $followings,
			'_token' => $this->generateCsrfToken('account/follow'),
			));
	}

	//コメント詳細
	public function showAction($params){
		$status = $this->db_manager->Status->fetchByIdAndUserName($params['id'],$params['user_name']);

		if(! $status){
			$this->forward404();
		}

		return $this->render(array(
				'status' => $status,
				));
	}

}