<?php

class Statuscontroller extends Controller{

	public function indexAction(){
		$user = $this->session->get('user');
		$statuses = $this->db_manager->get('Status')->fetchAllByUserId($user['id']);

		return $this->render(array(
			'comment' =>'',
			'statuses' => $statuses,
			'_token' => $this->generateCsrfToken('status/post'),
			));
	}

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
		$statuses = $this->db_manager->get('Status')->fetchAllArchivesByUserId($user['id']);

		return $this->render(array(
			'errors' => $errors,
			'comment' => $comment,
			'statuses' => $statuses,
			'_token' => $this->generateCsrfToken('statuses/post'),
			),'index');

	}

	public function userAction($params){
		$user = $this->db_manager->get('User')->fetchByUserName($params['user_name']);
		if(! $user){
			$this->forward404();
		}

		$statuses = $this->db_manager->get('Status')->fetchAllByUserId($user['id']);

		return $this->render(array(
			'user' => $user,
			'statuses' => $statuses,
			));
	}

	public function showAction($params){
		$status = $this->db_manager->get('Status')->fetchByIdAndUserName($params['id'],$params['user_name']);

		if(! $status){
			$this->forward404();
		}

		return $this->render(array(
				'status' => $status,
				));
	}

}